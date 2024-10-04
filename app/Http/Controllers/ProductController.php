<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $columns = ['name', 'code', 'price', 'stok', 'description', 'image', 'created_at'];

        $query = Product::select('id', ...$columns);

        // Pencarian
        if ($request->has('search') && $request->search['value'] != '') {
            $searchValue = $request->search['value'];
            $query->where(function ($subQuery) use ($columns, $searchValue) {
                foreach ($columns as $column) {
                    $subQuery->orWhere($column, 'like', "%{$searchValue}%");
                }
            });
        }

        // Sorting
        if ($request->order) {
            foreach ($request->order as $order) {
                $query->orderBy($columns[$order['column']], $order['dir']);
            }
        }

        $totalData = $query->count();
        $products = $query->skip($request->start)
            ->take($request->length)
            ->get();

        // Format Data
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'created_at' => Carbon::parse($product->created_at)->format('d-m-Y H:i'),
                'name' => $product->name,
                'code' => $product->code,
                'price' => $product->price,
                'stok' => $product->stok,
                'description' => $product->description,
                'image' => '<a href="/storage/' . $product->image . '" target="_blank"><img class="rounded-4" src="/storage/' . $product->image . '" alt="' . $product->name . '" width="50" height="50"></a>',
                'actions' => '<button class="edit-btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal" data-id="' . $product->id . '"><i class="bi bi-pencil-square"></i> Edit</button>
                              <button class="delete-btn btn btn-danger" data-id="' . $product->id . '"><i class="bi bi-trash"></i> Delete</button>',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096|dimensions:width=400,height=400',
            'code' => 'required|unique:products|alpha_num',
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'stok' => 'required|integer',
            'description' => 'required|min:20',
        ], [
            'image.required' => 'Gambar produk harus diunggah.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Gambar harus dalam format: jpeg, png, jpg, gif.',
            'image.max' => 'Gambar tidak boleh lebih dari 4MB.',
            'image.dimensions' => 'Gambar harus memiliki dimensi 400x400 piksel.',

            'code.required' => 'Kode produk wajib diisi.',
            'code.unique' => 'Kode produk sudah terdaftar, silakan gunakan kode yang lain.',
            'code.alpha_num' => 'Kode produk hanya boleh terdiri dari huruf dan angka.',

            'name.required' => 'Nama produk wajib diisi.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal adalah 1.',

            'stok.required' => 'Stok produk wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',

            'description.required' => 'Deskripsi produk wajib diisi.',
            'description.min' => 'Deskripsi harus memiliki minimal 20 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
            $product->image = $image;
        }
        $product->code = $request->code;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->price = $request->price;
        $product->stok = $request->stok;
        $product->description = $request->description;
        $product->save();

        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        // Validasi input, termasuk gambar opsional
        $request->validate([
            'name' => 'required',
            'code' => 'required|alpha_num|unique:products,code,' . $id,
            'price' => 'required|numeric',
            'stok' => 'required|integer',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096|dimensions:width=400,height=400',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'code.required' => 'Kode produk wajib diisi.',
            'code.unique' => 'Kode produk sudah terdaftar, silakan gunakan kode yang lain.',
            'code.alpha_num' => 'Kode produk hanya boleh terdiri dari huruf dan angka.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'stok.required' => 'Stok produk wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Gambar harus dalam format: jpeg, png, jpg, gif.',
            'image.max' => 'Gambar tidak boleh lebih dari 4MB.',
            'image.dimensions' => 'Gambar harus memiliki dimensi 400x400 piksel.',
        ]);


        $product = Product::findOrFail($id);

        // Mengupdate data produk lainnya
        $product->name = $request->name;
        $product->code = $request->code;
        $product->price = $request->price;
        $product->stok = $request->stok;
        $product->description = $request->description;

        if ($request->hasFile('image')) {
            // Hapus file lama dari storage
            if ($product->image) {
                $imagePath = $product->image;
                Storage::disk('public')->exists($imagePath);
                Storage::disk('public')->delete($imagePath);
            }
            // Simpan gambar baru
            $imageName = Str::random(40) . '.' . $request->image->extension();
            $request->image->storeAs('products', $imageName, 'public');
            $product->image = 'products/' . $imageName; // Simpan nama file ke database
        }

        $product->save();
        return response()->json(['message' => 'Product updated successfully!']);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        Storage::disk('public')->delete($product->image);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}

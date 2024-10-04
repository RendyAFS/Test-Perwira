<div class="card shadow-sm border-0 bg-white p-4 rounded-4 me-3">
    <span class="fw-bold fs-2 mb-4"><i class="bi bi-box-seam"></i> Create Product</span>
    <form id="createProductForm" enctype="multipart/form-data">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control bg-white" id="name" name="name"
                placeholder="Input Nama Product">
            <label for="name">Nama Product</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control bg-white" id="code" name="code"
                placeholder="Input Code Product">
            <label for="code">Code Product</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" inputmode="numeric" class="form-control bg-white" id="price" name="price"
                placeholder="Input Price Product">
            <label for="price">Price Product</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" inputmode="numeric" class="form-control bg-white" id="stok" name="stok"
                placeholder="Input Stok Product">
            <label for="stok">Stok Product</label>
        </div>

        <div class="form-floating mb-3">
            <textarea class="form-control bg-white" placeholder="Description" name="description" id="description"
                style="height: 100px"></textarea>
            <label for="description">Description Product</label>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input class="form-control bg-white" type="file" name="image" id="image">
        </div>

        <div class="containt-submit d-flex justify-content-center">
            <button type="reset" class="btn btn-danger me-2">
                <i class="bi bi-x-lg"></i> Clear
            </button>
            <button type="submit" class="btn btn-success" id="submitButton">
                <i class="bi bi-box-seam" id="submitIcon"></i>
                <span class="spinner-border spinner-border-sm text-light" id="spinner" style="display:none;"
                    role="status"></span>
                <span id="submitText"> Create</span>
            </button>
        </div>
    </form>
</div>

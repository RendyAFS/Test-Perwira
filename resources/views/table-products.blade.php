<div class="card shadow-sm border-0 bg-white p-4 rounded-4">
    <span class="fw-bold fs-2 mb-4"><i class="bi bi-table"></i> List Product</span>
    <table id="productsTable" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th><span class="fw-bold">created_at </span></th>
                <th><span class="fw-bold">Name </span></th>
                <th><span class="fw-bold text-center">Code </span></th>
                <th><span class="fw-bold text-center">Price </span></th>
                <th><span class="fw-bold text-center">Stok </span></th>
                <th><span class="fw-bold">Image </span></th>
                <th><span class="fw-bold">Description </span></th>
                <th><span class="fw-bold">Actions </span></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/products',
                type: 'GET'
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at',
                    visible: false,
                    className: "align-middle",
                    render: function(data, type, full, meta) {
                        const date = new Date(data);
                        return `${date.getDate()}-${date.getMonth() + 1}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}`;
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    className: "align-middle"
                },
                {
                    data: 'code',
                    name: 'code',
                    className: "align-middle text-center"
                },
                {
                    data: 'price',
                    name: 'price',
                    className: "align-middle text-center"
                },
                {
                    data: 'stok',
                    name: 'stok',
                    className: "align-middle text-center"
                },
                {
                    data: 'image',
                    name: 'image',
                    className: "align-middle",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return `<a href="/storage/${data}" target="_blank">
                                <img class="rounded-4" src="/storage/${data}" alt="${full.name}" width="50" height="50">
                            </a>`;
                    }
                },
                {
                    data: 'description',
                    name: 'description',
                    className: "align-middle"
                },
                {
                    data: null,
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return `<button class="edit-btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal" data-id="${full.id}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="delete-btn btn btn-danger" data-id="${full.id}">
                                <i class="bi bi-trash"></i> Delete
                            </button>`;
                    }
                }
            ],
            order: [
                [0, 'desc']
            ],
            responsive: true
        });
    });
</script>

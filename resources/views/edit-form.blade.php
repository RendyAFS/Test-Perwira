<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    @csrf
                    <input type="hidden" name="id" id="editProductId">

                    <div class="mb-3">
                        <label for="editName" class="form-label">Name:</label>
                        <input type="text" name="name" class="form-control" id="editName" required>
                    </div>

                    <div class="mb-3">
                        <label for="editCode" class="form-label">Code:</label>
                        <input type="text" name="code" class="form-control" id="editCode" required>
                    </div>

                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Price:</label>
                        <input type="number" name="price" class="form-control" id="editPrice" required>
                    </div>

                    <div class="mb-3">
                        <label for="editStok" class="form-label">Stock:</label>
                        <input type="number" name="stok" class="form-control" id="editStok" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description:</label>
                        <textarea name="description" class="form-control" id="editDescription" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editImage" class="form-label">Image:</label>
                        <input type="file" name="image" class="form-control" id="editImage">
                    </div>

                    <div class="containt-submit d-flex justify-content-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-danger me-2">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success" id="editSubmitButton">
                            <i class="bi bi-pencil-square" id="editSubmitIcon"></i>
                            <span class="spinner-border spinner-border-sm text-light" id="editSpinner"
                                style="display:none;" role="status"></span>
                            <span id="editSubmitText"> Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

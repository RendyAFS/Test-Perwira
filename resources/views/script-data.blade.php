<script>
    $(document).ready(function() {
        // Setup CSRF token untuk setiap request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // Create Product
        $('#createProductForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $('#submitIcon').hide();
            $('#submitText').show();
            $('#spinner').show();

            $.ajax({
                url: '/products',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Created',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    $('#createProductForm')[0].reset();

                    // Reload DataTables
                    $('#productsTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';

                    $.each(errors, function(key, value) {
                        errorMessages += value + '\n';
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessages,
                        confirmButtonColor: '#198754',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    $('#submitIcon').show();
                    $('#submitText').show();
                    $('#spinner').hide();
                }
            });
        });


        // Event listener BTN Edit
        $(document).on('click', '.edit-btn', function() {
            const productId = $(this).data('id');

            $.ajax({
                url: `/products/${productId}`,
                method: 'GET',
                success: function(product) {
                    $('#editProductId').val(product.id);
                    $('#editName').val(product.name);
                    $('#editCode').val(product.code);
                    $('#editPrice').val(product.price);
                    $('#editStok').val(product.stok);
                    $('#editDescription').val(product.description);
                    $('#editImage').val('');

                    $('#editProductModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });


        // Update Product
        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            const productId = $('#editProductId').val();

            formData.append('_method', 'PUT');

            if (!$('#editImage')[0].files.length) {
                formData.delete('image');
            }

            $('#editSubmitIcon').hide();
            $('#editSubmitText').show();
            $('#editSpinner').show();

            $.ajax({
                url: `/products/${productId}`,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Updated',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    $('#editProductModal').modal('hide');

                    // Reload DataTables
                    $('#productsTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value + '\n';
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        text: errorMessages,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    $('#editSubmitIcon').show();
                    $('#editSubmitText').show();
                    $('#editSpinner').hide();
                }
            });
        });


        // Event listener BTN Delete
        $(document).on('click', '.delete-btn', function() {
            const productId = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Anda tidak dapat mengembalikan data ini setelah dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/products/${productId}`,
                        method: 'DELETE',
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            // Reload DataTables setelah penghapusan
                            $('#productsTable').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus produk.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        });
    });
</script>

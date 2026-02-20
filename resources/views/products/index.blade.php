@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <span>Product List</span>
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Product
        </button>
    </div>

    <div class="card-body">

        <table class="table table-bordered" id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Images</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Product</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="productForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="product_id">

                    <div class="mb-3">
                        <label>Product Name</label>
                        <input type="text" name="product_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Product Price</label>
                        <input type="number" name="product_price" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="product_description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Add New Images</label>
                        <input type="file" name="product_image[]" multiple class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Existing Images</label>
                        <div id="oldImages" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Save Product
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    loadProducts();
});

/* ------------------ LOAD PRODUCTS ------------------ */
function loadProducts()
{
    $.get('/products', function(products){

        let rows = '';

        products.forEach(function(product){

            let imagesHtml = '';

            product.images.forEach(function(img){
                imagesHtml += `<img src="/images/${img.image}" width="50" height="50" class="me-1">`;
            });

            rows += `
                <tr>
                    <td>${product.id}</td>
                    <td>${product.product_name}</td>
                    <td>${product.product_price}</td>
                    <td>${product.product_description}</td>
                    <td>${imagesHtml}</td>
                    <td>
                        <button type="button"
                            onclick="editProduct(${product.id})"
                            class="btn btn-warning btn-sm">
                            Edit
                        </button>
                        <button type="button"
                            onclick="deleteProduct(${product.id})"
                            class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#productTable tbody').html(rows);
    });
}

/* ------------------ ADD & UPDATE ------------------ */
$('#productForm').submit(function(e){
    e.preventDefault();

    let id = $('#product_id').val();
    let formData = new FormData(this);

    let url = id ? "/update/" + id : "/store";

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });

            $('#productForm')[0].reset();
            $('#product_id').val('');
            $('#oldImages').html('');
            $('#modalTitle').text('Add Product');

            // bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
            let modalElement = document.getElementById('addModal');
            let modalInstance = bootstrap.Modal.getInstance(modalElement);

            if (modalInstance) {
                modalInstance.hide();
            }

            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');


            loadProducts();
        }
    });
});

/* ------------------ EDIT PRODUCT ------------------ */
function editProduct(id)
{
    $.get('/edit/' + id, function(product){

        $('#modalTitle').text('Edit Product');
        $('#product_id').val(product.id);

        $('input[name="product_name"]').val(product.product_name);
        $('input[name="product_price"]').val(product.product_price);
        $('textarea[name="product_description"]').val(product.product_description);

        let imagesHtml = '';

        product.images.forEach(function(img){
            imagesHtml += `
                <div class="position-relative">
                    <img src="/images/${img.image}" width="80" class="border">
                    <button type="button"
                        onclick="deleteImage(${img.id})"
                        class="btn btn-sm btn-danger position-absolute top-0 end-0">
                        ×
                    </button>
                </div>
            `;
        });

        $('#oldImages').html(imagesHtml);

        var modal = new bootstrap.Modal(document.getElementById('addModal'));
        modal.show();
    });
}

/* ------------------ DELETE IMAGE ------------------ */
function deleteImage(id)
{
    Swal.fire({
        title: 'Delete Image?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "/delete-image/" + id,
                type: "DELETE",
                success: function(){

                    Swal.fire({
                        icon: 'success',
                        title: 'Image Deleted',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    editProduct($('#product_id').val());
                }
            });
        }
    });
}

/* ------------------ DELETE PRODUCT ------------------ */
function deleteProduct(id)
{
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "/delete/" + id,
                type: "DELETE",
                success: function(response){

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    loadProducts();
                }
            });
        }
    });
}
</script>

@endsection
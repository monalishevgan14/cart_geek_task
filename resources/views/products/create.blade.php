@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header bg-primary text-white">
        Add Product
    </div>

    <div class="card-body">

        <form id="productForm" enctype="multipart/form-data">
            @csrf

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
                <label>Product Images</label>
                <input type="file" name="product_image[]" multiple class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Save Product</button>
        </form>

    </div>
</div>

<script>
$('#productForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "/store",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            alert(response.message);
            $('#productForm')[0].reset();
        }
    });
});
</script>

@endsection
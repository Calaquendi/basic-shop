@extends('app')

@section('content')
    <div class="col-lg-12">

        <h1 class="my-4">Edit Product</h1>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            Name:
            <br />
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" />
            <br />

            Price ($):
            <br />
            <input type="text" name="price" value="{{ $product->price }}" class="form-control" />
            <br />

            Description:
            <br />
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
            <br />

            Category:
            <br />
            <select name="category_id" class="form-controll">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if ($category->id == $product->category_id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
            <br /><br />

            Photo:
            <br />
            @if (!empty($product->photo))
                <img src="{{ asset('storage/'.$product->photo) }}" height="100px">
                <br />
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="delete" name="deletePhoto">
                <label class="form-check-label" for="deletePhoto">
                    Delete Photo?
                </label>
            </div>    
            @endif
            <br />
            <input type="file" name="photo" />
            <br /><br />


            <input type="submit" class="btn btn-primary" value="Save" />
            <br /><br />
        </form>

    </div>
    <!-- /.col-lg-12 -->
@endsection

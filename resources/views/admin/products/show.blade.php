@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mb-2 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3>{{ $edit ? 'Edit Product' : 'View Product' }}</h3>
                    </div>
                    @if(!$edit)
                        <div class="col-auto ms-auto text-end mt-n1">
                            <a href="{{ route('admin.products.show', ['product' => $product, 'edit' => true]) }}" class="btn btn-primary">Edit Product</a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($edit)
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                @if($edit)
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <p class="form-control-plaintext">{{ $product->name }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                @if($edit)
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <p class="form-control-plaintext">{{ $product->description }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                @if($edit)
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <p class="form-control-plaintext">${{ number_format($product->price, 2) }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                @if($edit)
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach(\App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <p class="form-control-plaintext">{{ $product->category->name }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Product Image</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                    </div>
                                @endif
                                @if($edit)
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Leave empty to keep the current image</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($edit)
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                        </form>
                    @else
                        <div class="mb-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
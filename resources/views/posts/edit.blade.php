@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <h4 class="card-header">Edit Post</h4>

            <!-- Display success message if exists -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display validation errors if any -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form to edit the post -->
            <form action="{{ route('posts.confirmedit', $post->id) }}" method="GET">
                @csrf
                <div class="row mb-3">
                    <label for="title" class="col-md-4 col-form-label text-md-end">Title</label>
                    <div class="col-md-6">
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}">
                        @error('title')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                    <div class="col-md-6">
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <label for="category_id" class="col-md-4 col-form-label text-md-end">Category</label>
                    <div class="col-md-6">
                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <label for="status" class="col-md-4 col-form-label text-md-end">Status</label>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="status" id="status" {{ old('status', $post->status) == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-info text-white" onclick="clearForm()">Clear</button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>

    <script>
        function clearForm() {
            $('#title, #description').val('');
        }
    </script>
@endsection

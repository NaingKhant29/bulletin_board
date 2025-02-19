@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="user">
            <h4 class="user-header">Create Post</h4>

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

            <!-- Change form method to POST and pass data to the confirm page -->
            <form action="{{ route('posts.confirm') }}" method="POST" class="create-post-title">
                @csrf
                <div class="mb-3 create-post container-form-post">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                </div>
                
                <div class="mb-3 create-post container-form-post">
                    <label for="description" class="form-label des">Description</label>
                    <textarea name="description" id="description" class="form-control des">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3 create-post container-form-post">
                    <label for="category_id" class="form-label des">Category</label>
                    <select name="category_id" id="category_id" class="form-control des" required>
                        <option value="" disabled selected>Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="btn-create-clear container-form-post">
                    <!-- Submit button to move to confirm page -->
                    <button type="submit" class="btn btn-success container-form-post">Create</button>
                    <button type="button" class="btn btn-info container-form-post" style="color: white"
                        onclick="clearForm()">Clear</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function clearForm() {
            document.getElementById('title').value = '';
            document.getElementById('description').value = '';
            document.getElementById('category_id').selectedIndex = 0; // Reset category dropdown
        }
    </script>
@endsection

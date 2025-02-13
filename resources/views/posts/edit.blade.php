@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="user">
            <h4 class="user-header">Edit Post</h4>

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
            <form action="{{ route('posts.confirmedit', $post->id) }}" method="POST">
                @csrf
                <div class="mb-3 create-post container-form-post top">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $post->title) }}">
                </div>

                <div class="mb-3 create-post container-form-post">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $post->description) }}</textarea>
                </div>

                <!-- Status Toggle Switch -->
                <div class="mb-3 create-post container-form-post">
                    <label for="status" class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="status" id="status"
                            {{ old('status', $post->status) == 1 ? 'checked' : 0 }}>
                    </div>
                </div>
                <div class="btn-create-clear container-form-post">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-info container-form-post" onclick="clearForm()">Clear</button>
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

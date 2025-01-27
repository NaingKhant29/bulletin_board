@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="user">
            <h4 class="user-header">Confirm Post Details</h4>

            <!-- Display the title and description passed from the controller -->
            <div class="mb-3 create-post container-form-post">
                <label for="title" class="form-label">Title</label>
                <p>{{ $title }}</p>
            </div>
            
            <div class="mb-3 create-post container-form-post">
                <label for="description" class="form-label">Description</label>
                <p>{{ $description }}</p>
            </div>

            <!-- Form to confirm the creation of the post -->
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="title" value="{{ $title }}">
                <input type="hidden" name="description" value="{{ $description }}">

                <div class="btn-create-clear">
                    <button type="submit" class="btn btn-success container-form-post">Confirm</button>
                    <a href="{{ route('posts.create') }}" class="btn btn-secondary container-form-post">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

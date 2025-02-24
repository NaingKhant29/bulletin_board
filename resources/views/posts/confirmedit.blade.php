@extends('layouts.app')

@section('content')
@vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="card">
            <h4 class="card-header">Confirm Edit</h4>
            <div class = "confirm-container">
            <!-- Display current and edited data -->
            <div class="space-evenly mb-3">
                <label for="title" class="col-md-4 col-form-label text-md-end">Title</label>
                <div class="w-30 txt-lft">{{ $title }}</div> <!-- Display the title passed from the controller -->
            </div>

            <div class="space-evenly mb-3">
                <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                <div class="w-30 txt-lft">{{ $description }}</div>
                <!-- Display the description passed from the controller -->
            </div>

            <div class="space-evenly mb-3">
                <label for="description" class="col-md-4 col-form-label text-md-end">Category</label>
                <p class="w-30 txt-lft">{{ $category }}</p>
                <!-- Display the description passed from the controller -->
            </div>

            {{-- <div class="mb-3  create-post container-form-post">
                <label for="status" class="form-label">Status</label>
                <p>{{ $status ? 'Active' : 'Inactive' }}</p> <!-- Display the status (Active or Inactive) -->
            </div> --}}

            <!-- Buttons for confirming or canceling the changes -->
            <div class="btn-confirm-cancel">
                <!-- Confirm button -->
                <form action="{{ route('posts.update', $post->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                
                    <!-- Hidden input for category_id -->
                    <input type="hidden" name="category_id" value="{{ $category_id }}">
                
                    <div class="space-evenly mb-3">
                        <label for="status" class="col-md-4 col-form-label text-md-end">Status</label>
                        <div class="w-30 txt-lft">
                            <input type="checkbox" class="form-check-input" id="status"
                                {{ $status == 1 ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="status">
                                {{ $status == 1 ? 'Active' : 'Inactive' }}
                            </label>
                        </div>
                        <input type="hidden" name="status" value="{{ $status }}">
                    </div>
                
                    <div class="btn-create-clear container-form-post">
                        <button type="submit" class="btn btn-primary ">Confirm</button>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-info">Cancel</a>
                    </div>
                </form>
                
            </div>
                <!-- Cancel button (back to the edit page) -->


            </div>
        </div>
    @endsection

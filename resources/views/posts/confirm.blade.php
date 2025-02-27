@extends('layouts.app')

@section('content')
    @vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="card">
            <h4 class="card-header">Are you sure you want to create this post?</h4>

            <div class = "confirm-container">
                <div class="space-evenly mb-3">
                    <label for="title" class="col-md-4 col-form-label text-md-end">Title</label>
                    <div class="w-30 txt-lft">{{ $title }}</div>
                </div>

                <div class="space-evenly mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                    <div class="w-30 txt-lft">{{ $description }}</div>
                </div>
                <div class="space-evenly mb-3">
                    <label for="category" class="col-md-4 col-form-label text-md-end">Category</label>
                    <div class="w-30 txt-lft">{{ $category->name }}</div>
                </div>


                <!-- Form to confirm the creation of the post -->
                <form action="{{ route('posts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <input type="hidden" name="category_id" value="{{ $category_id }}">

                    <div class="btn-create-clear container-form-post">
                        <button type="submit" class="btn btn-primary margin-right mgn-lft">Confirm</button>
                        <a href="{{ route('posts.create', [
                            'title' => $title,
                            'description' => $description,
                            'category_id' => $category_id
                        ]) }}" class="btn btn-info">Cancel</a>
                        
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

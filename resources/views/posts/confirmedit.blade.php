@extends('layouts.app')

@section('content')
    @vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="card form-mg-top">
            <h4 class="card-header">Confirm Edit</h4>
            <div class = "confirm-container">
                <div class="space-evenly mb-3">
                    <label for="title" class="col-md-4 lable col-form-label text-md-end">Title</label>
                    <div class="w-30 txt-lft">{{ $title }}</div>
                </div>

                <div class="space-evenly mb-3">
                    <label for="description" class="col-md-4 lable col-form-label text-md-end">Description</label>
                    <div class="w-30 txt-lft">{{ $description }}</div>
                </div>

                <div class="space-evenly mb-3">
                    <label for="description" class="col-md-4 lable col-form-label text-md-end">Category</label>
                    <p class="w-30 txt-lft">{{ $category }}</p>
                </div>
                <div class="space-evenly mb-3">
                    <label for="status" class="col-md-4 lable col-form-label text-md-end">Status</label>
                    <p class="w-30 txt-lft">
                        <input type="checkbox" class="form-check-input" id="status"
                            {{ $status == 1 ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="status">
                            {{ $status == 1 ? 'Active' : 'Inactive' }}
                        </label>
                    </p>
                </div>
                <div class="btn-confirm-cancel">
                    <form action="{{ route('posts.update', $post->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="title" value="{{ $title }}">
                        <input type="hidden" name="description" value="{{ $description }}">

                        <input type="hidden" name="category_id" value="{{ $category_id }}">

                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="flex-center mb-3">
                            <button type="submit" class="btn btn-primary col-md-4 col-form-label text-md-end">Confirm</button>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-info margin-left">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

@extends('layouts.app')

@php
    info($status);
@endphp

@section('content')
    <div class="container">
        <div class="user">
            <h4 class="user-header">Confirm Edit</h4>

            <!-- Display current and edited data -->
            <div class="mb-3  create-post container-form-post top">
                <label for="title" class="form-label">Title</label>
                <p class="text-align-lft">{{ $title }}</p> <!-- Display the title passed from the controller -->
            </div>

            <div class="mb-3  create-post container-form-post">
                <label for="description" class="form-label">Description</label>
                <p class="text-align-lft">{{ $description }}</p>
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
                    <div class="mb-3  create-post container-form-post">
                        <label for="status" class="form-label">Status</label>
                        <div class="form-check text-align-lft">
                            <!-- Display a toggle switch reflecting the status -->
                            <input type="checkbox" class="form-check-input" id="status"
                                {{ $status == 1 ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="status">
                                {{ $status == 1 ? 'Active' : 'Inactive' }}
                            </label>
                        </div>
                        <!-- Hidden input to carry the status value -->
                        <input type="hidden" name="status" value="{{ $status }}">
                    </div>
                    <!-- Pass edited status -->
                    <div class="btn-create-clear container-form-post">
                        <button type="submit" class="btn btn-success">Confirm</button>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

                <!-- Cancel button (back to the edit page) -->


            </div>
        </div>
    @endsection

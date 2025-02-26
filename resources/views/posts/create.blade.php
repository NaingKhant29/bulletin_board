@extends('layouts.app')

@section('content')
@vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="card">
            <h4 class="card-header">Create Post</h4>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('posts.confirm') }}" method="POST" style="padding: 40px;">
                @csrf
            
                <div class="row mb-3">
                    <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', request('title')) }}">
                        @error('title')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <textarea name="description" id="description" 
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', request('description')) }}</textarea>
                        @error('description')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <label for="category_id" class="col-md-4 col-form-label text-md-end">{{ __('Category') }}</label>
                    <div class="col-md-6">
                        <select name="category_id" id="category_id"
                            class="form-control @error('category_id') is-invalid @enderror">
                            <option value="" disabled>{{ __('Select a Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create') }}
                        </button>
                        <button type="button" class="btn btn-info text-white" id="clear-btn">
                            {{ __('Clear') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        function clearForm() {
            document.getElementById('title').value = '';
            document.getElementById('description').value = '';
            document.getElementById('category_id').selectedIndex = 0; 
        }
    </script>
@endsection

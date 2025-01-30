@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Upload Posts CSV</h2>

    <!-- Display Success or Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Upload Form -->
    <form action="{{ route('posts.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choose CSV File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
        <!-- Clear Button -->
        <button type="button" class="btn btn-secondary" onclick="clearFileInput()">Clear</button>
    </form>

    <hr>

    <p><strong>Instructions:</strong></p>
    <ul>
        <li>The CSV file must have 3 columns: title, description, status.</li>
        <li>Each row in the CSV must represent a post to be uploaded.</li>
    </ul>
</div>

<script>
    function clearFileInput() {
        document.getElementById('file').value = '';
    }
</script>
@endsection

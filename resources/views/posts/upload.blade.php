@extends('layouts.app')

@section('content')

<div class="container">
    <div class="user">
    <h4 class="user-header">Upload CSV file</h4>

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
    <form action="{{ route('posts.upload') }}" method="POST" class="container-form-post" enctype="multipart/form-data">
        @csrf
        <div class="form-group container-form-post">
            <label for="file" class="">CSV file</label> 
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary up-btn">Upload</button>
        <!-- Clear Button -->
        <button type="button" class="btn btn-secondary up-btn" onclick="clearFileInput()">Clear</button>
    </form>

</div>
</div>
<script>
    function clearFileInput() {
        $('#file').val('');
    }
</script>
@endsection

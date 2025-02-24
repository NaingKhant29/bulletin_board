@extends('layouts.app')

@section('content')
@vite(['resources/css/confirm.css'])

<div class="container">
    <div class="card">
    <h4 class="card-header">Upload CSV file</h4>

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
    <form action="{{ route('posts.upload') }}" method="POST" class= "form-pad" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <label for="file" class="col-md-4 col-form-label text-md-end">CSV file</label>
            <div class="col-md-6">
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
        </div>
    
        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Upload
                </button>
                <button type="button" class="btn btn-secondary" onclick="clearFileInput()">
                    Clear
                </button>
            </div>
        </div>
    </form>
    

</div>
</div>
<script>
    function clearFileInput() {
        $('#file').val('');
    }
</script>
@endsection

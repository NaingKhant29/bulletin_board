@extends('layouts.app')

@section('content')
    @vite(['resources/css/confirm.css'])

    <div class="container mt-4 ">  {{-- Added margin-top for spacing --}}
        <div class="card form-mg-top">
            <h4 class="card-header">Upload CSV File</h4>

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form id="csvUploadForm" action="{{ route('posts.upload') }}" method="POST" class="form-pad" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label for="file" class="col-md-4 col-form-label text-md-end">CSV File</label>
                    <div class="col-md-6">
                        <input type="file" name="file" id="file" class="form-control" required accept=".csv">
                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" id="uploadBtn" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-secondary" onclick="clearFileInput()">Clear</button>
                      
                            <a href="{{ asset('sample_csv.csv') }}" class="btn btn-info csv-format" download>
                                Download CSV Format
                            </a>
                            <small class="text-muted ms-2">* Max file size: 5MB</small>
                      
                        
                    </div>
                    
                </div>
                
            </form>
            
            <!-- Full-Screen Loading Overlay -->
            <div id="loadingOverlay">
                <div class="loading-content">
                    <div class="loadingio-spinner-bars-nq4q5u6dq7r">
                        <div class="ldio-x2uulkbinbj">
                            <div></div><div></div><div></div><div></div>
                        </div>
                    </div>
                    <p style="font-weight: bold;">Uploading... Please wait.</p>
                </div>
            </div>
            
            
        </div>
    </div>

    <script>
        document.getElementById('csvUploadForm').addEventListener('submit', function() {
            document.getElementById('uploadBtn').disabled = true; 
            document.getElementById('loadingOverlay').style.display = 'flex'; 
        });
    
        function clearFileInput() {
            document.getElementById('file').value = ''; 
        }
    </script>
    
@endsection

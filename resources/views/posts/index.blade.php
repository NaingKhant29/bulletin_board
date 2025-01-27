@extends('layouts.app')

@section('content')
<div class="container">
   <div class="user">
    <h3 class="user-header">Post List</h3>

    <form method="GET" action="{{ route('posts.index') }}" class="mb-4">
      <div class="row">
          <div class="col-md-4 label">
            <lable class="keyword">Keyword: </lable>
              <input type="text" name="search" class="form-control blank" placeholder="Search by keyword" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
          <div class="col-md-4 label">
            <a href="{{ route('posts.create') }}" class="btn btn-success upload-download">Create</a>
                <a href="#" class="btn btn-success upload-download">Upload</a>
                <a href="#" class="btn btn-success upload-download">Download</a>
        </div>
      </div>
  </form>
    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Post Title</th>
                <th>Post Description</th>
                <th>Posted User</th>
                <th>Posted Date</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->description }}</td>
                    <td>{{ $post->user->name ?? 'Unknown' }}</td>
                    <td>{{ $post->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="#" class="btn btn-warning">Edit</a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No posts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
   </div>
</div>
@endsection

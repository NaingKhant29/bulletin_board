@extends('layouts.app')


@section('content')
<div class="container">
   <div class="user">
      <h3 class="user-header">Post List</h3>

      <!-- Success Message -->
      @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
      @endif

      <!-- Search Form -->
      <form method="GET" action="{{ route('posts.index') }}" class="mb-4">
         <div class="row">
            <div class="col-md-4 label">
               <label class="keyword">Keyword: </label>
               <input type="text" name="search" class="search blank" placeholder="Search by keyword" value="{{ request('search') }}">
               <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-4 label">
               <a href="{{ route('posts.create') }}" class="btn btn-success upload-download">Create</a>
               <a href="{{ route('posts.upload') }}" class="btn btn-success upload-download">Upload</a>
               <a href="{{ route('posts.download') }}" class="btn btn-success upload-download">Download</a>
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
                  <td>
                     <a href="#" 
                        class="text-decoration-none" 
                        data-bs-toggle="modal" 
                        data-bs-target="#postDetailModal{{ $post->id }}">
                        {{ $post->title }}
                     </a>
                  </td>
                  <td>{{ $post->description }}</td>
                  <td>{{ $post->user->name ?? 'Unknown' }}</td>
                  <td>{{ $post->created_at->format('Y-m-d') }}</td>
                  <td>
                     <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
                     <!-- Delete Button -->
                     <a href="#" 
                        class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal{{ $post->id }}">
                        Delete
                     </a>
                  </td>
               </tr>
             
               <!-- Post Detail Modal -->
               <div class="modal fade" id="postDetailModal{{ $post->id }}" tabindex="-1" aria-labelledby="postDetailModalLabel{{ $post->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="postDetailModalLabel{{ $post->id }}">Post Details</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                           <p><strong>Title:</strong> {{ $post->title }}</p>
                           <p><strong>Description:</strong> {{ $post->description }}</p>
                           <p><strong>Status:</strong> {{ $post->status == 1 ? 'Active' : 'Inactive' }}</p>
                           <p><strong>Created Date:</strong> {{ $post->created_at->format('Y-m-d') }}</p>
                           <p><strong>Created By:</strong> {{ $post->user->name ?? 'Unknown' }}</p>
                           <p><strong>Updated Date:</strong> {{ $post->updated_at->format('Y-m-d') }}</p>
                           <p><strong>Updated By:</strong> {{ $post->updated_user->name ?? 'Unknown'}}</p>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Delete Confirmation Modal -->
               <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $post->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="deleteModalLabel{{ $post->id }}">Delete Confirm</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                           <p>Are you sure you want to delete this post?</p>
                           <p><strong>ID:</strong> {{ $post->id }}</p>
                           <p><strong>Title:</strong> {{ $post->title }}</p>
                           <p><strong>Description:</strong> {{ $post->description }}</p>
                           <p><strong>Status:</strong> {{ $post->status == 1 ? 'Active' : 'Inactive' }}</p>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                           <form id="deleteForm{{ $post->id }}" action="{{ route('posts.destroy', $post->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Delete</button>
                           </form>
                        </div>
                        
                     </div>
                  </div>
               </div>
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

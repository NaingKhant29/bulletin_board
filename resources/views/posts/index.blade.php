@extends('layouts.app')


@section('content')
    @vite(['resources/css/index.css'])
    <div class="container">


        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!-- Error Message (Bootstrap Alert) -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <!-- Search Form -->
        <form method="GET" action="{{ route('posts.index') }}" class="search-form mb-4">
            <div class="row align-items-center g-3">

                <!-- Filter by Date -->
                <div class="col-md-2">
                    <input type="date" name="created_at" class="form-control date-input"
                        value="{{ request('created_at') }}">
                </div>

                <!-- Search Input with Icon -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control search-input vh-6"
                            placeholder="Search by keyword" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary search-btn vh-6">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons (Create, Upload, Download) -->
                <div class="col-md-6 d-flex justify-content-end gap-2 action-buttons">
                    <a href="{{ route('posts.create') }}" class="btn btn-secondary">
                        <i class="bi bi-plus-circle"></i> Create
                    </a>
                    <a href="{{ route('posts.upload') }}" class="btn btn-secondary">
                        <i class="bi bi-upload"></i> Upload
                    </a>
                    <a href="{{ route('posts.download') }}" class="btn btn-secondary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>

            </div>
        </form>


        <div class="row justify-content-start">
            @forelse ($posts as $post)
                <div class="col-md-6">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center"
                            style="background-color:#184A45FF !important;">
                            <!-- Post Title -->
                            <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal"
                                data-bs-target="#postDetailModal{{ $post->id }}">
                                <!-- You can also apply hover effect for better UX -->
                                {{ $post->title }}
                            </a>
                            <p class="m-0 text-light" style="font-size: 0.7rem; margin-left: 350px !important;">
                                <i class="bi bi-calendar"></i> {{ $post->created_at->diffForHumans() }}
                            </p>
                            @if (Auth::check())
                                <div class="dropdown">
                                    <button class="btn btn-link btn-sm" type="button"
                                        id="dropdownMenuButton{{ $post->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical fs-4 three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end pos-dd"
                                        aria-labelledby="dropdownMenuButton{{ $post->id }}">
                                        <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}"> <i
                                                    class="bi bi-pencil me-2"></i> Edit</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('posts.downloadSingle', $post->id) }}">
                                                <i class="bi bi-download"></i> Download</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $post->id }}"><i
                                                    class="bi bi-trash"></i> Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Clickable card body that opens the Post Detail Modal -->
                        <div class="card-body" style="background-color: #643E46FF!important;">
                            <p class="card-text">{{ $post->description }}</p>
                            <hr>
                            


                            <div class="d-flex justify-content-between align-items-center" style="height: 30px;">
                                                               <div class="d-flex justify-content-around mt-2">
                                   
                            <p class="m-0 text-light" style="font-size: 0.9rem;">
                                <i class="bi bi-person-vcard"></i> {{ $post->user->name ?? 'Unknown' }}
                            </p>
                                    <button class="btn btn-outline-primary reaction-btn" data-post-id="{{ $post->id }}"
                                        data-type="like">
                                        👍 Like
                                        <span class="reaction-count" id="like-count-{{ $post->id }}">
                                            {{ $post->reactions->where('type', 'like')->count() }}
                                        </span>
                                    </button>

                                    <!-- Love Button -->
                                    <button class="btn btn-outline-danger reaction-btn" data-post-id="{{ $post->id }}"
                                        data-type="love">
                                        ❤️ Love
                                        <span class="reaction-count" id="love-count-{{ $post->id }}">
                                            {{ $post->reactions->where('type', 'love')->count() }}
                                        </span>
                                    </button>

                                    <!-- Haha Button -->
                                    <button class="btn btn-outline-warning reaction-btn" data-post-id="{{ $post->id }}"
                                        data-type="haha">
                                        😂 Haha
                                        <span class="reaction-count" id="haha-count-{{ $post->id }}">
                                            {{ $post->reactions->where('type', 'haha')->count() }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1"
                    aria-labelledby="deleteModalLabel{{ $post->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel{{ $post->id }}">Delete Confirm
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                <form id="deleteForm{{ $post->id }}"
                                    action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Post Detail Modal -->
                <div class="modal fade" id="postDetailModal{{ $post->id }}" tabindex="-1"
                    aria-labelledby="postDetailModalLabel{{ $post->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="postDetailModalLabel{{ $post->id }}">{{ $post->title }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Description:</strong> {{ $post->description }}</p>
                                <p><strong>Status:</strong> {{ $post->status == 1 ? 'Active' : 'Inactive' }}</p>
                                <p><strong>Created Date:</strong> {{ $post->created_at->format('Y-m-d H:i:s') }}</p>
                                <p><strong>Created By:</strong> {{ $post->user->name ?? 'Unknown' }}</p>
                                <p><strong>Updated Date:</strong> {{ $post->updated_at->format('Y-m-d') }}</p>
                                <p><strong>Updated By:</strong> {{ $post->updated_user->name ?? 'Unknown' }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-md-12 text-center">
                    <p>No data available.</p>
                </div>
            @endforelse
        </div>
        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center w-100">
            {{ $posts->links() }}
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".reaction-btn").click(function() {
                let postId = $(this).data("post-id");
                let type = $(this).data("type");

                $.ajax({
                    url: "{{ route('reactions.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        post_id: postId,
                        type: type
                    },
                    success: function(response) {
                        if (response.success) {
                            updateReactionCount(postId);
                        }
                    }
                });
            });

            function updateReactionCount(postId) {
                $.ajax({
                    url: `/reactions/${postId}`,
                    method: "GET",
                    success: function(reactions) {
                        let likeCount = reactions.filter(r => r.type === 'like').length;
                        let loveCount = reactions.filter(r => r.type === 'love').length;
                        let hahaCount = reactions.filter(r => r.type === 'haha').length;

                        $(`#like-count-${postId}`).text(likeCount);
                        $(`#love-count-${postId}`).text(loveCount);
                        $(`#haha-count-${postId}`).text(hahaCount);
                    }
                });
            }
        });
    </script>
@endsection

@extends('layouts.app')


@section('content')
    @vite(['resources/css/post/index.css'])
    <div id = "content">
        <div class="container">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form method="GET" action="{{ route('posts.index') }}" class="search-form mb-4">
                <div class="d-flex flex-row align-items-center gap-3 w-100">
                    <!-- Date Filter -->
                    <div class="flex-shrink-0" style="width: 18%;">
                        <input type="date" name="created_at" class="form-control date-input"
                            value="{{ request('created_at') }}">
                    </div>
                    <div class="d-flex align-items-center" style="width: 50%">
                        <div class="flex-shrink-0 width-dd">
                            <select name="category_id" id="myselect"
                                class="form-select category-id border border-secondary rounded-0">
                                <option value="">All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="texts" name="search"
                            class="search-by-key-wd form-control border border-secondary rounded-0"
                            placeholder="Search by keyword" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary border border-secondary rounded-0">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal"
                        style="width: 12%">
                        <i class="bi bi-list-ul"></i> <br>
                        <div class="hide-on-mobile">Category</div>
                    </button>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2" style="width: 28%">
                        <a href="{{ route('posts.create') }}" class="btn btn-secondary" style="width: 30%">
                            <i class="bi bi-plus-circle"></i> <br>
                            <div class="hide-on-mobile">Create</div>
                        </a>
                        <a href="{{ route('posts.upload') }}" class="btn btn-secondary" style="width: 30%">
                            <i class="bi bi-upload"></i> <br>
                            <div class="hide-on-mobile">Upload</div>
                        </a>
                        <a href="{{ route('posts.download') }}" class="btn btn-secondary" style="width: 30%">
                            <i class="bi bi-download"></i> <br>
                            <div class="hide-on-mobile">Download</div>
                        </a>
                    </div>
                </div>
            </form>

            <div class="posts-container">
                <div class="row justify-content-start">
                    @forelse ($posts as $index => $post)
                        @if ($index % 3 == 0 && $index != 0)
                </div>
                <div class="row justify-content-start">
                    @endif

                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-light mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center "
                                style="background-color:#f0f1f2!important; ">
                                <a href="#" class="text-decoration-none text-dark white-space-nowrap"
                                    data-bs-toggle="modal" data-bs-target="#postDetailModal{{ $post->id }}"
                                    style="width: 50%; display: inline-block;">
                                    {{ $post->title }}
                                </a>

                                <p class="m-0 text-light"
                                    style="font-size: 0.7rem; color: #323653 !important; width: 35%; text-align: right;">
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
                                                        class="bi bi-pencil me-2"></i> Edit</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('posts.downloadSingle', $post->id) }}"><i
                                                        class="bi bi-download"></i> Download</a></li>
                                            <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $post->id }}"><i
                                                        class="bi bi-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body" style="background-color: none !important;">
                                <p class="text-dark">{{ $post->description }}</p>
                            </div>

                            <div class="card-footer bg-transparent border-success p-3"
                                style="background-color:#f0f1f2!important; ">
                                <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                                    <div class="d-flex justify-content-between mt-2"
                                        style="width: 100%; align-items: center;">
                                        <div class="m-0 text-light"
                                            style="font-size: 0.8rem; display: flex; align-items: center; width: 100%;">
                                            <img src="{{ optional($post->user)->profile ? asset('storage/' . $post->user->profile) : 'https://via.placeholder.com/40' }}"
                                                alt="Profile" class="rounded-circle me-2" width="25" height="25"
                                                style="object-fit: cover;">
                                            <span class="username"
                                                style="white-space: nowrap; color: #323653; overflow: hidden; text-overflow: ellipsis;">
                                                {{ optional($post->user)->name ?? 'Unknown' }}
                                            </span>
                                        </div>

                                        <div class="d-flex reaction-container">
                                            @php
                                                $userReaction = $post->reactions->where('user_id', Auth::id())->first();
                                            @endphp

                                            @foreach (['like' => '👍', 'love' => '❤️', 'haha' => '😂'] as $type => $emoji)
                                                @php
                                                    $isReacted = $userReaction && $userReaction->type === $type;
                                                @endphp
                                                <button
                                                    class="btn reaction-btn me-2 {{ $isReacted ? 'btn-primary' : 'btn-outline-primary' }}"
                                                    style="font-size: 1rem; padding: 0.3rem 0.6rem; border-width: {{ $isReacted ? '3px' : '1px' }};"
                                                    data-post-id="{{ $post->id }}" data-type="{{ $type }}">
                                                    {{ $emoji }}
                                                    <span class="reaction-count"
                                                        id="{{ $type }}-count-{{ $post->id }}">
                                                        {{ $post->reactions->where('type', $type)->count() }}
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal fade" id="commentModal{{ $post->id }}" tabindex="-1"
                        aria-labelledby="commentModalLabel{{ $post->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentModalLabel{{ $post->id }}">Comments</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="comments-section mb-3">
                                        <h6 class="mb-3">Previous Comments</h6>
                                        <ul class="list-group" id="commentList{{ $post->id }}"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @if ($post->comments->count() > 0)
                                                @foreach ($post->comments as $comment)
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <!-- Profile Image -->
                                                        <img src="{{ $comment->user->profile ? asset('storage/' . $comment->user->profile) : 'https://via.placeholder.com/40' }}"
                                                            alt="Profile" class="rounded-circle me-2" width="40"
                                                            height="40" style="object-fit: cover;">

                                                        <div class="flex-grow-1">
                                                            <strong>{{ $comment->user->name }}:</strong>
                                                            {{ $comment->content }}
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                        </div>

                                                        @if (Auth::id() === $comment->user_id)
                                                            <form action="{{ route('comments.destroy', $comment->id) }}"
                                                                method="POST" class="d-inline"
                                                                style="box-shadow: none; background : #fff">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dlt-btn">🗑</button>
                                                            </form>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item text-muted text-center">No comments yet. Be the
                                                    first to comment!</li>
                                            @endif
                                        </ul>
                                    </div>

                                    <!-- Divider -->
                                    <hr class="my-3">

                                    <form id="commentForm{{ $post->id }}"
                                        action="{{ route('comments.store', $post->id) }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf

                                        <img src="{{ Auth::check() && Auth::user()->profile ? asset('storage/' . Auth::user()->profile) : 'https://static.vecteezy.com/system/resources/previews/045/711/150/non_2x/male-default-placeholder-avatar-profile-gray-picture-isolated-on-background-man-silhouette-with-beard-picture-for-social-media-forum-dating-site-chat-operator-free-vector.jpg' }}"
                                            alt="Profile" class="rounded-circle me-2" width="40" height="40"
                                            style="object-fit: cover;">

                                        <input type="text" name="content" class="form-control me-2"
                                            placeholder="Write your comment..." required>

                                        <button type="submit" class="btn btn-primary">💬</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for Category Actions -->
                    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="categoryModalLabel">Category Actions</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Create Category Form -->
                                    <form action="{{ route('categories.store') }}" method="POST"
                                        id="createCategoryForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="categoryName" class="form-label">Category Name</label>
                                            <input type="text" class="form-control" id="categoryName" name="name"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Create Category</button>
                                    </form>

                                    <hr>

                                    <!-- Delete Category Form -->
                                    <form action="{{ route('categories.delete') }}" method="POST"
                                        id="deleteCategoryForm">
                                        @csrf
                                        @method('DELETE')
                                        <div class="mb-3">
                                            <label for="deleteCategoryId" class="form-label">Select Category to
                                                Delete</label>
                                            <select name="category_id" id="deleteCategoryId" class="form-select">
                                                <option value="">Choose Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">Delete Category</button>
                                    </form>
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
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
                                <div class="modal-header" style="background-color: #40a3a7; color: #fff;">
                                    <h5 class="modal-title" id="postDetailModalLabel{{ $post->id }}">
                                        {{ $post->title }}
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
                                    <p><strong>Category:</strong> {{ $post->category->name ?? 'No Category' }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
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
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center w-100">
                {{ $posts->links() }}
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                $(".reaction-btn").click(function() {
                    let postId = $(this).data("post-id");
                    let type = $(this).data("type");
                    let button = $(this);

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
                                $(`#like-count-${postId}`).text(response.likeCount);
                                $(`#love-count-${postId}`).text(response.loveCount);
                                $(`#haha-count-${postId}`).text(response.hahaCount);

                                const selectedBtn = $(`button[data-post-id="${postId}"][data-type=${type}]`);
                                button.addClass("btn-primary");

                                const container = button.parent();

                                container.children().each(function (index, item) {
                                    if (button[0] !== item) {
                                        $(item).removeClass("btn-primary");
                                    }
                                });
                            }
                        }
                    });
                });




                function updateURL() {
                    let url = new URL(window.location.href);
                    url.searchParams.delete("page");

                    let categoryId = $("#myselect").val();
                    categoryId ? url.searchParams.set("category_id", categoryId) : url.searchParams.delete(
                        "category_id");

                    let searchQuery = $("input[name='search']").val();
                    searchQuery ? url.searchParams.set("search", searchQuery) : url.searchParams.delete("search");

                    let createdAt = $("input[name='created_at']").val();
                    createdAt ? url.searchParams.set("created_at", createdAt) : url.searchParams.delete("created_at");

                    window.location.href = url.toString();
                }

                $("#myselect").on("change", updateURL);
                let typingTimer;
                $("input[name='search']").on("keyup", function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(updateURL, 500);
                });
            });
        </script>

    </div>
@endsection

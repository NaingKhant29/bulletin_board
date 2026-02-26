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
            <form method="GET" action="{{ route('posts.index') }}"
                style="
    background:#ffffff;
    padding:18px 20px;
    border-radius:16px;
    box-shadow:0 8px 24px rgba(0,0,0,0.08);
    margin-bottom:24px;
">
                <div
                    style="
        display:flex;
        flex-wrap:wrap;
        gap:12px;
        align-items:center;
        width:100%;
    ">

                    <!-- Date -->
                    <div style="flex:0 0 180px;">
                        <input type="date" name="created_at" value="{{ request('created_at') }}"
                            style="
                width:100%;
                height:44px;
                padding:0 12px;
                border-radius:10px;
                border:1px solid #d0d5dd;
                outline:none;
                font-size:14px;
                background:#fff;
            ">
                    </div>

                    <!-- Category + Search Group -->
                    <div
                        style="
            display:flex;
            align-items:center;
            flex:1;
            min-width:260px;
            height:44px;
            border:1px solid #d0d5dd;
            border-radius:12px;
            overflow:hidden;
            background:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        ">

                        <!-- Category -->
                        <select name="category_id" id="myselect"
                            style="
                height:100%;
                border:none;
                outline:none;
                padding:0 14px;
                font-size:14px;
                background:#f8fafc;
                color:#323653;
                min-width:140px;
                cursor:pointer;
            ">
                            <option value="">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Divider -->
                        <div style="width:1px; height:60%; background:#e5e7eb;"></div>

                        <!-- Search Input -->
                        <input type="text" name="search" placeholder="Search by keyword" value="{{ request('search') }}"
                            style="
                flex:1;
                height:100%;
                border:none;
                outline:none;
                padding:0 14px;
                font-size:14px;
                color:#323653;
            ">

                        <!-- Search Button -->
                        <button type="submit"
                            style="
                width:48px;
                height:100%;
                border:none;
                background:#5f6f82;
                color:#fff;
                cursor:pointer;
                display:flex;
                align-items:center;
                justify-content:center;
            ">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Category Modal Button -->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#categoryModal"
                        style="
            height:44px;
            padding:0 16px;
            border-radius:10px;
            border:1px solid #d0d5dd;
            background:#f8fafc;
            cursor:pointer;
            font-size:13px;
            display:flex;
            align-items:center;
            gap:6px;
            color:#323653;
        ">
                        <i class="bi bi-list-ul"></i>
                        <span>Category</span>
                    </button>

                    <!-- Action Buttons -->
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">

                        <a href="{{ route('posts.create') }}"
                            style="
                height:44px;
                padding:0 18px;
                border-radius:10px;
                background:#5f6f82;
                color:#fff;
                text-decoration:none;
                display:flex;
                align-items:center;
                gap:6px;
                font-size:13px;
            ">
                            <i class="bi bi-plus-circle"></i> Create
                        </a>

                        <a href="{{ route('posts.upload') }}"
                            style="
                height:44px;
                padding:0 18px;
                border-radius:10px;
                background:#eef2f6;
                color:#323653;
                text-decoration:none;
                display:flex;
                align-items:center;
                gap:6px;
                font-size:13px;
                border:1px solid #d0d5dd;
            ">
                            <i class="bi bi-upload"></i> Upload
                        </a>

                        <a href="{{ route('posts.download') }}"
                            style="
                height:44px;
                padding:0 18px;
                border-radius:10px;
                background:#eef2f6;
                color:#323653;
                text-decoration:none;
                display:flex;
                align-items:center;
                gap:6px;
                font-size:13px;
                border:1px solid #d0d5dd;
            ">
                            <i class="bi bi-download"></i> Download
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

                            <div class="card-footer bg-transparent border-success"
                                style="background-color:#f0f1f2!important; ">
                                <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                                    <div class="d-flex justify-content-between mt-2"
                                        style="width: 100%; align-items: center;">
                                        <div class="m-0 text-light"
                                            style="font-size: 0.8rem; display: flex; align-items: center; width: 30%;">
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
                                            <div class="comment-wrapper d-inline-block position-relative"
                                                data-post-id="{{ $post->id }}">
                                                <button class="btn comment-btn" data-bs-toggle="modal"
                                                    data-bs-target="#commentModal{{ $post->id }}"
                                                    data-post-id="{{ $post->id }}" style="border: 1px solid #bbb;">
                                                    💬
                                                </button>
                                                <span class="comment-hover-count position-absolute"
                                                    id="comment-count-{{ $post->id }}">
                                                    {{ $post->comments->count() }}
                                                </span>
                                            </div>


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
                    <!-- Premium Category Modal -->
                    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content"
                                style="
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 12px 32px rgba(0,0,0,0.15);
            border:none;
        ">

                                <!-- Header -->
                                <div class="modal-header"
                                    style="
                background:#5f6f82;
                color:#fff;
                padding:16px 20px;
                border-bottom:none;
            ">
                                    <h5 class="modal-title" id="categoryModalLabel" style="margin:0; font-weight:600;">
                                        Category Management
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body" style="padding:20px; background:#f8fafc;">

                                    <!-- Create Category -->
                                    <div
                                        style="
                    background:#ffffff;
                    border-radius:12px;
                    padding:16px;
                    box-shadow:0 4px 12px rgba(0,0,0,0.06);
                    margin-bottom:20px;
                ">
                                        <h6 style="margin-bottom:12px; color:#323653; font-weight:600;">Create Category
                                        </h6>

                                        <form action="{{ route('categories.store') }}" method="POST"
                                            id="createCategoryForm">
                                            @csrf
                                            <div style="margin-bottom:12px;">
                                                <label for="categoryName"
                                                    style="
                                display:block;
                                font-size:13px;
                                margin-bottom:6px;
                                color:#5f6f82;
                            ">Category
                                                    Name</label>
                                                <input type="text" id="categoryName" name="name" required
                                                    style="
                                width:100%;
                                height:42px;
                                padding:0 12px;
                                border-radius:10px;
                                border:1px solid #d0d5dd;
                                outline:none;
                                font-size:14px;
                            ">
                                            </div>

                                            <button type="submit"
                                                style="
                            width:100%;
                            height:42px;
                            border-radius:10px;
                            border:none;
                            background:#5f6f82;
                            color:#fff;
                            font-size:14px;
                            cursor:pointer;
                        ">
                                                + Create Category
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Delete Category -->
                                    <div
                                        style="
                    background:#ffffff;
                    border-radius:12px;
                    padding:16px;
                    box-shadow:0 4px 12px rgba(0,0,0,0.06);
                ">
                                        <h6 style="margin-bottom:12px; color:#b42318; font-weight:600;">Delete Category
                                        </h6>

                                        <form action="{{ route('categories.delete') }}" method="POST"
                                            id="deleteCategoryForm">
                                            @csrf
                                            @method('DELETE')

                                            <div style="margin-bottom:12px;">
                                                <label for="deleteCategoryId"
                                                    style="
                                display:block;
                                font-size:13px;
                                margin-bottom:6px;
                                color:#5f6f82;
                            ">Select
                                                    Category</label>

                                                <select name="category_id" id="deleteCategoryId"
                                                    style="
                                width:100%;
                                height:42px;
                                padding:0 12px;
                                border-radius:10px;
                                border:1px solid #d0d5dd;
                                outline:none;
                                font-size:14px;
                                background:#fff;
                            ">
                                                    <option value="">Choose Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit"
                                                style="
                            width:100%;
                            height:42px;
                            border-radius:10px;
                            border:none;
                            background:#d92d20;
                            color:#fff;
                            font-size:14px;
                            cursor:pointer;
                        ">
                                                🗑 Delete Category
                                            </button>
                                        </form>
                                    </div>

                                </div>

                                <!-- Footer -->
                                <div class="modal-footer"
                                    style="
                border-top:none;
                padding:12px 20px;
                background:#f8fafc;
            ">
                                    <button type="button" data-bs-dismiss="modal"
                                        style="
                    height:38px;
                    padding:0 16px;
                    border-radius:10px;
                    border:1px solid #d0d5dd;
                    background:#fff;
                    color:#323653;
                    cursor:pointer;
                ">
                                        Close
                                    </button>
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

                    <!-- Post Detail Modal (Premium Neutral) -->
                    <div class="modal fade" id="postDetailModal{{ $post->id }}" tabindex="-1"
                        aria-labelledby="postDetailModalLabel{{ $post->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content"
                                style="
            border:none;
            border-radius:16px;
            overflow:hidden;
            background:#ffffff;
            box-shadow:0 24px 60px rgba(0,0,0,0.25);
        ">

                                <!-- Header -->
                                <div class="modal-header"
                                    style="
                background:#f8fafc;
                border-bottom:1px solid #e5e7eb;
                padding:18px 24px;
            ">
                                    <div style="display:flex; flex-direction:column;">
                                        <h5 class="modal-title mb-1" id="postDetailModalLabel{{ $post->id }}"
                                            style="
                        font-weight:600;
                        color:#0f172a;
                        display:flex;
                        align-items:center;
                        gap:8px;
                    ">
                                            <i class="bi bi-file-text"></i>
                                            {{ $post->title }}
                                        </h5>
                                        <small style="color:#64748b;">Post Details</small>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body" style="background:#f9fafb; padding:24px;">

                                    <div
                                        style="
                    background:#ffffff;
                    border-radius:12px;
                    padding:20px 22px;
                    box-shadow:0 10px 24px rgba(0,0,0,0.06);
                ">

                                        <!-- Row -->
                                        <div
                                            style="display:flex; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-card-text"></i> Description
                                            </div>
                                            <div style="flex:1; color:#0f172a;">
                                                {{ $post->description }}
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div
                                            style="display:flex; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-toggle-on"></i> Status
                                            </div>
                                            <div style="flex:1;">
                                                @if ($post->status == 1)
                                                    <span
                                                        style="
                                    display:inline-block;
                                    padding:4px 10px;
                                    border-radius:999px;
                                    background:#ecfdf5;
                                    color:#065f46;
                                    font-size:12px;
                                    font-weight:500;
                                ">Active</span>
                                                @else
                                                    <span
                                                        style="
                                    display:inline-block;
                                    padding:4px 10px;
                                    border-radius:999px;
                                    background:#f1f5f9;
                                    color:#334155;
                                    font-size:12px;
                                    font-weight:500;
                                ">Inactive</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div
                                            style="display:flex; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-calendar-plus"></i> Created Date
                                            </div>
                                            <div style="flex:1; color:#0f172a;">
                                                {{ $post->created_at->format('Y-m-d H:i:s') }}
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div
                                            style="display:flex; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-person"></i> Created By
                                            </div>
                                            <div style="flex:1; color:#0f172a;">
                                                {{ $post->user->name ?? 'Unknown' }}
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div
                                            style="display:flex; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-calendar-check"></i> Updated Date
                                            </div>
                                            <div style="flex:1; color:#0f172a;">
                                                {{ $post->updated_at->format('Y-m-d') }}
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div
                                            style="display:flex; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-person-check"></i> Updated By
                                            </div>
                                            <div style="flex:1; color:#0f172a;">
                                                {{ $post->updated_user->name ?? 'Unknown' }}
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div style="display:flex; gap:16px; padding:10px 0;">
                                            <div
                                                style="width:180px; color:#64748b; display:flex; align-items:center; gap:8px;">
                                                <i class="bi bi-tags"></i> Category
                                            </div>
                                            <div style="flex:1; color:#0f172a;">
                                                {{ $post->category->name ?? 'No Category' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer"
                                    style="
                background:#f8fafc;
                border-top:1px solid #e5e7eb;
                padding:16px 24px;
            ">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i> Close
                                    </button>
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
                $(".comment-btn").hover(function() {
                    let postId = $(this).data("post-id");
                    let commentCount = $(`#comment-count-${postId}`).text().trim();

                    // Append count inside button
                    $(this).html(`💬 ${commentCount}`);
                }, function() {
                    // Remove count when hover ends
                    $(this).html("💬");
                });
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

                                const selectedBtn = $(
                                    `button[data-post-id="${postId}"][data-type=${type}]`);
                                button.addClass("btn-primary");

                                const container = button.parent();

                                container.children().each(function(index, item) {
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

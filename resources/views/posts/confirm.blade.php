@extends('layouts.app')

@section('content')
@vite(['resources/css/confirm.css'])

<div class="container" style="max-width:900px; margin-top:40px; margin-bottom:60px;">

    <div class="card" style="
        border:none;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 20px 40px rgba(0,0,0,0.10);
        background:#ffffff;
    ">

        <!-- Header -->
        <div style="
            padding:18px 24px;
            background:#f8fafc;
            border-bottom:1px solid #e5e7eb;
            display:flex;
            align-items:center;
            justify-content:space-between;
        ">
            <div style="display:flex; flex-direction:column;">
                <span style="font-size:18px; font-weight:600; color:#323653;">
                    Confirm Create Post
                </span>
                <span style="font-size:12px; color:#6b7280;">
                    Please review the information before submitting
                </span>
            </div>
            <div style="
                width:10px;
                height:10px;
                border-radius:50%;
                background:#5f6f82;
            "></div>
        </div>

        <!-- Body -->
        <div style="padding:28px; background:#f9fafb;">

            <!-- Info Box -->
            <div style="
                margin-bottom:24px;
                padding:16px 18px;
                border-radius:14px;
                background:#eef2ff;
                border:1px solid #c7d2fe;
                color:#1e3a8a;
                box-shadow:0 6px 16px rgba(0,0,0,0.05);
                display:flex;
                align-items:center;
                gap:10px;
                font-size:14px;
                font-weight:500;
            ">
                <span style="
                    display:inline-flex;
                    width:22px;
                    height:22px;
                    border-radius:50%;
                    background:#4f46e5;
                    color:#fff;
                    align-items:center;
                    justify-content:center;
                    font-size:14px;
                    line-height:1;
                ">i</span>
                Are you sure you want to create this post?
            </div>

            <!-- Preview Fields -->
            <div style="
                background:#ffffff;
                border-radius:14px;
                padding:20px;
                box-shadow:0 8px 20px rgba(0,0,0,0.06);
                margin-bottom:24px;
            ">

                <!-- Title -->
                <div style="margin-bottom:16px; display:flex; gap:20px;">
                    <div style="width:120px; font-weight:600; color:#374151;">Title</div>
                    <div style="flex:1; color:#111827;">{{ $title }}</div>
                </div>

                <!-- Description -->
                <div style="margin-bottom:16px; display:flex; gap:20px;">
                    <div style="width:120px; font-weight:600; color:#374151;">Description</div>
                    <div style="flex:1; color:#111827; white-space:pre-wrap;">{{ $description }}</div>
                </div>

                <!-- Category -->
                <div style="margin-bottom:0; display:flex; gap:20px;">
                    <div style="width:120px; font-weight:600; color:#374151;">Category</div>
                    <div style="flex:1; color:#111827;">{{ $category->name }}</div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="title" value="{{ $title }}">
                <input type="hidden" name="description" value="{{ $description }}">
                <input type="hidden" name="category_id" value="{{ $category_id }}">

                <!-- Actions -->
                <div style="display:flex; gap:12px; justify-content:flex-end;">

                    <button type="submit" style="
                        height:44px;
                        padding:0 24px;
                        border-radius:12px;
                        border:none;
                        background:#4f46e5;
                        color:#fff;
                        font-size:14px;
                        font-weight:500;
                        cursor:pointer;
                        box-shadow:0 6px 14px rgba(79,70,229,0.35);
                    ">
                        Confirm
                    </button>

                    <a href="{{ route('posts.create', [
                        'title' => $title,
                        'description' => $description,
                        'category_id' => $category_id
                    ]) }}" style="
                        height:44px;
                        padding:0 24px;
                        border-radius:12px;
                        border:1px solid #d1d5db;
                        background:#ffffff;
                        color:#374151;
                        font-size:14px;
                        font-weight:500;
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        text-decoration:none;
                        cursor:pointer;
                    ">
                        Cancel
                    </a>

                </div>
            </form>

        </div>
    </div>
</div>
@endsection

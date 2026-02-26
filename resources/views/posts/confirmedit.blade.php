@extends('layouts.app')

@section('content')
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
            <div>
                <div style="font-size:18px; font-weight:600; color:#323653;">
                    Confirm Edit
                </div>
                <div style="font-size:12px; color:#6b7280;">
                    Please review the information before confirming
                </div>
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

            <!-- Info Panel -->
            <div style="
                background:#ffffff;
                border-radius:14px;
                padding:20px;
                box-shadow:0 6px 18px rgba(0,0,0,0.06);
                margin-bottom:24px;
            ">

                <!-- Row: Title -->
                <div style="display:flex; gap:20px; margin-bottom:14px;">
                    <div style="width:140px; font-size:13px; color:#6b7280;">Title</div>
                    <div style="flex:1; font-size:14px; color:#323653; font-weight:500;">
                        {{ $title }}
                    </div>
                </div>

                <!-- Row: Description -->
                <div style="display:flex; gap:20px; margin-bottom:14px;">
                    <div style="width:140px; font-size:13px; color:#6b7280;">Description</div>
                    <div style="flex:1; font-size:14px; color:#323653;">
                        {{ $description }}
                    </div>
                </div>

                <!-- Row: Category -->
                <div style="display:flex; gap:20px; margin-bottom:14px;">
                    <div style="width:140px; font-size:13px; color:#6b7280;">Category</div>
                    <div style="flex:1; font-size:14px; color:#323653; font-weight:500;">
                        {{ $category }}
                    </div>
                </div>

                <!-- Row: Status -->
                <div style="display:flex; gap:20px;">
                    <div style="width:140px; font-size:13px; color:#6b7280;">Status</div>
                    <div style="flex:1;">
                        @if ($status == 1)
                            <span style="
                                display:inline-block;
                                padding:4px 10px;
                                border-radius:999px;
                                background:#e6f4ea;
                                color:#1e7f43;
                                font-size:12px;
                                font-weight:600;
                            ">
                                Active
                            </span>
                        @else
                            <span style="
                                display:inline-block;
                                padding:4px 10px;
                                border-radius:999px;
                                background:#fdecea;
                                color:#b42318;
                                font-size:12px;
                                font-weight:600;
                            ">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Actions -->
            <div style="display:flex; justify-content:flex-end; gap:12px;">

                <form action="{{ route('posts.update', $post->id) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <input type="hidden" name="category_id" value="{{ $category_id }}">
                    <input type="hidden" name="status" value="{{ $status }}">

                    <button type="submit" style="
                        height:44px;
                        padding:0 22px;
                        border-radius:12px;
                        border:none;
                        background:#5f6f82;
                        color:#ffffff;
                        font-size:14px;
                        font-weight:500;
                        cursor:pointer;
                    ">
                        Confirm
                    </button>
                </form>

                <a href="{{ route('posts.edit', $post->id) }}" style="
                    height:44px;
                    padding:0 22px;
                    border-radius:12px;
                    border:1px solid #d1d5db;
                    background:#ffffff;
                    color:#323653;
                    font-size:14px;
                    text-decoration:none;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">
                    Cancel
                </a>

            </div>

        </div>
    </div>
</div>
@endsection

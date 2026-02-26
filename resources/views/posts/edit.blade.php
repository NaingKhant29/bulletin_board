@extends('layouts.app')

@section('content')
@vite(['resources/css/confirm.css'])
@vite(['resources/css/post/editpost.css'])

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
                    Edit Post
                </span>
                <span style="font-size:12px; color:#6b7280;">
                    Update post information
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
        <div style="padding:28px; background:#f9fafb; box-sizing:border-box; max-width:100%;">

            {{-- Success Alert --}}
            @if (session('success'))
                <div style="
                    margin-bottom:20px;
                    padding:14px 16px;
                    border-radius:14px;
                    background:#ecfdf5;
                    border:1px solid #a7f3d0;
                    color:#065f46;
                    box-shadow:0 4px 12px rgba(0,0,0,0.05);
                ">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Summary --}}
            @if ($errors->any())
                <div style="
                    margin-bottom:20px;
                    padding:16px 18px;
                    border-radius:14px;
                    background:#fff1f2;
                    border:1px solid #fecdd3;
                    color:#9f1239;
                    box-shadow:0 6px 16px rgba(0,0,0,0.06);
                    box-sizing:border-box;
                    max-width:100%;
                ">
                    <div style="
                        display:flex;
                        align-items:center;
                        gap:10px;
                        font-weight:600;
                        margin-bottom:10px;
                        font-size:14px;
                    ">
                        <span style="
                            display:inline-flex;
                            width:22px;
                            min-width:22px;
                            height:22px;
                            border-radius:50%;
                            background:#e11d48;
                            color:#fff;
                            align-items:center;
                            justify-content:center;
                            font-size:14px;
                            line-height:1;
                        ">!</span>
                        Please fix the following errors
                    </div>
                    <ul style="margin:0; padding-left:20px; font-size:13px;">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom:6px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('posts.confirmedit', $post->id) }}" method="GET">

                <!-- Title -->
                <div style="margin-bottom:18px;">
                    <label for="title" style="display:block; font-size:13px; margin-bottom:6px; color:#5f6f82; font-weight:500;">
                        Title <span style="color:#d92d20">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                        value="{{ old('title', $post->title) }}"
                        class="form-control"
                        style="
                            width:100%;
                            height:46px;
                            border-radius:12px;
                            border:1px solid #d1d5db;
                            padding:0 14px;
                            font-size:14px;
                            background:#ffffff;
                        ">
                    @error('title')
                        <div style="margin-top:6px; font-size:12px; color:#b91c1c;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div style="margin-bottom:18px;">
                    <label for="description" style="display:block; font-size:13px; margin-bottom:6px; color:#5f6f82; font-weight:500;">
                        Description <span style="color:#d92d20">*</span>
                    </label>
                    <textarea name="description" id="description"
                        class="form-control"
                        style="
                            width:100%;
                            min-height:120px;
                            border-radius:12px;
                            border:1px solid #d1d5db;
                            padding:12px 14px;
                            font-size:14px;
                            background:#ffffff;
                        ">{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <div style="margin-top:6px; font-size:12px; color:#b91c1c;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category -->
                <div style="margin-bottom:18px;">
                    <label for="category_id" style="display:block; font-size:13px; margin-bottom:6px; color:#5f6f82; font-weight:500;">
                        Category <span style="color:#d92d20">*</span>
                    </label>
                    <select name="category_id" id="category_id"
                        class="form-control"
                        style="
                            width:100%;
                            height:46px;
                            border-radius:12px;
                            border:1px solid #d1d5db;
                            padding:0 14px;
                            font-size:14px;
                            background:#ffffff;
                        ">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div style="margin-top:6px; font-size:12px; color:#b91c1c;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div style="margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                    <label for="status" style="font-size:13px; color:#5f6f82; font-weight:500;">
                        Status
                    </label>
                    <input type="checkbox" name="status" id="status"
                        style="width:18px; height:18px;"
                        {{ old('status', $post->status) == 1 ? 'checked' : '' }}>
                    <span style="font-size:13px; color:#374151;">Active</span>
                </div>

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
                        Update
                    </button>

                    <button type="button" onclick="clearForm()" style="
                        height:44px;
                        padding:0 24px;
                        border-radius:12px;
                        border:1px solid #d1d5db;
                        background:#ffffff;
                        color:#374151;
                        font-size:14px;
                        font-weight:500;
                        cursor:pointer;
                    ">
                        Clear
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script>
    function clearForm() {
        document.getElementById('title').value = '';
        document.getElementById('description').value = '';
        document.getElementById('category_id').selectedIndex = 0;
        document.getElementById('status').checked = false;
    }
</script>
@endsection

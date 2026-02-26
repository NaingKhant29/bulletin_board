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
            <div style="display:flex; flex-direction:column;">
                <span style="font-size:18px; font-weight:600; color:#323653;">
                    Create Post
                </span>
                <span style="font-size:12px; color:#6b7280;">
                    Add a new post to bulletin board
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
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Premium Error Summary (No overflow) --}}
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
                    overflow:hidden;
                ">
                    <div style="
                        display:flex;
                        align-items:center;
                        gap:10px;
                        font-weight:600;
                        margin-bottom:10px;
                        font-size:14px;
                        word-break:break-word;
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
                    <ul style="
                        margin:0;
                        padding-left:20px;
                        font-size:13px;
                        max-width:100%;
                        word-break:break-word;
                    ">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom:6px; word-break:break-word;">
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('posts.confirm') }}" method="POST">
                @csrf

                <!-- Title -->
                <div style="margin-bottom:18px; max-width:100%; box-sizing:border-box;">
                    <label for="title" style="
                        display:block;
                        font-size:13px;
                        margin-bottom:6px;
                        color:#5f6f82;
                        font-weight:500;
                    ">
                        Title <span style="color:#d92d20">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                        value="{{ old('title', request('title')) }}"
                        class="form-control"
                        style="
                            width:100%;
                            height:46px;
                            border-radius:12px;
                            border:1px solid #d1d5db;
                            padding:0 14px;
                            font-size:14px;
                            background:#ffffff;
                            box-sizing:border-box;
                        ">

                    @error('title')
                        <div style="
                            margin-top:6px;
                            font-size:12px;
                            color:#b91c1c;
                            display:flex;
                            align-items:flex-start;
                            gap:6px;
                            max-width:100%;
                            word-break:break-word;
                        ">
                            <span style="
                                display:inline-flex;
                                width:16px;
                                min-width:16px;
                                height:16px;
                                border-radius:50%;
                                background:#e11d48;
                                color:#fff;
                                align-items:center;
                                justify-content:center;
                                font-size:11px;
                                line-height:1;
                                margin-top:2px;
                            ">!</span>
                            <span style="word-break:break-word;">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Description -->
                <div style="margin-bottom:18px; max-width:100%; box-sizing:border-box;">
                    <label for="description" style="
                        display:block;
                        font-size:13px;
                        margin-bottom:6px;
                        color:#5f6f82;
                        font-weight:500;
                    ">
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
                            box-sizing:border-box;
                        ">{{ old('description', request('description')) }}</textarea>

                    @error('description')
                        <div style="
                            margin-top:6px;
                            font-size:12px;
                            color:#b91c1c;
                            display:flex;
                            align-items:flex-start;
                            gap:6px;
                            max-width:100%;
                            word-break:break-word;
                        ">
                            <span style="
                                display:inline-flex;
                                width:16px;
                                min-width:16px;
                                height:16px;
                                border-radius:50%;
                                background:#e11d48;
                                color:#fff;
                                align-items:center;
                                justify-content:center;
                                font-size:11px;
                                line-height:1;
                                margin-top:2px;
                            ">!</span>
                            <span style="word-break:break-word;">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Category -->
                <div style="margin-bottom:24px; max-width:100%; box-sizing:border-box;">
                    <label for="category_id" style="
                        display:block;
                        font-size:13px;
                        margin-bottom:6px;
                        color:#5f6f82;
                        font-weight:500;
                    ">
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
                            box-sizing:border-box;
                        ">
                        <option value="" disabled selected>Select a Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <div style="
                            margin-top:6px;
                            font-size:12px;
                            color:#b91c1c;
                            display:flex;
                            align-items:flex-start;
                            gap:6px;
                            max-width:100%;
                            word-break:break-word;
                        ">
                            <span style="
                                display:inline-flex;
                                width:16px;
                                min-width:16px;
                                height:16px;
                                border-radius:50%;
                                background:#e11d48;
                                color:#fff;
                                align-items:center;
                                justify-content:center;
                                font-size:11px;
                                line-height:1;
                                margin-top:2px;
                            ">!</span>
                            <span style="word-break:break-word;">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Actions -->
                <div style="display:flex; gap:12px; justify-content:flex-end; max-width:100%;">

                    <button type="submit" style="
                        height:44px;
                        padding:0 22px;
                        border-radius:12px;
                        border:none;
                        background:#5f6f82;
                        color:#fff;
                        font-size:14px;
                        font-weight:500;
                        cursor:pointer;
                    ">
                        Create
                    </button>

                    <button type="button" id="clear-btn" style="
                        height:44px;
                        padding:0 22px;
                        border-radius:12px;
                        border:1px solid #d1d5db;
                        background:#ffffff;
                        color:#323653;
                        font-size:14px;
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
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('clear-btn').addEventListener('click', function () {
            document.getElementById('title').value = '';
            document.getElementById('description').value = '';
            document.getElementById('category_id').selectedIndex = 0;
        });
    });
</script>
@endsection

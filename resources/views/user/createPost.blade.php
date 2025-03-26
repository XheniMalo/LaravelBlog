@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
@can('manage-post')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Create New Post</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter post title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Content</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="Write your post here..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Upload Images</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">You can upload multiple images.</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('posts.store') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-success">Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

@extends('layouts.app')
@section('title', $post->title)
@section('content')
    <div class="container mt-4">
        <div class="card shadow p-4">
            <h2 class="fw-bold text-center">{{ $post->title }}</h2>
            <p class="text-muted text-center">Posted on {{ $post->created_at->format('M d, Y') }}</p>

            @if($post->images->isNotEmpty())
                <div class="row">
                    @foreach($post->images as $image)
                        <div class="col-md-4 mb-3 position-relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded shadow w-100"
                                alt="Post Image">
                            <form action="{{ route('images.destroy', $image->id) }}" method="POST"
                                class="position-absolute top-0 start-100 translate-middle">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 border-0">
                                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('posts.update', $post->post_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" class="form-control" rows="5">{{ $post->content }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Add Images</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-success">Update Post</button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-0.7">Back to Posts</a>
                </div>

            </form>

            <div class="comments-section mt-4">
            <h4>Comments ({{ $post->comments->count() }})</h4>
                <div class="comments-list">
                    @include('comments.comments_list', ['comments' => $post->topLevelComments])
                </div>
            </div>


        </div>
    </div>
@endsection
@extends('layouts.app')
@section('title', $post->title)
@section('content')

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="fw-bold">{{ $post->title }}</h2>
                <small class="text-muted">By {{ $post->user->name }}</small>
                <hr>

                @if($post->images->isNotEmpty())
                    <div class="row">
                        @foreach($post->images as $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded" alt="Post Image">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No images available.</p>
                @endif

                <p class="mt-3">{{ $post->content }}</p>

                <div class="d-flex align-items-center mt-3 mb-3">
                    <form action="{{ route('posts.like', $post->post_id) }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit"
                            class="btn {{ $post->isLikedByUser() ? 'btn-danger' : 'btn-outline-danger' }}">
                            <i class="fas fa-heart"></i>
                            {{ $post->isLikedByUser() ? 'Unlike' : 'Like' }}
                        </button>
                    </form>
                    <span>{{ $post->likesCount() }} likes</span>
                </div>

                <div class="card shadow mt-4">
                    <div class="card-header bg-light">
                        <h4>Comments ({{ $post->comments->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                            <div class="mb-3">
                                <textarea class="form-control" name="body" rows="3" placeholder="Write a comment..."
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Comment</button>
                        </form>

                        <div class="comments-list mt-4">
                            @include('comments.comments_list', ['comments' => $post->comments])
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to homepage
                </a>
            </div>
        </div>
    </div>
@endsection
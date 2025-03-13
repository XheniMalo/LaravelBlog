@extends('layouts.app')
@section('title', 'My Posts')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">My Posts</h2>
        <div class="row">

            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100 d-flex flex-column">
                        @if($post->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" class="d-block w-100 rounded"
                                alt="Post Image" style="height: 200px; object-fit: cover;">

                        @endif
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $post->title }}</h5>
                            <p class="text-muted">{{ Str::limit($post->content, 100) }}</p>
                            <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-sm btn-primary">View Post</a>
                            <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="d-inline"
                                onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this post?");
        }
    </script>
@endsection
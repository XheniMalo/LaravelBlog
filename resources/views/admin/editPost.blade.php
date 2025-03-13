@extends('adminlte::page')
@section('title', 'User Posts - ATLAS Blog')

@section('content_header')
<h1>All Posts</h1>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Post</h3>
                </div>
                <div class="card-body">
                    <h2 class="fw-bold text-center">{{ $post->title }}</h2>
                    <p class="text-muted text-center">Posted on {{ $post->created_at->format('M d, Y') }}</p>

                    @if($post->images->isNotEmpty())
                        <div class="row">
                            @foreach($post->images as $image)
                                <div class="col-md-4 mb-3 position-relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="img-fluid rounded shadow w-100" alt="Post Image">
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

                    <form action="{{ route('post.update', $post->post_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" id="content" class="form-control"
                                rows="5">{{ $post->content }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Add Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple>
                        </div>
                        <div class="card-body">
                            <button type="submit" class="btn btn-success">Update Post</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
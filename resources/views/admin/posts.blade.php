@extends('adminlte::page')
@section('title', 'User Posts - ATLAS Blog')

@section('content_header')
<h1>All Posts</h1>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="toast align-items-center text-white bg-success border-0" id="successToast" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <span id="toastMessage"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">All Posts</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ Str::limit($post->content, 100) }}</td>
                                    <td>@if ($post->user)
                                        {{ $post->user->name }}
                                    @else
                                        <em>No user assigned</em>
                                    @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('post.edit', $post->post_id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#likesModal-{{ $post->post_id }}">
                                            <i class="fas fa-heart"></i> Show likes
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#commentsModal-{{ $post->post_id }}">
                                            <i class="fas fa-comment"></i> Show comments
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $post->post_id }}"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="likesModal-{{ $post->post_id }}" tabindex="-1" aria-labelledby="likesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Users who liked this post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        @foreach ($post->likes as $like)
                            <li>{{ $like->user ? $like->user->name : 'Unknown User' }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commentsModal-{{ $post->post_id }}" tabindex="-1" aria-labelledby="commentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        @foreach ($post->comments as $comment)
                            <li><strong>{{ $comment->user ? $comment->user->name : 'Unknown User' }}:</strong>
                                {{ $comment->content }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('.showLikesBtn').click(function () {
            let postId = $(this).data('id');

            $.ajax({
                url: `/admin/post/${postId}/likes`,
                type: 'GET',
                success: function (response) {
                    let likesList = $('#likesList');
                    likesList.empty();

                    if (response.likes.length > 0) {
                        response.likes.forEach(like => {
                            likesList.append(`<li>${like.user ? like.user.name : 'Unknown User'}</li>`);
                        });
                    } else {
                        likesList.append('<li>No likes yet.</li>');
                    }

                    $('#likesModal').modal('show');
                },
                error: function () {
                    alert('Error fetching likes.');
                }
            });
        });

        $('.showCommentsBtn').click(function () {
            let postId = $(this).data('id');

            $.ajax({
                url: `/admin/post/${postId}/comments`,
                type: 'GET',
                success: function (response) {
                    let commentsList = $('#commentsList');
                    commentsList.empty();

                    if (response.comments.length > 0) {
                        response.comments.forEach(comment => {
                            commentsList.append(`<li><strong>${comment.user ? comment.user.name : 'Unknown User'}:</strong> ${comment.content}</li>`);
                        });
                    } else {
                        commentsList.append('<li>No comments yet.</li>');
                    }

                    $('#commentsModal').modal('show');
                },
                error: function () {
                    alert('Error fetching comments.');
                }
            });
        });
    });
</script>
@stop
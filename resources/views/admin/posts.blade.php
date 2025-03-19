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
                                    <td>
                                        @if ($post->user)
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
                                            <i class="fas fa-heart"></i> Likes
                                            <span
                                                class="badge bg-light text-dark">{{ $post->likes->count() }}</span>
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#commentsModal-{{ $post->post_id }}">
                                            <i class="fas fa-comment"></i> Comments
                                            <span
                                                class="badge bg-light text-dark">{{ $post->comments->count() }}</span>
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $post->post_id }}"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="likesModal-{{ $post->post_id }}" tabindex="-1"
                                    aria-labelledby="likesModalLabel-{{ $post->post_id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Users who liked "{{ $post->title }}"
                                                    ({{ $post->likes->count() }})</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($post->likes->count() > 0)
                                                    <ul class="list-group">
                                                        @foreach ($post->likes as $index => $user)
                                                            <li class="list-group-item d-flex">
                                                                <span
                                                                    class="badge bg-primary">{{ $index + 1 }}</span>
                                                                {{ $user->name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p>No likes yet.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @include('admin.comments', ['post' => $post])
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
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        let postIdToDelete;
        let commentIdToDelete;
        let replyIdToDelete;
        let postIdOfComment;
        let parentCommentId;

        $('.deleteBtn').click(function () {
            postIdToDelete = $(this).data('id');
        });

        $('#confirmDelete').click(function () {
            $.ajax({
                url: `/admin/post/${postIdToDelete}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    $('#toastMessage').text('Post deleted successfully!');
                    const toast = new bootstrap.Toast(document.getElementById('successToast'));
                    toast.show();

                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                },
                error: function () {
                    alert('Error deleting post.');
                    $('#deleteModal').modal('hide');
                }
            });
        });

        $('.deleteCommentBtn').click(function () {
            commentIdToDelete = $(this).data('comment-id');
            postIdOfComment = $(this).data('post-id');
        });

        $('#confirmDeleteComment').click(function () {
            $.ajax({
                url: `/admin/comment/${commentIdToDelete}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#deleteCommentModal').modal('hide');
                    $('#toastMessage').text('Comment deleted successfully!');
                    const toast = new bootstrap.Toast(document.getElementById('successToast'));
                    toast.show();
                    
                    $(`#comment-${commentIdToDelete}`).remove();
                    
                    const commentCount = $(`.comment-list-${postIdOfComment} li`).length;
                    $(`#commentsModal-${postIdOfComment} .modal-title`).text(
                        `Comments for "${$(`#commentsModal-${postIdOfComment} .modal-title`).text().split('"')[1]}" (${commentCount})`
                    );
                    
                    $(`button[data-bs-target="#commentsModal-${postIdOfComment}"] .badge`).text(commentCount);
                },
                error: function () {
                    alert('Error deleting comment.');
                    $('#deleteCommentModal').modal('hide');
                }
            });
        });

        $('.deleteReplyBtn').click(function () {
            replyIdToDelete = $(this).data('reply-id');
            parentCommentId = $(this).data('comment-id');
            postIdOfComment = $(this).data('post-id');
        });

        $('#confirmDeleteReply').click(function () {
            $.ajax({
                url: `/admin/reply/${replyIdToDelete}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#deleteReplyModal').modal('hide');
                    $('#toastMessage').text('Reply deleted successfully!');
                    const toast = new bootstrap.Toast(document.getElementById('successToast'));
                    toast.show();
                    
                    $(`#reply-${replyIdToDelete}`).remove();
                    
                    if ($(`.replies-list-${parentCommentId} li`).length === 0) {
                        $(`.replies-list-${parentCommentId}`).remove();
                    }
                },
                error: function () {
                    alert('Error deleting reply.');
                    $('#deleteReplyModal').modal('hide');
                }
            });
        });
    });
</script>
@stop
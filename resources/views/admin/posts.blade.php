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
                                                class="badge bg-light text-dark rounded-pill ms-1">{{ $post->likes->count() }}</span>
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#commentsModal-{{ $post->post_id }}">
                                            <i class="fas fa-comment"></i> Comments
                                            <span
                                                class="badge bg-light text-dark rounded-pill ms-1">{{ $post->comments->count() }}</span>
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $post->post_id }}"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete
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
                                                                    class="badge bg-primary rounded-pill me-2">{{ $index + 1 }}</span>
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


                                <div class="modal fade" id="commentsModal-{{ $post->post_id }}" tabindex="-1"
                                    aria-labelledby="commentsModalLabel-{{ $post->post_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Comments for "{{ $post->title }}"
                                                    ({{ $post->comments->count() }})</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($post->comments->count() > 0)
                                                    <ul class="list-group comment-list-{{ $post->post_id }}">
                                                        @foreach ($post->comments as $index => $comment)
                                                            <li class="list-group-item" id="comment-{{ $comment->id }}">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center">
                                                                        <span
                                                                            class="badge bg-primary rounded-pill me-2">{{ $index + 1 }}</span>
                                                                        <strong>{{ $comment->user ? $comment->user->name : 'Unknown User' }}</strong>
                                                                    </div>
                                                                    <button class="btn btn-sm btn-danger delete-comment-btn"
                                                                        data-comment-id="{{ $comment->id }}"
                                                                        data-post-id="{{ $post->post_id }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                                <p class="mt-1 ms-4">{{ $comment->body }}</p>

                                                                @if(isset($comment->replies) && $comment->replies->count() > 0)
                                                                    @include('comments.comments_replies', ['replies' => $comment->replies, 'parentId' => $comment->id, 'postId' => $post->post_id])
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p>No comments yet.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCommentModalLabel">Confirm Comment Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this comment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteComment">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteReplyModal" tabindex="-1" aria-labelledby="deleteReplyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteReplyModalLabel">Confirm Reply Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this reply?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteReply">Delete</button>
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

    $(document).on('click', '.delete-comment-btn', function () {
        commentIdToDelete = $(this).data('comment-id');
        postIdOfComment = $(this).data('post-id');
        $('#deleteCommentModal').modal('show');
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

                const commentCountElement = $(`button[data-bs-target="#commentsModal-${postIdOfComment}"] .badge`);
                const currentCount = parseInt(commentCountElement.text());
                commentCountElement.text(currentCount - 1);

                $(`#comment-${commentIdToDelete}`).remove();

                $(`#commentsModal-${postIdOfComment} .comment-list-${postIdOfComment} > li`).each(function (index) {
                    $(this).find('.badge').first().text(index + 1);
                });

                if ($(`#commentsModal-${postIdOfComment} .comment-list-${postIdOfComment} > li`).length === 0) {
                    $(`#commentsModal-${postIdOfComment} .modal-body`).html('<p>No comments yet.</p>');
                }
            },
            error: function () {
                alert('Error deleting comment.');
                $('#deleteCommentModal').modal('hide');
            }
        });
    });

    $(document).on('click', '.delete-reply-btn', function () {
        replyIdToDelete = $(this).data('reply-id');
        parentCommentId = $(this).data('comment-id');
        postIdOfComment = $(this).data('post-id');
        $('#deleteReplyModal').modal('show');
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

                $(`.reply-list-${parentCommentId} > li`).each(function (index) {
                    $(this).find('.badge').first().text(index + 1);
                });

                const replyCountElement = $(`.comment-replies:has(.reply-list-${parentCommentId}) h6`);
                if (replyCountElement.length > 0) {
                    const currentReplies = $(`.reply-list-${parentCommentId} > li`).length;
                    if (currentReplies > 0) {
                        replyCountElement.html(`<i class="fas fa-reply"></i> Replies (${currentReplies})`);
                    } else {
                        $(`.comment-replies:has(.reply-list-${parentCommentId})`).remove();
                    }
                }

                const commentCountElement = $(`button[data-bs-target="#commentsModal-${postIdOfComment}"] .badge`);
                const currentCount = parseInt(commentCountElement.text());
                commentCountElement.text(currentCount - 1);
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
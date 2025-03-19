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
                                        <span class="badge bg-primary">{{ $index + 1 }}</span>
                                        <strong class="ms-2">{{ $comment->user ? $comment->user->name : 'Unknown User' }}</strong>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-danger deleteCommentBtn" 
                                            data-comment-id="{{ $comment->id }}"
                                            data-post-id="{{ $post->post_id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCommentModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="mt-1 ms-4">{{ $comment->body }}</p>

                                @if(isset($comment->replies) && $comment->replies->count() > 0)
                                    <ul class="list-group mt-2 replies-list-{{ $comment->id }}">
                                        @include('admin.comments_replies', [
                                            'replies' => $comment->replies,
                                            'depth' => 1,
                                            'parentId' => $comment->id,
                                            'postId' => $post->post_id
                                        ])
                                    </ul>
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

<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCommentModalLabel">Confirm Comment Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this comment?</p>
                <p class="text-danger"><strong>Note:</strong> This will also delete all replies to this comment.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteComment">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteReplyModal" tabindex="-1" aria-labelledby="deleteReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteReplyModalLabel">Confirm Reply Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this reply?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteReply">Delete</button>
            </div>
        </div>
    </div>
</div>
@if($comments && count($comments) > 0)
    @foreach($comments as $comment)
        <div class="comment border-bottom py-3" id="comment-{{ $comment->id }}">
            <div class="d-flex">
                <div class="flex-grow-1 ms-3">
                    <h5 class="mt-0">{{ $comment->user->name }}
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </h5>
                    <p>{{ $comment->body }}</p>

                    <button class="btn btn-sm btn-light reply-btn" data-comment-id="{{ $comment->id }}">
                        Reply
                    </button>
                    

                    @if(auth()->id() == $comment->user_id || auth()->id() == $comment->post->user_id)
                        <button class="btn btn-sm btn-light edit-comment-btn" data-comment-id="{{ $comment->id }}"
                            data-comment-body="{{ $comment->body }}">
                            Edit
                        </button>
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @endif

                    <div class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display: none;">
                        <form action="{{ route('comments.reply', $comment->id) }}" method="POST" class="reply-form-container">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="mb-2">
                                <textarea class="form-control" name="body" rows="2" placeholder="Write a reply..."
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Submit Reply</button>
                            <button type="button" class="btn btn-sm btn-light cancel-reply"
                                data-comment-id="{{ $comment->id }}">Cancel</button>
                        </form>
                    </div>

                    <div class="edit-form mt-2" id="edit-form-{{ $comment->id }}" style="display: none;">
                        <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="edit-form-container">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                            <div class="mb-2">
                                <textarea class="form-control" name="body" rows="2" required>{{ $comment->body }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <button type="button" class="btn btn-sm btn-light cancel-edit"
                                data-comment-id="{{ $comment->id }}">Cancel</button>
                        </form>
                    </div>

                    @if($comment->replies->count() > 0)
                        <div class="replies mt-3 ms-4">
                            @include('comments.comments_list', ['comments' => $comment->replies])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted">No comments yet.</p>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
    initializeCommentActions();
});

function initializeCommentActions() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
        return;
    }

    $(document).off('click', '.reply-btn');
    $(document).off('click', '.cancel-reply');
    $(document).off('click', '.edit-comment-btn');
    $(document).off('click', '.cancel-edit');

    $(document).on('click', '.reply-btn', function() {
        var commentId = $(this).data('comment-id');
        $('#reply-form-' + commentId).show();
    });

    $(document).on('click', '.cancel-reply', function() {
        var commentId = $(this).data('comment-id');
        $('#reply-form-' + commentId).hide();
    });

    $(document).on('click', '.edit-comment-btn', function() {
        var commentId = $(this).data('comment-id');
        $('#edit-form-' + commentId).show();
    });

    $(document).on('click', '.cancel-edit', function() {
        var commentId = $(this).data('comment-id');
        $('#edit-form-' + commentId).hide();
    });
    
    console.log('Comment actions initialized');
}

window.addEventListener('focus', function() {
    initializeCommentActions();
});

</script>
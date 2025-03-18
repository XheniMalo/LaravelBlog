@if(isset($replies) && $replies->count() > 0)
    <div class="comment-replies">
        <h6 class="ms-4 mt-2"><i class="fas fa-reply"></i> Replies ({{ $replies->count() }})</h6>
        <ul class="list-group reply-list-{{ $parentId }}">
            @foreach($replies as $replyIndex => $reply)
                <li class="list-group-item reply-item" id="reply-{{ $reply->id }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary rounded-pill me-2">{{ $replyIndex + 1 }}</span>
                            <strong>{{ $reply->user ? $reply->user->name : 'Unknown User' }}</strong>
                        </div>
                        <button class="btn btn-sm btn-danger delete-reply-btn"
                            data-reply-id="{{ $reply->id }}"
                            data-comment-id="{{ $parentId }}"
                            data-post-id="{{ $postId }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <p class="mt-1 ms-4">{{ $reply->body }}</p>
                    
                    @if(isset($reply->replies) && $reply->replies->count() > 0)
                        @include('comments.comments_replies', ['replies' => $reply->replies, 'parentId' => $reply->id, 'postId' => $postId])
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
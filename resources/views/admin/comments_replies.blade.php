@foreach ($replies as $reply)
    <li class="list-group-item" id="reply-{{ $reply->id }}">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <span class="badge bg-secondary ms-{{ $depth * 2 }}">Reply</span>
                <strong class="ms-2">{{ $reply->user ? $reply->user->name : 'Unknown User' }}</strong>
            </div>
            <div>
                <button class="btn btn-sm btn-danger deleteReplyBtn" 
                    data-reply-id="{{ $reply->id }}"
                    data-comment-id="{{ $parentId }}"
                    data-post-id="{{ $postId }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteReplyModal">
                    <i class="fas fa-trash"></i> 
                </button>
            </div>
        </div>
        <p class="mt-1 ms-{{ $depth * 2 + 3 }}">{{ $reply->body }}</p>
        
        @if(isset($reply->replies) && $reply->replies->count() > 0)
            <ul class="list-group mt-2 replies-list-{{ $reply->id }}">
                @include('admin.comments_replies', [
                    'replies' => $reply->replies,
                    'depth' => $depth + 1,
                    'parentId' => $parentId,
                    'postId' => $postId
                ])
            </ul>
        @endif
    </li>
@endforeach
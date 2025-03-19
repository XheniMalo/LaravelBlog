<div>
    <button wire:click="toggleLike" class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }}">
        <i class="fas fa-heart"></i> {{ $isLiked ? 'Unlike' : 'Like' }}
    </button>
    <span>{{ $likesCount }} likes</span>
</div>
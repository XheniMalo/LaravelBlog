@extends('layouts.app')
@section('title', __('messages.my_posts'))
@section('content')

    <div class="container mt-4">
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100 d-flex flex-column">
                        @if($post->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" class="d-block w-100 rounded"
                                alt="{{ __('messages.post_image') }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-placeholder.png') }}" class="d-block w-100 rounded"
                                alt="{{ __('messages.no_image') }}">
                        @endif
                        <div class="card-body flex-grow-1 d-flex flex-column">
                            <h5 class="fw-bold">{{ $post->title }}</h5>
                            <p class="text-muted">{{ Str::limit($post->content, 100) }}</p>
                            <small class="text-muted">{{ __('messages.by') }} {{ $post->user->name }}</small>

                            <div class="mt-3 d-flex justify-content-between">
                                <a href="{{ route('posts.showDetails', $post->post_id) }}" class="btn btn-primary btn-sm">
                                    {{ __('messages.view_more') }} <i class="fas fa-angle-right"></i>
                                </a>

                                <span class="text-muted">
                                    <i class="fas fa-heart"></i>
                                    {{ $post->likesCount() }}
                                    
                                    <i class="fas fa-comment"></i>
                                    {{ $post->comments->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

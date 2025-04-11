@extends('layouts.app')
@section('title', $post->title)
@section('content')

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="fw-bold">{{ $post->title }}</h2>
                <small class="text-muted">{{ __('messages.by') }} {{ $post->user->name }}</small>
                <hr>

                @if($post->images->isNotEmpty())
                    <div class="row">
                        @foreach($post->images as $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded" alt="{{ __('messages.post_image') }}">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">{{ __('messages.no_images_available') }}</p>
                @endif

                <p class="mt-3">{{ $post->content }}</p>

                <div class="d-flex align-items-center mt-3 mb-3">
                    @livewire('like', ['post' => $post])
                </div>

                <div class="card shadow mt-4">
                    <div class="card-header bg-light">
                        <h4>{{ __('messages.comments') }} ({{ $post->comments->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                            <div class="mb-3">
                                <textarea class="form-control" name="body" rows="3" placeholder="{{ __('messages.write_comment') }}" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('messages.add_comment') }}</button>
                        </form>

                        <div class="comments-list mt-4">
                            @include('comments.comments_list', ['comments' => $post->comments])
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
@endsection

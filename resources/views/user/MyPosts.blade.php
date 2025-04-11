@extends('layouts.app')
@section('title', __('messages.my_posts'))
@section('content')
@can('view-post')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">{{ __('messages.my_posts') }}</h2>
        <div class="row">

            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100 d-flex flex-column">
                        @if($post->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" class="d-block w-100 rounded"
                                alt="{{ __('messages.post_image') }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $post->title }}</h5>
                            <p class="text-muted">{{ Str::limit($post->content, 100) }}</p>
                            <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-sm btn-primary">{{ __('messages.view_post') }}</a>
                            <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="d-inline"
                                onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete_post') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        function confirmDelete() {
            return confirm("{{ __('messages.confirm_delete') }}");
        }
    </script>
@endcan
@endsection

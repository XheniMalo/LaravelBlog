@extends('layouts.app')

@section('title', __('messages.create_post'))

@section('content')
@can('manage-post')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">{{ __('messages.create_new_post') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.title') }}</label>
                    <input type="text" name="title" class="form-control" placeholder="{{ __('messages.enter_post_title') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.content') }}</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="{{ __('messages.write_your_post') }}" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.upload_images') }}</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">{{ __('messages.upload_multiple_images_note') }}</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('posts.store') }}" class="btn btn-secondary me-2">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-success">{{ __('messages.publish') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

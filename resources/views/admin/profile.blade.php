@extends('adminlte::page')
@section('title', __('messages.profile') . ' - ATLAS Blog')

@section('content_header')
<h1>{{ __('messages.profile') }}</h1>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-13">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.profile') }} {{ __('messages.information') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminprofile.update', auth()->id()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ auth()->user()->id }}">

                        @if(session('profile_success'))
                            <div class="alert alert-success">
                                {{ session('profile_success') }}
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.full_name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ Auth::user()->name }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', auth()->user()->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profession" class="form-label">{{ __('messages.profession') }}</label>
                            <input type="text" class="form-control @error('profession') is-invalid @enderror"
                                id="profession" name="profession" value="{{ Auth::user()->profession }}">
                            @error('profession')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="birthday" class="form-label">{{ __('messages.birthday') }}</label>
                            <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                id="birthday" name="birthday" value="{{ Auth::user()->birthday }}">
                            @error('birthday')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">{{ __('messages.gender') }}</label>
                            <input type="text" class="form-control @error('gender') is-invalid @enderror" id="gender"
                                name="gender" value="{{ Auth::user()->gender }}">
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.update_profile') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stop
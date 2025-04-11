@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('messages.profile_settings') }}</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="text-center mt-3">
                        @if(Auth::user()->profilePicture && Auth::user()->profilePicture->image_path)
                            <img src="{{ asset('storage/' . Auth::user()->profilePicture->image_path) }}"  
                                alt="{{ __('messages.profile_picture') }}" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-profile.jpg') }}" 
                                alt="{{ __('messages.profile_picture') }}"  style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text">{{ Auth::user()->name }}</p>
                        <hr>
                        <ul class="list-unstyled">
                            <li><strong>{{ __('messages.joined') }}</strong> {{ Auth::user()->created_at->format('M d, Y') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="profileTabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#profile">{{ __('messages.profile') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#security">{{ __('messages.security') }}</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div id="profile" class="tab-pane fade show active">
                                <form action="{{ route('profile.updateImage', auth()->id()) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">{{ __('messages.profile_picture') }}</label>
                                        <input type="file" class="form-control @error('profile_picture') is-invalid @enderror"
                                            id="profile_picture" name="profile_picture">
                                        @error('profile_picture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <button type="submit" class="btn btn-primary mt-2">{{ __('messages.upload') }}</button>
                                    </div>
                                </form>

                                <form action="{{ route('profile.update', auth()->id()) }}" method="POST">
                                    @csrf
                                    @method ('PUT')
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('messages.full_name') }}</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ Auth::user()->name }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ Auth::user()->email }}">
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
                                        <input type="text" class="form-control @error('gender') is-invalid @enderror"
                                            id="gender" name="gender" value="{{ Auth::user()->gender }}">
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success">{{ __('messages.save_changes') }}</button>
                                </form>
                            </div>

                            <div id="security" class="tab-pane fade">
                                <form action="{{ route('profile.security', auth()->id()) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">{{ __('messages.current_password') }}</label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>

                                    <button type="submit" class="btn btn-success">{{ __('messages.update_password') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

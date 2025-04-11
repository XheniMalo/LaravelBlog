@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ __('messages.support_our_blog') }}</h4>
        </div>
        <div class="card-body text-center">
            <p class="mb-4">{{ __('messages.choose_amount') }}</p>

            <form action="{{ route('donate.checkout') }}" method="POST" class="d-inline-block">
                @csrf

                <div class="btn-group mb-3" role="group">
                    @foreach(config('donations.amounts') as $amount)
                        <button type="submit" name="amount" value="{{ $amount }}" class="btn btn-outline-primary">
                            ${{ number_format($amount / 100, 2) }}
                        </button>
                    @endforeach
                </div>

                <div class="my-3">{{ __('messages.or') }}</div>

                <div class="input-group mb-3 w-50 mx-auto">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="custom_amount" min="1" step="0.01">
                </div>

                <button type="submit" class="btn btn-success">{{ __('messages.donate_custom_amount') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('platform::dashboard')

@section('title', 'Add Contact')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add Customer') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('wa-user.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="customer_name">{{ __('Customer Name') }}</label>
                                <input id="customer_name" type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name" value="{{ old('customer_name') }}" required autofocus>

                                @error('customer_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="whatsapp_number">{{ __('Customer WhatsApp Number (International Format)') }}</label>
                                <input id="whatsapp_number" type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required>

                                @error('whatsapp_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Submit') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('platform::dashboard')

@section('title', 'Chat Requests')

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Ongoing Chats</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($ongoing_request as $request)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $request->customer_id }}
                                <form method="post" action="{{ route('update.chat_request', $request->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="end">
                                    <input type="hidden" name="customer_id" value="{{$request->customer_id}}">
                              
                                    <button type="submit" class="btn btn-danger btn-sm">End Chat</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Pending Chats</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($pending as $request)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $request->customer_id }}
                                <form method="post" action="{{ route('update.chat_request', $request->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="accept">
                                    <input type="hidden" name="customer_id" value="{{$request->customer_id}}">
                                    {{-- <input type="hidden" name="customer_reps_id" value="{{Auth::user()->id}}"> --}}
                                    <button type="submit" class="btn btn-primary btn-sm">Accept Chat</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

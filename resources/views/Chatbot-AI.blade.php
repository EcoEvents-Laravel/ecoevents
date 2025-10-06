@extends('layouts.front')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card border-0 rounded-3 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white text-center rounded-top-3">
                        <h3 class="mb-0 fw-bold">Chatbot AI</h3>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div id="chat-container" class="mb-3"
                            style="height: 400px; overflow-y: auto; background: #f4f6fb; border-radius: 0.5rem;">
                            <div id="chat-log" style="height: 320px; overflow-y: auto;">
                                @if(session('userMessage'))
                                    <div class="mb-2">
                                        <span class="fw-semibold text-primary">You:</span>
                                        <span class="ms-1">{{ session('userMessage') }}</span>
                                    </div>
                                @endif
                                @if(session('botResponse'))
                                    <div class="mb-2">
                                        <span class="fw-semibold text-success">Bot:</span>
                                        <span class="ms-1">{{ session('botResponse') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('chatbot.response') }}">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control rounded-start-3" id="user-input"
                                    placeholder="Type your message here..." name="message">
                                <button id="send-button" class="btn btn-primary rounded-end-3">Send</button>
                            </div>
                            @error('message')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

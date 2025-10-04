@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Chatbot AI</h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="chat-container" class="p-3"
                            style="height: 400px; overflow-y: auto; background: #f8f9fa;">
                            <div id="chat-log" style="height: 320px; overflow-y: auto;">
                                @if(session('userMessage'))
                                    <div class="mb-2">
                                        <strong>You:</strong> {{ session('userMessage') }}
                                    </div>
                                @endif
                                @if(session('botResponse'))
                                    <div class="mb-2">
                                        <strong>Bot:</strong> {{ session('botResponse') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-3 border-top bg-light">
                            <form method="POST" action="{{ route('chatbot.response') }}">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" id="user-input"
                                        placeholder="Type your message here..." name="message">
                                        @error('message')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    <button id="send-button" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

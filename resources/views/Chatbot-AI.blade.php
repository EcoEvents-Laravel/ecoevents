@extends('layouts.front')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <style>
        .chatbot-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            background: linear-gradient(135deg, #e6ffe6 0%, #f8f9fa 100%);
        }
        .chatbot-header {
            background: linear-gradient(90deg, #28a745 60%, #00c6ff 100%);
            color: #fff;
            border-radius: 1rem 1rem 0 0;
            box-shadow: 0 2px 8px rgba(40,167,69,0.08);
        }
        #chat-container {
            height: 400px;
            overflow-y: auto;
            background: #f8f9fa;
            border-radius: 0.75rem;
            border: 1px solid #e3e3e3;
        }
        #chat-log {
            height: 320px;
            overflow-y: auto;
            padding: 0.5rem 1rem;
        }
        .chat-message {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        .chat-user {
            background: #e6ffe6;
            color: #28a745;
            border-radius: 0.75rem 0.75rem 0.75rem 0.25rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            margin-right: auto;
            max-width: 80%;
            box-shadow: 0 2px 8px rgba(40,167,69,0.04);
        }
        .chat-bot {
            background: #e3f0ff;
            color: #007bff;
            border-radius: 0.75rem 0.25rem 0.75rem 0.75rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            margin-left: auto;
            max-width: 80%;
            box-shadow: 0 2px 8px rgba(0,123,255,0.04);
        }
        .input-group .form-control {
            border-radius: 1rem 0 0 1rem !important;
            border: 1px solid #e3e3e3;
            background: #f8f9fa;
        }
        .input-group .btn {
            border-radius: 0 1rem 1rem 0 !important;
            font-weight: 600;
            background: linear-gradient(90deg, #28a745 60%, #00c6ff 100%);
            border: none;
        }
        .input-group .btn:hover {
            background: linear-gradient(90deg, #218838 60%, #0096c7 100%);
        }
    </style>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card chatbot-card">
                    <div class="card-header chatbot-header text-center">
                        <h3 class="mb-0 fw-bold">Chatbot AI</h3>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div id="chat-container" class="mb-3">
                            <div id="chat-log">
                                @if(session('userMessage'))
                                    <div class="chat-message">
                                        <div class="chat-user">
                                            <span class="fw-semibold">You:</span>
                                            <span class="ms-1">{{ session('userMessage') }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if(session('botResponse'))
                                    <div class="chat-message">
                                        <div class="chat-bot">
                                            <span class="fw-semibold">Bot:</span>
                                            <span class="ms-1">{{ session('botResponse') }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" style="height: 120px;">
                            <form method="POST" action="{{ route('chatbot.response') }}" style="width: 100%; max-width: 600px;">
                                @csrf
                                <div class="input-group input-group-lg justify-content-center">
                                    <input type="text" class="form-control" id="user-input"
                                        placeholder="Type your message here..." name="message" style="font-size: 1.5rem; height: 40px; width: 300px;">
                                    <button id="send-button" class="btn btn-primary" style="font-size: 1.5rem; height: 40px; min-width: 120px;">Send</button>
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
    </div>
@endsection

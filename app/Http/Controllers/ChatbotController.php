<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChatbotRequest;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('Chatbot-AI');
    }
    public function getResponse(ChatbotRequest $request)
    {
        $userMessage = $request->input('message');

        if (empty($userMessage)) {
            return redirect()->back()->with('error', 'Aucun message reçu.');
        }

        $apiKey = env('API_KEY');
        if (empty($apiKey)) {
            return redirect()->back()->with('error', 'API Key manquante ou non configurée.');
        }
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $userMessage]
                    ]
                ]
            ]
        ];

        $client = new Client();
        try {
            $response = $client->post($endpoint, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            $data = json_decode($response->getBody(), true);

            if (
                isset($data['candidates'][0]['content']['parts'][0]['text']) &&
                is_string($data['candidates'][0]['content']['parts'][0]['text'])
            ) {
                $botResponse = $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $botResponse = 'Aucune réponse valide du bot.';
            }
        } catch (\Exception $e) {
            $botResponse = 'Erreur lors de la connexion à Google AI Studio : ' . $e->getMessage();
        }

        // Use session flash instead of with() for redirect
        // NOTE: The view should retrieve 'botResponse' and 'userMessage' using session('botResponse') and session('userMessage')
        session()->flash('botResponse', $botResponse);
        session()->flash('userMessage', $userMessage);

        return redirect()->back();
    }
}

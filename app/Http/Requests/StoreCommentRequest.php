<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // On autorise toute personne authentifiée à commenter.
        // Des règles plus complexes pourraient être ajoutées ici.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'content' => ['required', 'string', 'min:10', 'max:1000'],
            'event_id' => ['required', 'exists:events,id'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'content.required' => 'Le commentaire ne peut pas être vide.',
            'content.min' => 'Le commentaire doit contenir au moins :min caractères.',
            'content.max' => 'Le commentaire ne doit pas dépasser :max caractères.',
            'event_id.required' => 'Une erreur est survenue, l\'événement est manquant.',
            'event_id.exists' => 'L\'événement spécifié n\'existe pas.',
        ];
    }
}
<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        // On autorise la suppression si l'ID de l'utilisateur connecté
        // est identique à l'ID de l'utilisateur qui a créé le commentaire.
        return $user->id === $comment->user_id;
        
        // Plus tard, on pourrait ajouter une condition pour les administrateurs, par exemple :
        // return $user->id === $comment->user_id || $user->isAdmin();
    }
}
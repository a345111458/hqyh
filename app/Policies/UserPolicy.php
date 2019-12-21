<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $currentUser , User $user){
        if (!in_array($currentUser->email , ['afton.beer@example.com','345111458@qq.com']) && $currentUser->email != $user->email){
            return false;
        }
        return true;
    }


    public function destroy(User $currentUser , User $user){
        if (!in_array($currentUser->email , ['bruce.dubuque@example.net','345111458@qq.com']) || $currentUser->email == $user->email || in_array($user->email , ['queenie.mccullough@example.com','345111458@qq.com'])){
            return false;
        }
        return true;
    }



}

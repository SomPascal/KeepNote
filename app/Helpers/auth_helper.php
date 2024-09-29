<?php

use App\Entities\User;

/**
 * @return bool
 * 
 * Check if the current visitor is connected
 */
function isSignedIn():bool
{
    return session()->has("user");
}

function login(User $user): void
{
    session()->set("user", [
        "id" => $user->id(),
        "username" => $user->username(),
        "color" => $user->color()
    ]);
}

function isSignedUp(): bool
{
    return session()->has("signed_up") && 
    session()->get("signed_up") === true;
}
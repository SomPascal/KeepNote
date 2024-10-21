<?php

namespace App\Validation;

use App\Models\AccountModel;

class UserRules
{
    /**
     * @param string $username The username to check
     * @param string &$error The error message in case of failure
     * @return bool
     */
    public function username(string $username): bool
    {
        return (bool) preg_match("/^[a-zA-Z][a-zA-Z0-9_ ]{2,23}$/", $username);
    }

    /**
     * @param string $password The password to check
     * @param ?string $&error The error message in case of failure
     */
    public function strong_password(string $password): bool
    {
        helper("text");
        $password = convert_accented_characters(trim($password));

        $state = (bool)
        (preg_match(pattern: "/[A-Z]{1,}/", subject: $password) && // uppercase
        preg_match(pattern: "/[a-z]{1,}/", subject: $password) && // lowercase
        preg_match(pattern: "/[0-9]{1,}/", subject: $password)); // digit

        if ($state)
        {
            $specialChars = preg_replace(pattern: "/[A-Za-z0-9]/", replacement: "", subject: $password);
            $state = mb_strlen($specialChars) >= 1;
        }
        return $state;
    }


    public function correct_password(string $typed_password): bool
    {
        $hashed_password = model(AccountModel::class)->get("password");

        return password_verify(
            $typed_password,
            $hashed_password
        );
    }

    public static function isCorrectLogIn(string $username, string $typed_password): bool
    {
        $model = model(AccountModel::class);

        $check_username = $model->where("username", $username)
        ->select("password")->find();

        if (count($check_username) > 0) 
        {
            $password_hash = $check_username[0]["password"];

            if (password_verify(
                password: $typed_password,
                hash: $password_hash
            ) == true) 
                return true;
            else return false;
        }
        else return false;
    }
}
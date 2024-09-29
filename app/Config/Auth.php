<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    /**
     * The named where the user will be redirected after signed out
     */
    public string $signOutRedirection = "home";

    /**
     * If set to true, all new visits will be recorded in the database
     */
    public bool $visitTracker = true;

    /**
     * Set of routes disallowed to the visitor
     */
    public array $visitorDisallowedRoutes = 
    [
        "GET" => 
        [
            "account/*", "signout", 
            "note/*", "login-help/recovery-by-question",
            "login-help/forgot-password"
        ],

        "POST" => 
        [
            "note/*", "login-help/recovery-by-question",
            "login-help/forgot-password"
        ],

        "DELETE" => 
        [
            "note/*", "auth/*", 
            "login-help/recovery-by-question",
            "login-help/forgot-password"
        ],
        
        "PUT" => 
        [
            "note/*", "auth/*", 
            "login-help/recovery-by-question",
            "login-help/forgot-password"
        ]
    ];

    /**
     * Set of routes disallowed to the user
     */
    public array $userDisallowedRoutes = 
    [
        "GET" => ["signin", "signup"],
        "POST" => ["signin", "signup"],
        "DELETE" => ["signin", "signup"],
        "PUT" => ["signin", "signup"]
    ];
}

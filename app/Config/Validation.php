<?php

namespace Config;

use App\Validation\UserRules;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        UserRules::class
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $signup = 
    [
        "agree_terms" => [
            "required",
            "in_list[true,1,on]"
        ],
        "username" => [
            "required",
            "max_length[24]",
            "min_length[3]",
            "username",
            "is_unique[users.username]"
        ],
        "password" => [
            "required",
            "min_length[6]",
            "max_length[24]",
            "alpha_numeric_punct",
            "strong_password"
        ],
        "password_confirmation" => [
            "required",
            "matches[password]"
        ]
    ];

    public array $signup_errors =
    [
        "agree_terms" =>
        [
            "required" => "You should agree our terms of use before to sign up",
            "in_list" => "You should agree our terms of use before to sign up"
        ],
        // "username" =>  ["is_unique" => "This username is already used"]
    ];

    public array $signin =
    [
        "username" => 
        [
            "required",
            "max_length[24]",
            "min_length[3]",
            "username",
        ],
        "password" => 
        [
            "required",
            "min_length[6]",
            "max_length[24]",
            "alpha_numeric_punct"
        ]
    ];

    public array $change_username = 
    [
        "new_username" => 
        [
            "required",
            "max_length[24]",
            "min_length[3]",
            "username",
            "is_unique[users.username]"
        ],
        "password" => 
        [
            "required",
            "min_length[6]",
            "max_length[24]",
            "alpha_numeric_punct",
            "correct_password"
        ],
    ];

    // public array $change_username_errors =
    // [
    //     "new_username" => ["is_unique" => "This username is already used by you or another user"]
    // ];

    public array $change_password = 
    [
        "current_password" => 
        [
            "required",
            "max_length[24]",
            "min_length[6]",
            "alpha_numeric_punct",
            "strong_password",
            "correct_password"
        ],
        "password" => 
        [
            "required",
            "max_length[24]",
            "min_length[6]",
            "alpha_numeric_punct",
            "differs[current_password]",
            "strong_password"
        ],
        "password_confirmation" => 
        [
            "required",
            "matches[password]"
        ]
    ];

    public array $change_password_errors =
    [
        "password" => [
            "differs" => "The new password field must differ from the current password field"
        ]
    ];

    public array $create_note = [
        "note_title" => [
            "max_length[300]"
        ],
        "note_body" => [
            "required",
            "max_length[800]"
        ],
        "note_font" => [
            "required",
            "in_list[poppins,roboto,serif,Courier New]"
        ],
        "note_color" => [
            "required",
            "in_list[#89f3ff7a,#76ff769e,#ffe4c4bc,#ff5a5a74,#ffff6aaf,#f2f2f27a]"
        ]
    ];

    public array $update_note = [  
        "note_title" => [
            "max_length[300]"
        ],
        "note_body" => [
            "required",
            "max_length[800]"
        ],
        "note_font" => [
            "required",
            "in_list[poppins,roboto,serif,Courier New]"
        ],
        "note_color" => [
            "required",
            "in_list[#89f3ff7a,#76ff769e,#ffe4c4bc,#ff5a5a74,#ffff6aaf,#f2f2f27a]"
        ],
        "note_id" => [
            "required",
            "min_length[5]",
            "max_length[14]",
            "alpha_numeric_punct"
        ] 
    ];

    public array $password_recovery_by_question = [
        "question" => "required|min_length[3]|max_length[350]|alpha_numeric_punct",
        "answer" => "required|min_length[3]|max_length[150]|alpha_numeric_punct"
    ];
}

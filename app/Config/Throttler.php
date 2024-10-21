<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Throttler extends BaseConfig
{
    public array $recove_password = 
    [
        "key" => "throttler.recove_password.%s",
        "capacity" => 5,
        "seconds" => MINUTE
    ];
    public array $remember_me = 
    [
        "key" => "throttler.remember_me.%s",
        "capacity" => 5,
        "seconds" => MINUTE

    ];
    
    public array $goto =
    [
        "key" => "throttler.goto.%s",
        "capacity" => 5,
        "seconds" => MINUTE
    ];

    public array $signin = 
    [
        "key" => "throttler.signin.%s",
        "capacity" => 5,
        "seconds" => MINUTE
    ];

    public array $signup =
    [
        "key" => "thottler.signup.%s",
        "capacity" => 10,
        "seconds" => MINUTE
    ];

    public array $change_username =
    [
        "key" => "thottler.change_username.%s",
        "capacity" => 5,
        "seconds" => MINUTE
    ];

    public array $change_password = 
    [
        "key" => "throttler.change_password.%s",
        "capacity" => 5,
        "seconds" => MINUTE
    ];

    public array $get_notes =
    [
        "key" => "throttler.get_notes.%s",
        "capacity" => 5,
        "seconds" => MINUTE
    ];

    public array $create_note =
    [
        "key" => "throttler.create_note.%s",
        "capacity" => 5,
        "seconds" => 30*SECOND
    ];

    public array $update_note =
    [
        "key" => "throttler.update_notes.%s",
        "capacity" => 5,
        "seconds" => 30*SECOND
    ];

    public array $use_share_link =
    [
        "key" => "throttler.use_share_link.%s",
        "capacity" => 5,
        "seconds" => 30*SECOND
    ];

    public array $share_notes_link =
    [
        "key" => "throttler.share_notes_link.%s",
        "capacity" => 3,
        "seconds" => 30*SECOND
    ];

    public array $delete_notes = 
    [
        "key" => "throttler.delete.%s",
        "capacity" => 3,
        "seconds" => 15*SECOND
    ];

    public array $import_notes =
    [
        "key" => "throttler.import_notes.%s",
        "capacity" => 3,
        "seconds" => 30*SECOND
    ];

    public array $download_notes =
    [
        "key" => "throttler.download.%s",
        "capacity" => 3,
        "seconds" => MINUTE
    ];
}

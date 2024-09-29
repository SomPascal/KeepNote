<?php

return 
[
    "title" =>
    [
        "default" => "An error occured!",
        "download-expired" => "Download expired",
        "wrong-config" => "Wrong config file",
        "wrong-id" => "Wrong note's id",
        "http" => "Error {code}",
        "notes-limit" => "You reached your limit of notes",
        "use-own-link" => "You can't open your own share notes link",
        "link-expired" => "This link has expitred",
        "link-already-used" => "An error occured. This link has already been used",
        "link-doesnt-exist" => "This share notes link doesn't exist",

        "401" => [
            "use-link" => "You're unauthorized to use this link"
        ],
        "404" => 
        [
            "no-page" => "The page you went looking for doesn't exist"
        ],
        "429" => "Too many attempts. Try again in {sec} second(s)"
    ],
    "body" =>
    [
        "link-doesnt-exist" => "An error occured. This share notes link doesn't exist",
        "notes-limit" => "You reached the limit of notes per user: {limit}",
        "link-already-used" => "An error occured. This link has already been used",
        "link-expired" => "An error occured. This link has expired {period}",
        "401" => [
            "use-link" => "An error occured. You can't open your own share notes link. Send rather it to another keepnote's user."
        ],
        "default" => "Something went wrong during the process",
        "download-expired" => "The download was expired. Press the button below to go back"
    ],
    "desc" => 
    [
        "download-expired" => "This download was expired!",
        "wrong-config" => "Wrong config file. Should be json or xml",
        "wrong-id" => "Wrong notes's id form"
    ]
];
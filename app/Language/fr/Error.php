<?php

return 
[
    "title" =>
    [
        "default" => "Une erreur s'est produite !",
        "download-expired" => "Téléchargement expiré",
        "wrong-config" => "Fichier de configuration incorrect",
        "wrong-id" => "identifiant de la note erronée",
        "http" => "Erreur {code}",
        "notes-limit" => "Vous avez atteint votre limite de notes",
        "use-own-link" => "Vous ne pouvez pas ouvrir votre prope lien de partage",
        "link-expired" => "Ce lien a déjà expiré",
        "link-already-used" => "Une erreur esy survenu. Vous avez déjà utilisé ce lien",
        "link-doesnt-exist" => "Ce lien n'existe pas.",

        "401" => [
            "use-link" => "Vous n'etes pas authorizé à utiliser ce lien"
        ],
        "404" => 
        [
            "no-page" => "La page que vous recherchez n'existe pas"
        ],
        "429" => "Trop de requetes. Essayez de nouveau dans {sec} secondes."
    ],
    "body" =>
    [
        "link-doesnt-exist" => "Une erreur est survenue. Ce lien de partage n'existe pas.",
        "notes-limit" => "Vous avez atteint la limite de notes par utilisateur: {limit}",
        "link-already-used" => "Une erreur est survenue. Ce lien à déjà été utilsé par vous",
        "link-expired" => "Une erreur est survenue. Ce lien a expiré {period}",
        "401" => [
            "use-link" => "Une erreur est survenue. Vous ne pouvez pas ouvrir votre propre lien de partage. Partagez le plutot à un autre utilisateur de KeepNote."
        ],
        "default" => "Un problème est survenu au cours du processus",
        "download-expired" => "Le téléchargement a expiré. Appuyez sur le bouton ci-dessous pour revenir en arrière"
    ],
    "desc" => 
    [
        "download-expired" => "Ce téléchargement a expiré !",
        "wrong-config" => "Mauvais fichier de configuration. Devrait être json ou xml",
        "wrong-id" => "Forme d'identification des notes erroné"
    ]
];
class i18n
{
    /**
     * @var {String}
     */
    env

    /**
     * @var {Array}
     */
    supportedLanguages

    /**
     * @var {String}
     */
    defaultLanguage

    /**
     * @var {?String}
     */
    currentLanguage = null

    /**
     * @var {Object}
     */
    contents = 
    {
        "en": 
        {
            "agree-t-o-u": "You must agree our <b>terms and conditions of use</b> before to sign up",
            "success-created": "Your note has been successfully created",
            "success-upload": "Your notes was successfully uploaded !",
            "success-modified": "Your note has been successfully modified",
            "success-deleted": "Your notes has been successfully deleted",

            "save-share-link": "Save the share link",
            "set-share-link": "Link: {link}\n\nCreated at: {created_at}\nExpire at: {expire_at}",

            "no-downloaded-notes": "no downloaded notes yet",
            "tap-to-upload": "Tap to upload notes",
            "error-occured": "An error occurred during the process",
            "copy-unavailable": "The copy feature isn't available",
            "sure-delete-notes": "Are you sure to really delete {num} note(s) ?",

            "at-least-del": "You have to select at least {num} note(s) to delete.",
            "at-least-share": "You have to select at least {num} note to share.",
            "at-most-del": "You can only delete {max} notes at time. But {picked} note(s) selected",

            "import-limit": "The limit of imports per upload is <b>{limit}</b> but <b>{picked}</b> selected.",

            "share-limit": "The limit of notes per share is <b>{limit}</b> but <b>{picked}</b> selected",

            "ext-auth": "Only \"XML\" and \"JSON\" files are authorized but \"{extension}\" detected in \"{file_name}\"",
            "heavy-file": "The \"{file_name}\" file is too heavy. The maximum size is {weight}",

            "reload-page": "Reload the current page",
            "no-notes": "No notes yet",
            "not-found": "Not found",

            "modify-b4-save": "You should add some modification to your note before to save it.",

            "hello": "Hello",

            "title": "Title",
            "body": "Body",

            "select": "Select",
            "selected": "selected",

            "copy": "Copier",
            "copied": "Copiée(s)",
            "try-again": "Try again",

            "x-selected-notes": "{x} note(s) selected",
            "x-saved-notes": "{x} notes(s) saved"
        },
        
        "fr": 
        {
            "agree-t-o-u": "Vous devez accepter nos <b>conditons d'utilisation</b> avant de vous enregistrer",
            "success-created": "Cette note a été créé avec succès",
            "success-upload": "Vos notes ont élé importées avec succès",
            "success-modified": "Votre note à été modifié avec succès",
            "success-deleted": "Vos notes ont été supprimés avec succès",

            "save-share-link": "Enrégistrez le lien de partage",
            "set-share-link": "Lien: {link}\n\nCréé le: {created_at}\nExpire le: {expire_at}",

            "no-downloaded-notes": "Pas encore de notes téléchargé...",
            "tap-to-upload": "Appuyez pour importer vos notes",
            "error-occured": "Une erreur c'est produite durant le traitement",
            "copy-unavailable": "Pour l'instant, le copier-coller n'est pas disponible",

            "no-notes": "Vous n'avez pas encore de notes",
            "not-found": "N'existe pas",
            "modify-b4-save": "Vous devez d'abord modifier la note avant de l'enrégistrer.",
            "sure-delete-notes": "Etes vous sur de supprimer {num} notes ?",

            "at-least-del": "Vous devez choisir au moins {num} note(s) à supprimer",
            "at-least-share": "Vous devez choisir au moins {num} note à partager",
            "at-most-del": "Vous ne pouvez uniquement supprimer {max} notes à la fois. Mais vous avais selctionné {picked} notes",

            "import-limit": "La limite d'importation en une fois est de <b>{limit}</b> mais {picked} ont été selectionnés",
            "share-limit": "La limite de notes par partage est de <b>{limit}</b> mais {picked} ont été selectionnés",

            "ext-auth": "Seuls les fichiers \"XML\" et \"JSON\" sont are authorisés, mais un fichier \"{extension}\" à été détecté dans \"{file_name}\"",
            "heavy-file": "Le fichier \"{file_name}\" est trop lourd. La taille maximale est de {max_weight}",

            "hello": "Bonjour",

            "no-notes": "Pas encore de notes enregistrées",
            "reload-page": "Veuillez recharger la page",
            "select": "Selectionner",
            "selected": "Selectionné",

            "title": "Titre",
            "body": "Contenu",

            "copy": "Copier",
            "copied": "Copiée(s)",
            "try-again": "Essayez de nouveau",

            "x-selected-notes": "{x} note(s) selectionné(s)",
            "x-saved-notes": "{x} notes(s) enregistrée(s)"
        }
    }

    /**
     * 
     * @param {String} locale The default website locale
     */
    constructor({locale, env, supportedLanguages, defaultLanguage})
    {
        this.env = env ?? "dev"
        this.supportedLanguages = supportedLanguages
        this.defaultLanguage = defaultLanguage
        this.setLocal(locale ?? "")
    }

    /**
     * 
     * @param {String} locale The current locale
     * @returns void
     */
    setLocal(locale="")
    {
        if (this.supportedLanguages.includes(locale))
            this.currentLanguage = locale
        else if (locale == "")
        {
            let baseLang

            baseLang = navigator.language.split(new RegExp("(-|_)"))[0]

            if (this.supportedLanguages.includes(navigator.language))
                this.currentLanguage = navigator.language
            else if (this.supportedLanguages.includes(baseLang))
                this.currentLanguage = baseLang
            else 
                this.currentLanguage = this.defaultLanguage
        }
        else 
        {
            if (this.env == "production")
                this.currentLanguage = this.defaultLanguage
            else 
            {
                throw new Error(`Unsupported locale time: "${locale}"`)
            }
        }
    }

    /**
     * 
     * @param {String} text 
     * @param {Object} data 
     */
    format(text, data)
    {
        for (const key in data) 
        {
            if (Object.hasOwnProperty.call(data, key)) 
            {
                text = text.replace(`{${key}}`, data[key])
            }
        }
        return text
    }

    /**
     * 
     * @param {String} query
     * @param {Object} data
     */
    get(query, data={})
    {
        let translated = ""

        if (this.contents[this.currentLanguage][query] != null)
            translated = this.contents[this.currentLanguage][query]
        else
        {
            for (const locale in this.contents) 
            {
                if (Object.hasOwnProperty.call(this.contents, locale)) 
                {
                    if (this.contents[locale][query] != null)
                        translated = this.contents[locale][query]
                }
            }
        }

        return this.format(translated, data)
    }
}

const lang = new i18n({
    locale: "",
    env: "production", 
    supportedLanguages: ["en", "fr"], 
    defaultLanguage: "fr"
})
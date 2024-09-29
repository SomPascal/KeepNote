
const PHONE_SIZE = 650
const KILO = 1000

const UPLOAD_NOTES_LIMIT = 10
const DEL_NOTES_LIMIT = 10
const SHARE_NOTES_LIMIT = 10

enable_delete_btns()

const navbar = document.querySelector(".navbar")
const search_form = document.querySelector("#search_form")
const notification = document.querySelector("#notification")

const create_note_btn = document.querySelector("#create_note")
const create_text_note_btn = document.querySelector("#create_text_note")

let upload_text_note = 
{
    btn: document.querySelector("#upload_downloaded_notes"),
    get: document.querySelector("#upload-notes"),
    downloaded_notes: document.querySelector("#downloaded-notes"),
    form: document.querySelector("#upload-notes form"),
    submit: document.querySelector("#submit-upload"),
    cancel: document.querySelector("#cancel-upload")
}

let share_notes =
{
    input_link: document.querySelector("#share-notes form input[type='link']"),
    submit: document.querySelector("#submit-share"),
    save: document.querySelector("#save-share-link"),
    cancel: document.querySelector("#cancel-share"),
    copy: document.querySelector("#copy-share-link"),
    get: document.querySelector("#share-notes"),
    form: document.querySelector("#share-notes form")
}

const create_new_note_btn = document.querySelector("#create-new-note")
const blurred_wall = document.querySelector(".blurred-wall")
const download_notes = document.querySelector("#download-notes")

responsive_search()
responsive_notes_features_btn()

window.addEventListener("resize", ()=> {
    responsive_search()
    responsive_notes_features_btn()
})

search_input.addEventListener("focus", (e)=>{
    if (window.innerWidth <= PHONE_SIZE) 
    {
        navbar.querySelector(".navbar-brand h2")?.classList.add("is-hidden")
    }
})

search_input.addEventListener("blur", (e)=>{
    navbar.querySelector(".navbar-brand h2")?.classList.remove("is-hidden")
})

search_form.addEventListener("submit", (e)=>{
    e.preventDefault()
})

create_note_btn.onclick = function (){

    if (create_note_btn.getAttribute("is-active") == "false") 
    {
        blurred_wall.classList.remove("hide")
        enable_create_note(true)

        blurred_wall.onclick = function(){
            blurred_wall.classList.add("hide")
            enable_create_note(false)
        }
    }
    else 
    {
        blurred_wall.classList.add("hide")
        enable_create_note(false)
    }
    
    /**
     * 
     * @param {Boolean} flag 
     */
    function enable_create_note(flag) 
    {
        if (flag == true)
        {
            create_note_btn.parentNode.style.zIndex = "60"
            create_note_btn.querySelector("i").classList.replace("fa-plus", "fa-close")
            create_note_btn.setAttribute("is-active", "true")

            if (window.innerWidth > PHONE_SIZE)
            {
                create_text_note_btn.style.transform = "translateX(-90px)";
                upload_text_note.btn.style.transform = "translateX(-180px)"
            }
            else 
            {
                create_text_note_btn.style.transform = "translateY(-180px)";
                upload_text_note.btn.style.transform = "translateY(-90px)"
            }
        }
        else
        {
            create_note_btn.parentNode.style.zIndex = ""
            create_note_btn.querySelector("i").classList.replace("fa-close", "fa-plus")
            create_note_btn.setAttribute("is-active", "false")

            create_text_note_btn.style.transform = "translateY(0px)";
            upload_text_note.btn.style.transform = "translate(0px)"
        }
    }
}

function responsive_notes_features_btn()
{
    let buttons = document.querySelector(".main .notes-container .notes-assets .buttons")

    if (window.innerWidth <= PHONE_SIZE) 
        buttons?.classList.remove("mt-1")
    else 
        buttons?.classList.add("mt-1")
}

/**
 * 
 * @param {String} msg 
 * @param {Boolean} flag 
 */
function disabled_window_msg(msg="", flag=true)
{
    let window_message = document.querySelector(".window-msg")

    if (window_message != null)
    {
        if (flag == true) {
            window_message.querySelector("p").innerHTML = ""
            window_message.querySelector("button").parentNode.classList.add("hide")
        }
        else {
            window_message.querySelector("p").innerHTML = msg
            window_message.querySelector("button").parentNode.classList.remove("hide")
        }
    }
}

function responsive_search()
{
    if (window.innerWidth <= PHONE_SIZE) 
    {
        search_input.classList.replace("is-medium", "is-normal")
        search_input.parentNode.classList.replace("is-medium", "is-normal")
        search_input.parentNode.querySelector("span").classList.replace("is-medium","is-normal")
        navbar.querySelector(".navbar-brand h2")?.classList.replace("fs-3", "fs-2")
    }
    else 
    {
        search_input.classList.replace("is-normal", "is-medium")
        search_input.classList.replace("is-normal", "is-medium")
        search_input.parentNode.classList.replace("is-normal", "is-medium")
        search_input.parentNode.querySelector("span").classList.replace("is-normal","is-medium")
        navbar.querySelector(".navbar-brand h2")?.classList.replace("fs-2", "fs-3")
    }
}

/**
 * 
 * @param {String} message 
 * @param {Number} time
 * @param {Boolean} danger 
 */
function set_notification(message, time=3500, status=false)
{
    let color = (status == true) ? "is-danger" : "is-success"
    let icon = (status == true) ? "<i class='fa fa-warning'></i>" : "<i class='fa fa-check-circle'></i>"
    
    notification.classList.add(color)
    notification.querySelector("p").innerHTML = `${message} ${icon}`
    notification.classList.remove("hide")
    
    setTimeout(() => {
        notification.classList.remove(color)
        notification.querySelector("p").innerHTML = ""
        notification.querySelector(".delete").onclick()
    }, time)
}

const notes = 
{
    "selected": [],
    "create": {
        "colors": 
        [
            "#89f3ff7a", "#76ff769e",
            "#ffe4c4bc", "#ff5a5a74",
            "#ffff6aaf", "#f2f2f27a"
        ],
        "fonts": ["Courier New", "roboto", "serif", "poppins"],
    },

    "extended_note": document.querySelector(".extended-note"),
    "extended_note_form": document.querySelector(".extended-note form"),
    "extended_note_title": document.querySelector(".extended-note form input"),
    "extended_note_body": document.querySelector(".extended-note form textarea"),

    "notes_container": document.querySelector(".main .notes-container"),
    "extend_btn": document.querySelector("#extend-btn"),
    "copy_btn": document.querySelector("#copy-btn"),
    "edit_btn": document.querySelector("#edit-btn"),
    "share_btn": document.querySelector("#share-btn"),
    "download_btn": document.querySelector("#download-btn"),
    "delete_btn": document.querySelector("#delete-btn"),

    "extended_note_feat": 
    {
        loader: {
            enable: ()=> {
                notes.extended_note_title.parentNode.classList.add("is-loading")
                notes.extended_note_body.parentNode.classList.add("is-loading")
            },

            disable: ()=> {
                notes.extended_note_title.parentNode.classList.remove("is-loading")
                notes.extended_note_body.parentNode.classList.remove("is-loading")
            }
        },

        alert: {
            get: undefined,

            setGet: function()
            {
                this.get = notes.extended_note.querySelector(".notes-data .field .notification")
            },

            /**
              * @param {String} message
              * @returns
              */
            set_message: function(message)
            {
                this.get.classList.remove("hide")
                this.get.querySelector("p").innerHTML = message
            },

            /**
              * @returns void
              */
            clear: function()
            {
                this.get.classList.add("hide")
                this.get.querySelector("p").innerHTML = ""
            }
        }
    },  

    "setSelectedNotes": function()
    {
        const tag = document.querySelector(".notes-assets .tag")

        tag.classList.remove("is-warning")
        tag.classList.add("is-info")
        tag.innerHTML = lang.get("x-selected-notes", {"x": Math.abs(this.selected.length)})
    },

    "setNumNotes": function()
    {
        const tag = document.querySelector(".notes-assets .tag")
        let num_notes = this.notes_container.querySelectorAll(".notes-box ul li").length - this.selected.length

        tag.classList.remove("is-info")
        tag.classList.add("is-warning") 

        document.querySelector(".notes-assets .tag").innerHTML = lang.get("x-saved-notes", {"x": Math.abs(num_notes)})

    },

    /**
     * 
     * @param {Object} note 
     */
    "appendHTMLNote": function (ObjectNote)
    {
        const notes_box = this.notes_container.querySelector(".notes-box")

        if (notes_box.querySelector("p[no-notes]") != null)
        {
            notes_box.querySelector("p[no-notes]").remove()
        }
        this.notes_container.querySelector(".notes-box ul")
        ?.prepend(this.createHTMLNote(ObjectNote))
    },

    /**
     * 
     * @param {Note} note 
     * @returns void
     */
    "editHTMLNote": function(note)
    {
        const HTMLNote = document.querySelector("#" + note.id)
        if (HTMLNote == null) return

        HTMLNote.style.backgroundColor = note.color

        HTMLNote.querySelector("h3").style.fontFamily = note.font
        HTMLNote.querySelector("textarea").style.fontFamily = note.font

        HTMLNote.querySelector("h3").setAttribute("full-data", note.title)
        HTMLNote.querySelector("h3").innerHTML = note.title

        HTMLNote.querySelector("textarea").value = note.body
    },

    /**
     * 
     * @param {String} param0 
     * @returns HTMLElement
     */
    "createHTMLNote": ({id, title, body, font, color, src, created_at}) => 
    {
        const note = document.createElement("li")
        const note_title = document.createElement("h3")
        const note_body = document.createElement("textarea")

        const selectButton = document.createElement("button")
        const selectButtonIcon = document.createElement("i")
        const selectButtonText = document.createElement("span")

        const noteExtra = document.createElement("div")
        const noteExtraCreatedAt = document.createElement("div")
        const noteExtraSrc = document.createElement("div")

        note.id = id
        note.classList.add("trans-1")
        note.style.backgroundColor = color
        note.setAttribute("color", color)

        note_title.style.fontFamily = font
        note_title.setAttribute("full-data", title)
        note_title.innerText = title

        note_body.classList.add("textarea", "is-warning")
        note_body.style.fontFamily = font
        note_body.setAttribute("readonly", "readonly")
        note_body.value = body

        selectButton.type = "button"
        selectButton.setAttribute("class", "button is-info is-fullwidth is-light is-bold trans-1")
        selectButton.setAttribute("click-flag", "0")

        selectButtonIcon.setAttribute("class", "fa fa-check-circle is-hidden")
        selectButtonText.innerText = lang.get("select")

        noteExtra.setAttribute("class", "is-flex is-flex-direction-row is-flex-wrap-wrap is-justify-content-space-between mt-1 opacity-7")
        noteExtra.style.width = "100%"

        noteExtraCreatedAt.setAttribute("class", "tag is-black is-light is-small")
        noteExtraCreatedAt.innerText = created_at

        noteExtraSrc.setAttribute("class", "tag is-black is-light is-small")
        noteExtraSrc.innerText = src

        noteExtra.append(noteExtraCreatedAt, noteExtraSrc)
        selectButton.append(selectButtonText, selectButtonIcon)

        note.append(note_title, note_body, selectButton, noteExtra)

        return note
    },

    /**
     * 
     * @param {HTMLElement} note 
     */
    "add_note": function (note) 
    {
        this.selected.push(note)
        this.enable_note_feature_btn()
    },

    /**
     * 
     * @param {HTMLElement} note 
     */
    "remove_note": function (note) {
        let selected = []

        for (let i = 0; i < this.selected.length; i++)
        {
            if (note.id != this.selected[i].id) {
                selected.push(this.selected[i])
            }
        }
        this.selected = selected
        this.enable_note_feature_btn()
    },

    "enable_note_feature_btn": function () 
    {
        features_btns = this.notes_container.querySelectorAll(".notes-assets .notes-assets-buttons .buttons")[1]
        
        features_btns.querySelectorAll("button").forEach((btn) => 
        {
            if (this.selected.length > 0) {
                if (!(this.selected.length > 1 && btn.getAttribute("multiple-notes-feat") == "false")) {
                    btn.removeAttribute("disabled", "disabled")
                }
                else {
                    btn.setAttribute("disabled", "disabled")
                }
            }
            else {
                btn.setAttribute("disabled", "disabled")
            }
        });

        let unpressed_btn = this.notes_container.querySelectorAll(".notes-box ul li button[click-flag='0']")
        if (this.selected.length > 0)
        {
            unselect_all_notes_btn.removeAttribute("disabled")
        }
        else unselect_all_notes_btn.setAttribute("disabled", "disabled")

        if ((this.selected.length < 1 || unpressed_btn.length < 1))
        {
            select_all_notes_btn.setAttribute("disabled", "disabled")
        }
        else select_all_notes_btn.removeAttribute("disabled", "disabled")
        
    },

    /**
     * @param {Boolean} action
     * @returns {void}
     */

    "add_select_notes_event": function () {

        this.notes_container.querySelectorAll(".notes-box ul li button").forEach((button)=>
        {
            button.onclick = function () 
            {   
                if (this.getAttribute("click-flag")  == "0") 
                {
                    this.classList.remove("is-light")
                    this.setAttribute("click-flag", "1")

                    // this.querySelector("span").innerHTML = "Selected"
                    this.querySelector("span").innerHTML = lang.get("selected")
                    this.querySelector("i").classList.remove("is-hidden")

                    notes.add_note(this.parentNode)
                }
                else 
                {
                    this.classList.add("is-light")
                    this.setAttribute("click-flag", "0")

                    // this.querySelector("span").innerHTML = "Select"
                    this.querySelector("span").innerHTML = lang.get("select")
                    this.querySelector("i").classList.add("is-hidden")

                    notes.remove_note(this.parentNode)
                }

                if (notes.selected.length == 0)
                    notes.setNumNotes()
                else
                    notes.setSelectedNotes()
            }
        })
    },

    "selectAll": function () 
    {
        let button
        let notes = Array.from(this.notes_container.querySelectorAll('.notes-box ul li'))
        .filter((note)=> {
            return ! (note.classList.contains("is-hidden"))
            && note.querySelector('button')?.getAttribute('click-flag') == '0'
        })
        
        notes.forEach((note)=> 
        {
            button = note.querySelector("button")

            if (button != null && button.getAttribute("click-flag") == "0") 
            button.onclick()
        })
    },

    "unselectAll": function () 
    {
        this.selected.forEach((el)=> {
            el.querySelector("button")?.onclick()
        })
    },

    /**
     * 
     * @param {HTMLElement} note 
     * @returns {void}
     */
    "set_extended_note_data": function (note) {
        let note_body = note.querySelector("textarea").innerHTML

        this.extended_note.querySelector("form").style.backgroundColor = note.style.backgroundColor

        this.extended_note.querySelector("input").value = note.querySelector("h3").getAttribute("full-data")
        this.extended_note.querySelector("input").setAttribute("current-note-title", note.querySelector("h3").getAttribute("full-data"))
        this.extended_note.querySelector("input").style.fontFamily = note.querySelector("h3").style.fontFamily

        this.extended_note.querySelector("textarea").innerHTML = note_body
        this.extended_note.querySelector("textarea").setAttribute("current-note-body", note.querySelector("textarea").innerHTML)
        this.extended_note.querySelector("textarea").style.fontFamily = note.querySelector("textarea").style.fontFamily

        this.extended_note.classList.remove("hide")
    },

    "clear_ext_note_data": function () {
        this.extended_note.querySelector("input").value = ""
        this.extended_note.querySelector("input").setAttribute("current-note-title", "")

        this.extended_note.querySelector("textarea").innerHTML = ""
        this.extended_note.querySelector("textarea").setAttribute("current-note-body", "")
    }
}

notes.extended_note_feat.loader.enable()
notes.extended_note_feat.loader.disable()
notes.extended_note_feat.alert.setGet()

const csrf_input = document.querySelector("#csrf_input")
const exit_extended_note_btn = document.querySelector("#exit-extended-note")
const edit_extended_note_btn = document.querySelector("#edit-extended-note")
const update_note_btns = document.querySelector("#update-note-btns")

const cancel_update_btn = update_note_btns.querySelectorAll("button")[0]
const save_update_btn = update_note_btns.querySelectorAll("button")[1]
const notes_aesthetics = document.querySelector("#notes_aesthetics")

const extended_note_color_input = document.querySelector("#extended_note_color")
const extended_note_font_input = document.querySelector("#extended_note_font")

const set_color_btn = notes_aesthetics.querySelectorAll("button")[0]
const set_font_btn = notes_aesthetics.querySelectorAll("button")[1]

const select_all_notes_btn = document.querySelector("#select-all-notes")
const unselect_all_notes_btn = document.querySelector("#unselect-all-notes")

let set_font_counter = 0
let set_color_counter = 0

select_all_notes_btn.onclick = ()=> notes.selectAll()
unselect_all_notes_btn.onclick = ()=> notes.unselectAll()

set_color_btn.onclick = function (e)
    {
        e.preventDefault()
        let i

        if (set_color_counter + 1 == notes.create.colors.length)
        {
            i = set_color_counter
            set_color_counter = 0
        }
        else if (set_color_counter + 1 >= notes.create.colors.length) {
            i = 0
            set_color_counter = 0
        }
        else {
            i = set_color_counter
            set_color_counter++
        }
        extended_note_color_input.value = notes.create.colors[i]
        notes.extended_note.querySelector("form").style.backgroundColor = notes.create.colors[i]
}

set_font_btn.onclick = function (e) 
{
    e.preventDefault()
    let i

    if (set_font_counter + 1 == notes.create.fonts.length)
    {
        i = set_font_counter
        set_font_counter = 0
    }
    else if (set_font_counter + 1 >= notes.create.fonts.length) {
        i = 0
        set_font_counter = 0
    }
    else {
        i = set_font_counter
        set_font_counter++
    }
    extended_note_font_input.value = notes.create.fonts[i]
    notes.extended_note.querySelector("input").style.fontFamily = notes.create.fonts[i] + ", sans-serif"
    notes.extended_note.querySelector("textarea").style.fontFamily = notes.create.fonts[i] + ", sans-serif"
}

notes.add_select_notes_event()

/**
 * ended
 * @returns {Object}
 */
notes.extend_btn.onclick = function (e) {
    
    let selected = notes.selected[0]

    notes.extended_note_form.style.backgroundColor = selected.style.backgroundColor
    extended_note_color_input.value = selected.getAttribute("color") ?? "#f2f2f27a"

    notes.extended_note_title.setAttribute("initial-data", selected.querySelector("h3").getAttribute("full-data"))
    notes.extended_note_title.value = selected.querySelector("h3").getAttribute("full-data") ?? ""
    notes.extended_note_title.style.fontFamily = selected.querySelector("h3").style.fontFamily
    extended_note_font_input.value = selected.querySelector("h3").style.fontFamily

    notes.extended_note_body.setAttribute("initial-data", selected.querySelector("textarea").value)
    notes.extended_note_body.value = selected.querySelector("textarea").value
    notes.extended_note_body.style.fontFamily = selected.querySelector("textarea").style.fontFamily

    notes.extended_note.classList.remove("hide")

    blurred_wall.onclick = undefined
    blurred_wall.classList.add("blur-effect")
    blurred_wall.classList.remove("hide")
}   

cancel_update_btn.onclick = function() {

    update_note_btns.classList.add("hide")
    notes_aesthetics.classList.add("hide")
    edit_extended_note_btn.classList.remove("hide")

    notes.extended_note_form.setAttribute("method", "POST")
    notes.extended_note_form.setAttribute("action", "/note/create")

    notes.extended_note_form.querySelectorAll(".help").forEach((el)=> el.innerHTML = "")
    notes.extended_note.querySelector("input").setAttribute("readonly", "readonly")
    notes.extended_note.querySelector("textarea").setAttribute("readonly", "readonly")

    notes.extended_note.querySelector("input").value = notes.extended_note.querySelector("input").getAttribute("initial-data")
    notes.extended_note.querySelector("textarea").value = notes.extended_note.querySelector("textarea").getAttribute("initial-data")
}

exit_extended_note_btn.onclick = function()
{
    cancel_update_btn.onclick()

    extended_note_color_input.value = "#f2f2f27a"
    extended_note_font_input.value = "poppins"

    notes_aesthetics.classList.add("hide")
    create_new_note_btn.classList.add("hide")
    notes.extended_note.querySelector("form").style.backgroundColor = "#f2f2f27a"

    notes.extended_note.querySelector("input").setAttribute("initial-data", "")
    notes.extended_note.querySelector("input").value = ""
    notes.extended_note.querySelector("input").style.fontFamily = "poppins, sans-serif"

    notes.extended_note.querySelector("textarea").setAttribute("initial-data", "")
    notes.extended_note.querySelector("textarea").value = ""
    notes.extended_note.querySelector("textarea").style.fontFamily = "poppins, sans-serif"
    
    notes.extended_note.classList.add("hide")
    blur_background(false)
}

/**
 * ended
 */
edit_extended_note_btn.onclick = function()
{
    this.classList.add("hide")
    update_note_btns.classList.remove("hide")

    notes_aesthetics.classList.remove("hide")
    notes.extended_note.querySelector("form").setAttribute("method", "POST")
    notes.extended_note.querySelector("form").setAttribute("action", "/notes/update")

    notes.extended_note.querySelector("input").removeAttribute("readonly")
    notes.extended_note.querySelector("textarea").removeAttribute("readonly")
}

save_update_btn.onclick = function (e)
{
    e.preventDefault()

    let action = "/note/update"
    let selected = notes.selected[0]
    let errors
    let editedNote

    let constraints =
    {
        "note_title": { "length": { "maximum": 300 } },
        "note_body": 
        {
            "presence": { allowEmpty: false },
            "length": { "maximum": 800 }
        }
    }

    const noteToEdit = Note.fromObject({
        "id": selected.id,
        "title": notes.extended_note_title.value.trim(),
        "body": notes.extended_note_body.value.trim(),
        "color": extended_note_color_input.value.trim(),
        "font": extended_note_font_input.value.trim(),
    })

    let update_note_data = 
    {
        "csrf_name": csrf_input.value,
        "note_id": noteToEdit.id,
        "note_title": noteToEdit.title,
        "note_body": noteToEdit.body,
        "note_color": noteToEdit.color,
        "note_font": noteToEdit.font,
    }
    disable(notes.extended_note.querySelectorAll("input"))
    disable(notes.extended_note.querySelectorAll("textarea"))
    disable(notes.extended_note.querySelectorAll("button"))

    notes.extended_note_feat.alert.setGet()
    notes.extended_note_feat.loader.enable()
    errors = validate(update_note_data, constraints)

    if (errors != undefined)
    {
        for (const id in errors) 
        {
            if (Object.hasOwnProperty.call(errors, id)) 
            {
                notes.extended_note_feat.alert.set_message(errors[id])
                notes.extended_note_feat.loader.disable()

                disable(notes.extended_note.querySelectorAll("input"), false)
                disable(notes.extended_note.querySelectorAll("textarea"), false)
                disable(notes.extended_note.querySelectorAll("button"), false)
                break
            }
        }
        return
    }
    else if (noteToEdit.doesContentEqualTo(Note.getContentFromId(selected.id)))
    {
        notes.extended_note_feat.alert.set_message(lang.get("modify-b4-save"))

        disable(notes.extended_note.querySelectorAll("input"), false)
        disable(notes.extended_note.querySelectorAll("textarea"), false)
        disable(notes.extended_note.querySelectorAll("button"), false)
        notes.extended_note_feat.loader.disable()

        return
    }
    else notes.extended_note_feat.alert.clear()
    
    fetch(action, {
        method: "POST",
        cache: "no-cache",

        headers: 
        {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify(update_note_data)
    })
    .then((response)=> 
    {
        disable(notes.extended_note.querySelectorAll("input"), false)
        disable(notes.extended_note.querySelectorAll("textarea"), false)
        disable(notes.extended_note.querySelectorAll("button"), false)

        notes.extended_note_feat.loader.disable()
        notes.extended_note_feat.alert.clear()

        return response.json()
    })
    .then(async (json)=> 
    {
        csrf_input.value = json.csrf_hash
        if (json.http_code == 200)
        {
            editedNote = Note.fromObject({
                id: update_note_data.note_id,
                title: update_note_data.note_title ?? "",
                body: update_note_data.note_body,
                font: update_note_data.note_font,
                color: update_note_data.note_color,
                src: null,
                created_at: null
            })
            notes.editHTMLNote(editedNote)
            notes.unselectAll()
            
            await se.removeNotes([editedNote.id])
            await se.appendNotes([editedNote])

            exit_extended_note_btn.onclick()
            set_notification(lang.get("success-modified"))
        }
        else if (json.http_code == 400)
        {
            for (const key in json.note_errors) {
                if (Object.hasOwnProperty.call(json.note_errors, key)) 
                {
                    notes.extended_note_feat.alert.set_message(json.note_errors[key] ?? lang.get("error-occured"))
                }
            }
        }
        else if ([400, 429, 500].indexOf(json.http_code) != -1)
        {
            notes.extended_note_feat.alert.set_message((json.http_reason ?? lang.get("error-occured")) + ". " + lang.get("try-again"))
        }
    })
    .catch(()=> {
        notes.extended_note_feat.alert.set_message(lang.get("error-occured"))
        notes.extended_note_feat.loader.disable()
    })
}

/**
 * ended
 */
notes.copy_btn.addEventListener("click", function ()
{
    let text_to_copy = ""

    let note_data = 
    {
        "title": null,
        "body": null,
        "clear": function () {
            this.title = null
            this.body = null
        }
    }

    notes.selected.forEach(function (note)
    {
        note_data.title = note.querySelector("h3").getAttribute("full-data")
        note_data.body = note.querySelector("textarea").value

        text_to_copy += `${lang.get("title")}: ${note_data.title}\n\n${lang.get("body")}: ${note_data.body}\n`
        note_data.clear()
    })
    text_to_copy = text_to_copy.slice(0, text_to_copy.length - 1)

    if (navigator.clipboard != undefined) 
    {
        navigator.clipboard.writeText(text_to_copy).then(()=>{

            notes.copy_btn.querySelector("i").classList.replace("fa-copy", "fa-check-circle")
            notes.copy_btn.querySelector("span").innerHTML = lang.get("copied")

            setTimeout(function () {
                notes.copy_btn.querySelector("i").classList.replace("fa-check-circle", "fa-copy")
                notes.copy_btn.querySelector("span").innerHTML = lang.get("copy")
            }, 3000)
        })
    }
    else
    {
        set_notification(lang.get("copy-unavailable"), 3500, true)
    }
})

notes.edit_btn.addEventListener("click", function ()
{
    notes.extend_btn.onclick()
    edit_extended_note_btn.onclick()
})

notes.delete_btn.addEventListener("click", function () {
    let selected = notes.selected

    let delete_notes_window = document.querySelector("#delete-notes")
    let delete_form = delete_notes_window.querySelector("form")

    let submit_delete = document.querySelector("#submit-delete")
    let cancel_delete = document.querySelector("#cancel-delete")

    let delete_text = delete_notes_window.querySelectorAll(".field p")[1]
    let error_box = delete_notes_window.querySelector(".notification p")
    let delete_tag = delete_notes_window.querySelector(".field .tag span")

    delete_notes_window.classList.remove("hide")
    blur_background()

    delete_tag.innerHTML = `<b>${selected.length}</b>`
    delete_text.innerHTML = lang.get("sure-delete-notes", {"num": selected.length})


    cancel_delete.onclick = function () 
    {
        blur_background(false)

        submit_delete.setAttribute("disabled", "disabled")
        delete_notes_window.classList.add("hide")
        delete_tag.parentNode.classList.replace("is-danger", "is-info")
        delete_tag.innerHTML = ""
        set_error("", false)
    }

    submit_delete.onclick = function (e) 
    {
        e.preventDefault()
        disable([submit_delete, cancel_delete])
        this.classList.add("is-loading")

        let notes_id = selected.map(el => el.id)

        delete_notes_data =  
        {
            csrf_name: csrf_input.value,
            notes_id
        }

        fetch(delete_form.action, {
            method: delete_form.getAttribute("method"),
            cache: "no-cache",

            headers: 
            {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(delete_notes_data)
        })
        .then((response)=> {
            disable([submit_delete, cancel_delete], false)
            this.classList.remove("is-loading")

            return response.json()
        })
        .then((json)=> 
        {
            csrf_input.value = json.csrf_hash
            if (json.http_code == 200)
            {
                let num_notes = notes.notes_container.querySelectorAll(".notes-box ul li").length - notes.selected.length
                notes.unselectAll()
                se.removeNotes(notes_id)

                selected.forEach((el)=> 
                {
                    notes.remove_note(el)
                    el.parentNode.removeChild(el)
                })

                if (num_notes < 1)
                {
                    notes.notes_container.querySelector(".notes-box ul").innerHTML = `<p class="h3 opacity-7" no-notes> ${lang.get("no-notes")} </p>`
                }
                notes.setNumNotes()
                cancel_delete.onclick()

                set_notification(lang.get("success-deleted"))
            }
            else
            {
                set_error(json.http_reason + ". " + lang.get("try-again"))
            }
        })
    }

    if (selected.length < 1)
    {
        delete_tag.parentNode.classList.replace("is-info", "is-danger")
        set_error(lang.get("at-least-del", {"num": "01"}))
        return
    }
    else if (selected.length > DEL_NOTES_LIMIT)
    {
        delete_tag.parentNode.classList.replace("is-info", "is-danger")
        set_error(lang.get("at-most-del", {"max": DEL_NOTES_LIMIT, "picked": selected.length}))
        return
    }
    else submit_delete.removeAttribute("disabled")


    /**
     * 
     * @param {String} error The error message to show
     * @param {Boolean} flag
     * @returns {void}
     */
    function set_error(error="", flag=true) 
    {
        if (flag) 
        {
            error_box.parentNode.classList.remove("hide")
            error_box.innerHTML = error 
        }
        else 
        {
            error_box.parentNode.classList.add("hide")
            error_box.innerHTML = "" 
        }
    }
})

create_text_note_btn.onclick = function ()
{    
    let note_form_action = "/notes/create"
    let note_form_method = "POST"


    notes_aesthetics.classList.remove("hide")
    notes.extended_note_form.style.backgroundColor = "#f2f2f27a"
    notes.extended_note_form.setAttribute("action", note_form_action)
    notes.extended_note_form.setAttribute("method", note_form_method)

    create_new_note_btn.classList.remove("hide")
    blur_background()

    notes.extended_note.classList.remove("hide")
    notes.extended_note.querySelector("input").removeAttribute("readonly", "readonly")
    notes.extended_note.querySelector("textarea").removeAttribute("readonly", "readonly")

    edit_extended_note_btn.classList.add("hide")

    create_new_note_btn.onclick = function(e) 
    {
        notes.extended_note_feat.alert.setGet()

        e.preventDefault()

        let errors
        let constraints =
        {
            "note_title": { "length": { "maximum": 300 } },
            "note_body": 
            {
                "presence": { allowEmpty: false },
                "length": {
                    "maximum": 800
                }
            }
        }
        disable(notes.extended_note.querySelectorAll("input"))
        disable(notes.extended_note.querySelectorAll("textarea"))
        disable(notes.extended_note.querySelectorAll("button"))

        notes.extended_note_feat.loader.enable()

        let create_note_data =  
        {
            "csrf_name": csrf_input.value.trim(),
            "note_title": notes.extended_note_title.value.trim(),
            "note_body": notes.extended_note_body.value.trim(),
            "note_color": extended_note_color_input.value.trim(),
            "note_font": extended_note_font_input.value.trim(),
        }
        errors = validate(create_note_data, constraints)

        if (errors != undefined)
        {
            for (const id in errors) 
            {
                if (Object.hasOwnProperty.call(errors, id)) 
                {
                    notes.extended_note_feat.alert.set_message(errors[id])
                    notes.extended_note_feat.loader.disable()

                    disable(document.querySelectorAll("button"), false)
                    disable(document.querySelectorAll("input"), false)
                    disable(document.querySelectorAll("textarea"), false)
                    break
                }
            }
            return
        }
        else notes.extended_note_feat.alert.clear()

        fetch("/note/create", {
            method: note_form_method,
            cache: "no-cache",
            headers: 
            {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(create_note_data)
        })
        .then((response)=> {

            disable(notes.extended_note.querySelectorAll("input"), false)
            disable(notes.extended_note.querySelectorAll("textarea"), false)
            disable(notes.extended_note.querySelectorAll("button"), false)

            notes.extended_note_feat.loader.disable()
            return response.json()
        })
        .then((json)=> {
            csrf_input.value = json.csrf_hash

            if ([200, 201].includes(json.http_code))
            {
                notes.appendHTMLNote({
                    id: json.created_note.id,  
                    title: create_note_data.note_title,
                    body: create_note_data.note_body,
                    font: create_note_data.note_font,
                    color: create_note_data.note_color,
                    src: json.created_note.src,
                    created_at: json.created_note.created_at
                })

                se.appendNotes([{
                    id: json.created_note.id,
                    title: create_note_data.note_title,
                    body: create_note_data.note_body
                }])
                notes.add_select_notes_event()

                notes.unselectAll()
                notes.setNumNotes()
                exit_extended_note_btn.onclick()

                set_notification(lang.get("success-created"))
            }
            // 500: http Internal server error
            // 507: http insuffisent internal memory
            // 429: http too many request
            // 403: http forbidden
            // 405: http method not allowed
            // 400: http bad request
            else if ([500, 403, 405, 429, 400, 507].indexOf(json.http_code) != -1) 
            {
                notes.extended_note.querySelector(".notes-data .help p").innerHTML = json.http_reason ?? lang.get("error-occured")
            }
            else if (json.form_errors != {})
            {
                let element

                for (const id in json.note_errors) {
                    if (Object.hasOwnProperty.call(json.note_errors, id))
                    {
                        element = document.querySelector("#"+id)
                        element.parentNode.querySelector(".help").innerHTML = json.note_errors[id]
                    }
                }
            }
        })
    }
}

upload_text_note.btn.onclick = function ()
{
    let valided = false
    let upload_data = new FormData(upload_text_note.form)
    let cancel = document.querySelector("#cancel-upload")
    let error_box = upload_text_note.form.querySelector(".field .help")

    upload_text_note.get.classList.remove("hide")
    blur_background()
    
    cancel.onclick = function () 
    {
        upload_text_note.get.classList.add("hide")

        disable([upload_text_note.submit], false)
        upload_text_note.form.querySelector(".file").classList.replace("is-danger", "is-info")

        upload_text_note.form.querySelector(".file-name").innerHTML = lang.get("no-downloaded-notes")
        upload_text_note.form.querySelector("span[class='file-label']").innerHTML = lang.get("tap-to-upload")
        
        error_box.innerHTML = ""
        error_box.classList.remove("is-danger")
        error_box.parentNode.classList.add("hide")

        blur_background(false)
    }

    /**
     * 
     * ended 
     */
    upload_text_note.downloaded_notes.onchange = function(e){

        let error_box = upload_text_note.form.querySelector(".field .help")
        let files = e.target.files
        let files_name = ""


        for (let i=0;i<files.length;i++)
        {
            files_name += (files[i].name + ", ")
        }
        files_name = files_name.slice(0, -2)

        set_file_name(files_name)
        set_selected_file(files.length)

        // Check the limit of files...
        if (files.length > UPLOAD_NOTES_LIMIT) 
        {
            set_upload_danger()
            set_file_error("import-limit", {"max": UPLOAD_NOTES_LIMIT, "picked": files.length})
            disable([upload_text_note.submit])
            valided = false

            return
        }
        let extension, file_name, file_size
        
        for (let i=0;i<files.length;i++)
        {
            file_name = files[i].name
            file_size = files[i].size

            extension = get_extension(file_name)

            if (["xml", "json"].indexOf(extension.toLowerCase()) == -1)
            {
                set_upload_danger()
                set_file_error(lang.get("ext-auth", {"extension": extension, "file_name": file_name}), "text")
                disable([upload_text_note.submit])
                valided = false

                return
            }
            else if (file_size > 200*KILO)
            {
                set_upload_danger()
                set_file_error(lang.get("heavy-file", {"file_name": file_name, "max_weight": "200KB"}), "text")
                disable([upload_text_note.submit])
                valided = false

                return
            }
        }
        for (let i = 0; i < files.length; i++)
        {
            upload_data.append(`file_${i+1}`, files[i])
        }

        set_upload_danger(false)
        disable([upload_text_note.submit], false)
        
        error_box.parentNode.classList.add("hide")
        error_box.classList.remove("is-danger")
        error_box.innerHTML = ""    
        valided = true
    }

    upload_text_note.submit.onclick = function (e) 
    {
        e.preventDefault()
        
        if (valided == true) 
        {
            upload_data.append("csrf_name", csrf_input.value)
            
            disable(upload_text_note.form.querySelectorAll("button"))
            disable([upload_text_note.downloaded_notes])

            upload_text_note.submit.classList.add("is-loading")

            fetch("/note/import", 
            {
                method: "POST",
                cache: "no-cache",
    
                headers:
                {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: upload_data
            })
            .then((response)=> {
                
                disable(upload_text_note.form.querySelectorAll("button"), false)
                disable([upload_text_note.downloaded_notes], false)

                upload_text_note.submit.classList.remove("is-loading")

                if ([500, 403, 405, 507].indexOf(response.status) != -1) 
                {
                    set_file_error(lang.get("error-occured") + ". " + lang.get("try-again"))
                }
                return response.json()
            })
            .then((json)=> {
                csrf_input.value = json.csrf_hash

                if ([200, 201].includes(json.http_code))
                {
                    if (json.imported_notes != undefined)
                    {
                        json.imported_notes.forEach(note => {
                            notes.appendHTMLNote({
                                id: note.id,
                                body: note.body,
                                title: note.title ?? "",
                                font: note.font,
                                color: note.color,
                                src: note.src,
                                created_at: note.created_at
                            })
                        })
                        notes.add_select_notes_event()
                        notes.unselectAll()
                        notes.setNumNotes()
                        cancel.onclick()

                        set_notification(lang.get("success-upload"))
                    }
                    else
                        window.location.reload()
                }
                else if ([429, 409, 404, 507].indexOf(json.http_code) != -1)
                {
                    set_upload_danger()
                    set_file_error(json.http_reason ?? "")
                }
                else if (json.http_code == 400)
                {
                    set_upload_danger()
                    set_file_error(json.upload_errors)
                } 
            })
            .catch(()=>
            {
                set_upload_danger()
                set_file_error(lang.get("error-occured") + ". " + lang.get("reload-page"))
            })
        }
        else {}
    }

    /**
     * 
     * @param {String} error The error message to show to the user
     * @param {String} type The message type. "html" or "text"
     */
    function set_file_error(error, type="html")
    {
        error_box.parentNode.classList.remove("hide")
        error_box.classList.add("is-danger")
        if (type == "html") {
            error_box.innerHTML = error
        }
        else error_box.innerText = error
    }

    /**
     * 
     * @param {Boolen} flag 
     */
    function set_upload_danger(flag=true) 
    {
        if (flag == true)
            upload_text_note.form.querySelector(".file").classList.replace("is-info", "is-danger")
        else 
        upload_text_note.form.querySelector(".file").classList.replace("is-danger", "is-info")
    }
}

notes.download_btn.onclick = function () {  

    let form = download_notes.querySelector("form")
    let submit = document.querySelector("#submit-download")
    let cancel = document.querySelector("#cancel-download")

    blur_background()
    download_notes.classList.remove("hide")

    download_notes.querySelector(".tag span").innerHTML = notes.selected.length
    if (notes.selected.length > 10)
    {
        download_notes.querySelector(".tag").classList.replace("is-success", "is-danger")
        download_notes.querySelector(".notification").classList.remove("hide")
        submit.setAttribute("disabled", "disabled")
    }
    else
    {
        download_notes.querySelector(".tag").classList.replace("is-danger", "is-success")
        download_notes.querySelector(".notification").classList.add("hide")
        submit.removeAttribute("disabled")
    }

    cancel.onclick = function()
    {
        download_notes.classList.add("hide")
        blur_background(false)
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault()

        submit.classList.add("is-loading")

        disable(form.querySelectorAll("button"))
        disable(form.querySelectorAll("input[type='radio']"))

        let config = download_notes.querySelector("input[name='config']:checked")
        let ids = notes.selected.map((el)=> el.id)
        let csrf = csrf_input.value.trim()

        let action = create_action(csrf, config.value.trim().toLowerCase(), ids)
        window.location = action

        /**
         * 
         * @param {String} csrf csrf hash value
         * @param {String} config download config file: xml or json
         * @param {Array<String>} ids notes id
         * @returns {String}
         * 
         */
        function create_action(csrf, config, ids)
        {
            let action = "/note/active/download?csrf="
            let ids_str = ""

            action += (csrf + "&config=" + config + "&ids=")

            ids.forEach((id)=> ids_str += (id+","))
            ids_str = ids_str.slice(0, -1)
            action += ids_str

            return action
        }
    })
}

notes.share_btn.onclick = function () 
{
    let notes_id = notes.selected.map((el)=> el.id)
    let error_box = share_notes.form.querySelector(".notification p")
    let share_tag = share_notes.form.querySelector(".field .tag")
    let share_link = {link: "", created_at: "", expire_at: ""}

    share_notes.get.classList.remove("hide")
    share_tag.querySelector("span").innerHTML = notes_id.length
    blur_background()

    share_notes.copy.addEventListener("click", (e)=>{
        e.preventDefault()

        share_notes.input_link.select()
        document.execCommand("copy")
        share_notes.copy.innerHTML = lang.get("copied") + " <i class='fa fa-check-circle'></i>"
                
        setTimeout(() => {
            share_notes.copy.innerHTML = lang.get("copy") + " <i class='fa fa-copy'></i>"
        }, 3000);
    })

    share_notes.cancel.onclick = function ()
    {
        share_notes.get.classList.add("hide")
        error_box.innerHTML = ""
        error_box.parentNode.classList.add("hide")
        share_notes.input_link.value = ""

        disable([share_notes.submit])
        share_tag.classList.replace("is-danger", "is-info")

        blur_background(false)

        share_notes.copy.classList.add("is-hidden")
        share_notes.save.classList.add("is-hidden")
        share_notes.submit.classList.remove("is-hidden")

        if (share_link.link != "") notes.unselectAll()
    }

    share_notes.save.onclick = function (e) 
    {
        e.preventDefault()

        share_notes.cancel.onclick()
        create_text_note_btn.onclick()

        notes.extended_note_title.value = "// " + lang.get("save-share-link")
        notes.extended_note_body.value = lang.get("set-share-link", {
            "link": share_link.link,
            "created_at": share_link.created_at,
            "expire_at": share_link.expire_at
        })
    }

    share_notes.submit.onclick = function (e) 
    {
        e.preventDefault()
        disable(share_notes.form.querySelectorAll("button"))
        disable([share_notes.input_link])

        share_notes.submit.classList.add("is-loading")
        let action = create_action(csrf_input.value, notes_id)

        fetch(action, 
        {
            method: "GET",
            cache: "no-cache",
            headers:
            {
                "Accept": "application/json",
                "Content-Type": "application",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then((response)=>
        {
            disable(share_notes.form.querySelectorAll("button"), false)
            disable([share_notes.input_link], false)
            share_notes.submit.classList.remove("is-loading")

            return response.json()
        })  
        .then((json)=> {
            csrf_input.value = json.csrf_hash

            if (json.http_code == 200) 
            {
                share_notes.copy.classList.remove("is-hidden")
                share_notes.save.classList.remove("is-hidden")
                share_notes.submit.classList.add("is-hidden")

                share_link.link = json.share_link.link ?? lang.get("not-found")
                share_link.created_at = json.share_link.created_at ?? lang.get("not-found")
                share_link.expire_at = json.share_link.expire_at ?? lang.get("not-found")

                share_notes.input_link.value = share_link.link
            }
            else if ([400, 500, 405, 429].indexOf(json.http_code) != -1) 
            {
                set_error(json.http_reason + ". Try again")
            }
            else 
            {
                set_error(lang.get("error-occured"))
            }
        })
        .catch(()=>{
            set_error(lang.get("error-occured"))
        })

    }

    if (notes_id.length > SHARE_NOTES_LIMIT) 
    {
        set_error(lang.get("share-limit", {"limit": SHARE_NOTES_LIMIT, "picked": notes_id.length}))
        return
    }
    else if (notes_id.length < 1) 
    {
        set_error(lang.get("at-least-share", {"num": "01"}))
        return
    }
    disable([share_notes.submit], false)

    /**
     * 
     * @param {String} error The error message to show to the user
     * @param {String} type The message type. "html" or "text"
     */
    function set_error(error, type="html")
    {
        error_box.parentNode.classList.remove("hide")
        if (type == "html") {
            error_box.innerHTML = error
        }
        else error_box.innerText = error

        share_tag.classList.replace("is-info", "is-danger")
    }

    /**
     * 
     * @param {String} csrf csrf hash value
     * @param {String[]} ids notes id
     * @returns {String}
     * 
     */
    function create_action(csrf, ids)
    {
        let action = "/note/share/get-link?csrf="
        let ids_str = ""

        action += `${csrf}&ids=`

        ids.forEach((id)=> ids_str += (id+","))
        ids_str = ids_str.slice(0, -1)
        action += ids_str

        return action
    }
}

/**
 * 
 * @param {Boolean} flag 
 * @returns {void}
 */
function blur_background(flag=true) 
{
    if (flag == true)
    {
        create_note_btn.parentNode.style.zIndex = ""
        create_note_btn.querySelector("i").classList.replace("fa-close", "fa-plus")
        create_note_btn.setAttribute("is-active", "false")

        create_text_note_btn.style.transform = "translateY(0px)";
        upload_text_note.btn.style.transform = "translate(0px)"

        blurred_wall.classList.remove("hide")
        blurred_wall.classList.add("blur-effect")
        blurred_wall.onclick = undefined
    }
    else 
    {
        blurred_wall.classList.add("hide")
        blurred_wall.classList.remove("blur-effect")
        blurred_wall.classList.add("hide")
    }
}

/**
 * 
 * @param {String} file_name 
 */
function set_file_name(file_name)
{
    upload_text_note.form.querySelector(".file-name").innerHTML = file_name
}

/**
 * 
 * @param {String} file_name The file name
 * @returns {String} The extension of a given file
 */
function get_extension(file_name)
{
    let extendion = file_name.slice(file_name.lastIndexOf(".")+1)
    return extendion
}

/**
 * 
 * @param {Number} number The number of the selected files by the user 
 */
function set_selected_file(number)
{
    upload_text_note.form.querySelector("span[class='file-label']").innerHTML = `${number} file(s) selected.`
}

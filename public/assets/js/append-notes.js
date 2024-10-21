document.addEventListener("DOMContentLoaded", ()=> {
    let notesObject

    fetch("/note/get", {
        method: "GET",
        cache: "no-cache",
        headers:
        {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => response.json())
    .then(json => {
        if (json.http_code == 200)
            notesObject = json.notes
        else if (json.http_code == 429)
            notesObject = json.old_notes
        
        if (notesObject.length == 0)
        {
            document.querySelector("p[no-notes]").innerHTML = lang.get("no-notes")
        }
        else
        {
            notesObject.reverse()
            for (const i in notesObject)
            {
                if (Object.hasOwnProperty.call(notesObject, i)) 
                {
                    notes.appendHTMLNote(notesObject[i])
                }
            }
            notes.setNumNotes()
        }
    })
    .then(()=> 
    {
        // To populate the search system index
        se.appendNotes(searchEngine.get_notes_data())
        notes.add_select_notes_event()
    })
})
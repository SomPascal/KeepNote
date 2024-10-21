const search_input = document.querySelector("#search_notes")

let se = new searchEngine(searchEngine.get_notes_data())
let term, previousTerm = ""
let results, all_notes

search_input.addEventListener("keyup", function (e) 
{
    e.preventDefault()

    term = e.target.value.trim().toLowerCase()
    all_notes = Array.from(document.querySelectorAll(".main .notes-container .notes-box ul li"))

    if (term == "")
    {
        show_all_notes()
        return
    }

    results = se.search(term)
    all_notes.forEach((note)=>
    {
        if (results.indexOf(note.id) == -1) note.classList.add("is-hidden")
        else note.classList.remove("is-hidden")
    })
    previousTerm = term

    function show_all_notes()
    {
        all_notes.forEach((note)=> note.classList.remove("is-hidden"))
    }
})
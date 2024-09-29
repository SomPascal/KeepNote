class searchEngine
{
    /**
     * @var {RegExp} 
     */
    text_pattern = new RegExp("^[a-zA-Z0-9 ]{1,}$")

    /**
     * @var {Object<Array, Array>}
     */
    index = {};

    /**
     * 
     * @param {Array} notes An array of notes to index ["id": "", "title": "", "body": ""]
     */
    constructor(notes) 
    {
        this.appendNotes(notes)
    }

    /**
     * 
     * @param {String} text 
     * @returns {String}
     */
    sanitize(text)
    {
        let res = ""
        for (let i = 0; i < text.length; i++) 
        {
            let char = text[i];
            if (this.text_pattern.test(char)) res += text[i]
            else res += " "
        }
        return res
    }

    /**
     * 
     * @param {Array} notes An array of notes to index
     * @returns {void}
     */
    appendNotes(notes)
    {
        let words, content = ""

        notes.forEach((note) => 
        {
            content = note.title.toLowerCase().trim() + " " + note.body.toLowerCase().trim()
            words = content.split(" ").filter((word) => word.trim() != "")
            
            words.forEach((word)=> 
            {
                if (this.index[word] == undefined)
                {
                    this.index[word] = [note.id]
                }
                else if (this.index[word].indexOf(note.id) == -1) 
                {
                    this.index[word].push(note.id)
                }
            })
            content = ""
        })
    }

    /**
     * 
     * @param {Array} notes_id An array of notes to index
     * @returns {void}
     */
    removeNotes(notes_id)
    {
        for (const word in this.index) 
        {
            if (Object.hasOwnProperty.call(this.index, word)) 
            {
                notes_id.forEach(note_id => 
                {
                    if (this.index[word].includes(note_id))
                    {
                        this.index[word] = this.index[word].filter(id => id != note_id)
                        if (this.index[word] == []) delete this.index[word]
                    }
                })
            }
        }
    }

    /**
     * 
     * @param {String} term The term to find into the index
     * @returns {Array<String, String>}
     */
    search (terms)
    {
        let results = Array.from(new Set())

        for (const word in this.index) 
        {
            if (Object.hasOwnProperty.call(this.index, word)) 
            {
                terms.split(" ")
                .filter((el) => el.trim() != "")
                .forEach((term)=> 
                {
                    if (word.indexOf(term) != -1)
                    {
                        results = Array.from(new Set(results.concat(this.index[word])))
                    }
                })
            }
        }
        return results
    }

    /**
     * 
     * @returns {Array<Object, Object>}
     */
    static get_notes_data()
    {
        let notes_data = []
        let notes = document.querySelectorAll(".main .notes-container ul li")

        if (notes == []) return

        notes.forEach((note) => 
        {
            notes_data.push({
                id: note.getAttribute("id"),
                title: note.querySelector("h3").getAttribute("full-data"),
                body: note.querySelector("textarea").value
            })
        })
        return notes_data
    }
}
class Note 
{
    /**
     * 
     * @param {String} id 
     * @param {String} title
     * @param {String} body
     * @param {String} font
     * @param {String} color
     * @param {String} created_at 
     */
    constructor(id, title, body, font, color, src, created_at)
    {
        this.id = id
        this.title = title ?? ""
        this.body = body
        this.font = font ?? "poppins"
        this.color = color ?? "#f2f2f27a"
        this.created_at = created_at

        this.setHTMLNote()
    }

    /**
     * 
     * @param {String} id 
     * @returns Object
     */
    static getContentFromId(id)
    {
        let content = {}
        const HTMLNote = document.querySelector("#" + id)

        if (HTMLNote == null) return {}

        content["title"] = HTMLNote.querySelector("h3").getAttribute("full-data")
        content["body"] = HTMLNote.querySelector("textarea").value
        content["font"] = HTMLNote.querySelector("h3").style.fontFamily
        content["color"] = HTMLNote.getAttribute("color") 

        return content
    }

    /**
     * 
     * @param {Object} note 
     * @returns Boolean
     */
    doesContentEqualTo(noteContent)
    {
        return this.title == noteContent.title &&
        this.body == noteContent.body &&
        this.font == noteContent.font &&
        this.color == noteContent.color
    }

    /**
     * 
     * @param {String} param0 
     * @returns void
     */
    setHTMLNote()
    {
        const HTMLNote = document.createElement("li")
        const HTMLTitle = document.createElement("h3")

        HTMLNote.id = this.id   
        HTMLNote.classList.add("trans-1")
        HTMLNote.style.backgroundColor = this.color

        HTMLTitle.style.fontFamily = this.font
        HTMLTitle.setAttribute("full-data", this.title)
        HTMLTitle.innerText = this.title
    }

    /**
     * 
     * @returns Note
     */
    static fromObject({id, title, body, font, color, src, created_at})
    {
        return new Note(
            id, 
            title,
            body,
            font,
            color,
            src,
            created_at
        )
    }
}
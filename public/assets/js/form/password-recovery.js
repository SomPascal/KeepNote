document.addEventListener("DOMContentLoaded", ()=> 
{
    enable_delete_btns()
    const recove_password_form = document.querySelector("#recove_password_form")

    const constraints = 
    {
        "the question": 
        {
            presence: { allowEmpty: false },
            length: 
            {
                minimum: 3,
                maximum: 350
            }
        },
        "the answer": 
        {
            presence: { allowEmpty: false },
            length: 
            {
                minimum: 3, 
                maximum: 150
            }
        }
    }

    const csrf_input = document.querySelector("#csrf_input")
    const question = document.querySelector("#question")
    const answer = document.querySelector("#answer")
    const submit = document.querySelector("#submit")
    const skip = document.querySelector("#another_sign_btn")

    submit.setAttribute("type", "submit")
    question.setAttribute("value", randString())
    answer.setAttribute("value", randString())

    skip.onclick = () => 
    {
        currentUrl = new URLSearchParams(window.location.search)

        if (
            currentUrl.has("nextPage") && 
            (new URL(currentUrl.get("nextPage"))).hostname == window.location.hostname
        )
        {
            window.location = currentUrl.get("nextPage")
        }
        else window.location = "/"
    }
    answer.addEventListener("blur", e => {

        let error = validate({
            "the answer": e.target.value.trim()
        }, { "the answer": constraints["the answer"] })

        if (error == undefined)
        {
            e.target.classList.replace("is-danger", "is-warning")
            e.target.parentNode.parentNode.querySelector(".help").innerHTML = ""
        }
        else
        {
            e.target.classList.replace("is-warning", "is-danger")
            e.target.parentNode.parentNode.querySelector(".help").innerHTML = error["the answer"]
        }
    })

    question.addEventListener("blur", e => {

        let error = validate({
            "the question": e.target.value.trim()
        }, { "the question": constraints["the question"] })

        if (error == undefined)
        {
            e.target.classList.replace("is-danger", "is-warning")
            e.target.parentNode.parentNode.querySelector(".help").innerHTML = ""
        }
        else
        {
            e.target.classList.replace("is-warning", "is-danger")
            e.target.parentNode.parentNode.querySelector(".help").innerHTML = error["the question"]
        }
    })

    recove_password_form.addEventListener("submit", e => {
        e.preventDefault()

        const data = 
        {
            csrf_name: csrf_input.value.trim(),
            answer: answer.value.trim(),
            question: question.value.trim()
        }
        disable(recove_password_form.querySelectorAll("input"))
        disable(recove_password_form.querySelectorAll("button"))
        submit.classList.add("is-loading")
        
        fetch(recove_password_form.getAttribute("action"), {
            method: recove_password_form.getAttribute("method") ?? "POST",
            cache: "no-cache",

            headers: 
            {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(data)
        })
        .then(response => 
        {
            disable(recove_password_form.querySelectorAll("input"), false)
            disable(recove_password_form.querySelectorAll("button"), false)
            submit.classList.remove("is-loading")

            return response.json()
        })
        .then(json => 
        {
            let success_code = [200, 201]
            let error_code = [400, 401, 429, 410]
            let server_error_code = [500]
            let target
            let currentUrl

            if (success_code.includes(json.status))
            {
                if (json.csrf_hash != undefined)
                {
                    csrf_input.value = json.csrf_hash
                }
                currentUrl = new URLSearchParams(window.location.search)

                if (
                    currentUrl.has("nextPage") && 
                    (new URL(currentUrl.get("nextPage"))).hostname == window.location.hostname
                )
                {
                    window.location = currentUrl.get("nextPage")
                }
                else window.location = json.redirectTo
            }
            else if (error_code.includes(json.status))
            {
                if (json.status == 400)
                {
                    csrf_input.value = json.csrf_hash

                    for (const index in json.errors) 
                    {
                        if (Object.hasOwnProperty.call(json.errors, index))
                        {
                            target = document.querySelector("#"+index)
                            if (target != null)
                            {
                                target.classList.replace("is-warning", "is-danger")
                                target.parentNode.parentNode.querySelector(".help")
                                .innerHTML = json.errors[index]
                            }
                            
                        }
                    }
                }
                else if (json.status == 429)
                {
                    csrf_input.value = json.messages.csrf_hash
                    set_error(json.messages.message ?? "Too many requests. Try later in a few seconds")
                }
                // Conflict
                else if (json.status == 409)
                {
                    window.location = "/"
                }
            }
            else if (server_error_code.includes(json.status))
            {
                if (json.status == 500)
                {
                    csrf_input.value = json.messages.csrf_hash
                    set_error("An unexpected internal error occured")
                }
            }
        })
        .catch(() => {
            set_error("An unexpected error occured, please retry the page.")
        })
    })

    const set_error = (message) => 
    {
        recove_password_form.querySelector(".notification").classList.remove("hide")
        recove_password_form.querySelector(".notification p").innerHTML = message
    }
})
document.addEventListener("DOMContentLoaded", ()=>{

    enable_delete_btns()
    const signin_form = document.querySelector("#signin_form")

    const csrf_input = document.querySelector("#csrf_input")
    const username_input = document.querySelector("#username")
    const password_input = document.querySelector("#password")

    const submit_btn = document.querySelector("#submit")
    submit_btn.type = "submit"

    const another_sign_btn = document.querySelector("#another_sign_btn")

    another_sign_btn.onclick = () => window.location = exitUrl("/signup")

    username_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "username")
    })
    password_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "password")
    })

    signin_form.addEventListener("submit", (form_event)=>
    {
        form_event.preventDefault()

        let values = {}
        let inputs = form_event.target.querySelectorAll("input")

        inputs.forEach((input) => {
            values[input.id] = input.value.trim()
        })
        let validation_res = set_form_errors(values)

        if (validation_res == true) 
        {
            let method = signin_form.getAttribute("method") ?? "POST"
            let action = signin_form.getAttribute("action")

            let signin_data =
            {
                csrf_name: csrf_input.value.trim(),
                username: username_input.value.trim(),
                password: password_input.value.trim(),
            }

            disable(inputs)
            disable([submit_btn, another_sign_btn])
            submit_btn.classList.add("is-loading")

            fetch(action, {
                method: method,
                cache: "no-cache",

                headers:
                {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(signin_data)
            })
            .then((response)=> {

                disable(inputs, false)
                disable([submit_btn, another_sign_btn], false)
                submit_btn.classList.remove("is-loading")

                password_input.value = ""
                return response.json()
            })
            .then((json)=> {
                csrf_input.value = json.csrf_hash
                
                if (json.http_code == 200) 
                {
                    currentUrl = new URLSearchParams(window.location.search)

                    if (
                        currentUrl.has("nextPage") && 
                        (new URL(currentUrl.get("nextPage"))).hostname == window.location.hostname
                    )
                    {
                        window.location = currentUrl.get("nextPage")
                    }
                    else window.location = json.redirectTo ?? "/"
                    return
                }

                /**
                 * 403: HTTP_FORBIDDEN
                 * 500: HTTP_INTERNAL_SERVER_ERROR
                 * 405: HTTP_METHOD_NOT_ALLOWED
                 * 429: HTTP_TOO_MANY_REQUEST
                 */
                else if ([403, 500, 405, 429].includes(json.http_code)) 
                {
                    set_error(json.http_reason)
                }
                else if (json.http_code == 400 && json.form_errors != {}) 
                {
                    let input

                    for (const id in json.form_errors)
                    {
                        if (Object.hasOwnProperty.call(json.form_errors, id)) 
                        {
                            input = document.querySelector("#"+id)
                            input.classList.replace("is-success", "is-danger")
                            input.parentNode.parentNode.querySelector(".help").innerHTML = json.form_errors[id]
                        }
                    }
                }
                else if (json.http_code == 401)
                {
                    csrf_input.value = json.csrf_hash
                    set_error(json.message ?? lang.get("error-occured") + ". " + lang.get("reload-page") + " !")
                }
            })
            .catch(()=> {
                set_error(lang.get("error-occured") + ". " + lang.get("reload-page") + "!")
            })
        }
    })

    /**
     * 
     * @param {String} message 
     */
    const set_error = (message) => 
    {
        signin_form.querySelector(".notification").classList.remove("hide")
        signin_form.querySelector(".notification p").innerHTML = message
    }
})
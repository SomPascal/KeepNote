document.addEventListener("DOMContentLoaded", ()=>{

    enable_delete_btns()
    const signup_form = document.querySelector("#signup_form")

    const username_input = document.querySelector("#username")
    const password_input = document.querySelector("#password")

    const password_confirm_input = document.querySelector("#password_confirmation")
    const csrf_input = document.querySelector("#csrf_input")

    const agree_checkbox = document.querySelector("#agree_terms")
    const rememberMe = document.querySelector("#remember_me")

    const submit_btn = document.querySelector("#submit")
    submit_btn.type = "submit"

    const another_sign_btn = document.querySelector("#another_sign_btn")

    another_sign_btn.onclick = () => window.location = exitUrl("/signin")

    username_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "username")
    })

    password_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "password")
    })

    password_confirm_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "password_confirmation", "password")
    })

    agree_checkbox.addEventListener("change", (e)=>{
        if (e.target.checked == true) {
            submit_btn.removeAttribute("disabled")
        }
        else {
            submit_btn.setAttribute("disabled", "disabled")
        }
    })

    signup_form.addEventListener("submit", (form_event) =>
    {
        form_event.preventDefault()

        if (agree_checkbox.checked == false) 
        {
            signup_form.querySelector(".notification").classList.remove("is-hidden")
            signup_form.querySelector(".notification p").innerHTML = lang.get("agree-t-o-u")
            return
        }

        let values = {}
        let inputs = form_event.target.querySelectorAll("input")

        inputs.forEach((input) => {
            if (input.type != "checkbox" && input.type != "hidden") {
                values[input.id] = input.value.trim()
            }
        })
        
        if (set_form_errors(values) == true)
        {
            disable(inputs)
            disable([
                agree_checkbox,
                submit_btn,
                another_sign_btn
            ])
            submit_btn.classList.add("is-loading")

            // Ajax code here...

            let method = signup_form.getAttribute("method") ?? "POST"
            let action = signup_form.getAttribute("action")

            signup_data = 
            {
                agree_terms: (agree_checkbox.checked == true) ? "true":"false",
                remember_me: rememberMe.checked,
                csrf_name: csrf_input.value.trim(),

                username: username_input.value.trim(),
                password: password_input.value.trim(),
                password_confirmation: password_confirm_input.value.trim()
            }

            fetch(action, {
                method: method,
                caches: "no-cache",

                headers:
                {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(signup_data)
            })
            .then((response)=> {
                disable(inputs, false)
                disable([
                    agree_checkbox,
                    submit_btn,
                    another_sign_btn
                ], false)
                submit_btn.classList.remove("is-loading")

                password_confirm_input.value = ""
                password_input.value = ""
                
                return response.json()
            })
            .then((json)=> {

                csrf_input.value = json.csrf_hash

                // debugger
                if ([200, 201].includes(json.http_code)) 
                {   
                    currentUrl = new URLSearchParams(window.location.search)

                    if (
                        currentUrl.has("nextPage") && 
                        (new URL(currentUrl.get("nextPage"))).hostname == window.location.hostname
                    )
                    {
                        window.location = currentUrl.get("nextPage")
                    }
                    else 
                    {
                        console.log(json.redirectTo);
                        window.location = json.redirectTo ?? "/"
                    }
                    return
                }
                else if ([403, 500, 405, 429].includes(json.http_code))
                {
                    set_error(json.http_reason)
                }
                else if (json.http_code == 400 && json.form_errors != {}) 
                {
                    for (const id in json.form_errors)
                    {
                        if (Object.hasOwnProperty.call(json.form_errors, id)) 
                        {
                            let input = document.querySelector("#"+id)
                            input.classList.replace("is-success", "is-danger")
                            input.parentNode.parentNode.querySelector(".help").innerHTML = json.form_errors[id]
                        }
                    }
                }
            })
            .catch(()=> {
                set_error(lang.get("error-occured") + ". " + lang.get("reload-page") +  " !")
            })
        }
    })

    const set_error = (message) => 
    {
        signup_form.querySelector(".notification").classList.remove("hide")
        signup_form.querySelector(".notification p").innerHTML = message
    }
})
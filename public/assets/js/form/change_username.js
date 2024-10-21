document.addEventListener("DOMContentLoaded", ()=>{
    
    enable_delete_btns()
    const change_username_form = document.querySelector("#change_username_form")
    const new_username_input = document.querySelector("#new_username")
    const password = document.querySelector("#password")
    const csrf_input = document.querySelector("#csrf_input")
    
    const submit_btn = document.querySelector("#submit")
    const another_sign_btn = document.querySelector("#another_sign_btn")
    submit_btn.type = "submit"

    new_username_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "username")
    })

    password.addEventListener("blur", (e)=>{
        set_form_error(e.target, "password")
    })

    change_username_form.addEventListener("submit", (form_event)=>{
        form_event.preventDefault()

        let values = {}
        let inputs = form_event.target.querySelectorAll("input")

        inputs.forEach((input) => {
            if (input.type != "hidden") {
                values[input.id] = input.value.trim()
            }
        })

        if (set_form_errors(values) == true)
        {
            disable(inputs)
            disable([
                new_username_input,
                password,
                submit_btn,
                another_sign_btn
            ])
            submit_btn.classList.add("is-loading")

            // ajax

            let method = change_username_form.getAttribute("method") ?? "POST"
            let action = change_username_form.getAttribute("action")

            let change_username_data = {
                csrf_name: csrf_input.value.trim(),

                new_username: new_username_input.value.trim(),
                password: password.value.trim(),
            }

            fetch(action, {
                method: method,
                cache: "no-cache",

                headers: 
                {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest" 
                },
                body: JSON.stringify(change_username_data)
            })
            .then((Response)=> Response.json())
            .then((json)=> 
            {
                csrf_input.value = json.csrf_hash

                if (json.http_code == 200)
                {
                    window.location = json.redirectTo ?? "/"
                }
                else if ([403, 500, 405, 429].indexOf(json.http_code) != -1) 
                {
                    change_username_form.querySelector(".notification").classList.remove("hide")
                    change_username_form.querySelector(".notification p").innerHTML = json.http_reason

                }
                else if (json.form_errors != {})
                {
                    console.log(json.form_errors)
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
                disable(inputs, false)
                disable([
                    submit_btn,
                    another_sign_btn
                ], false)
                submit_btn.classList.remove("is-loading")
            })
        }
    })
})
document.addEventListener("DOMContentLoaded", ()=>{
    
    enable_delete_btns()
    const change_pass_form = document.querySelector("#change_password_form")

    const csrf_input = document.querySelector("#csrf_input")
    const current_pass_input = document.querySelector("#current_password")
    const new_pass_input = document.querySelector("#new_password")
    const new_pass_input_confirm = document.querySelector("#new_password_confirmation")
    
    const submit_btn = document.querySelector("#submit")
    const another_sign_btn = document.querySelector("#another_sign_btn")
    submit_btn.type = "submit"

    current_pass_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "current_password")
    })

    new_pass_input.addEventListener("blur", (e)=>{
        set_form_error(e.target, "new_password")
    })

    new_pass_input_confirm.addEventListener("blur", (e)=>{
        set_form_error(e.target, "", "new_password")
    })
    
    
    change_pass_form.onsubmit = (e)=> {
        e.preventDefault()
        
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
                current_pass_input,
                new_pass_input,
                new_pass_input_confirm,
                submit_btn,
                another_sign_btn
            ])
            submit_btn.classList.add("is-loading")

            let method = change_pass_form.getAttribute("method") ?? "POST"
            let action = change_pass_form.getAttribute("action")

            let change_pass_data = {
                csrf_name: csrf_input.value,

                current_password: current_pass_input.value.trim(),
                password: new_pass_input.value.trim(),
                password_confirmation: new_pass_input_confirm.value.trim()
            }

            fetch(action, {
                cache: "no-cache",
                method: method,

                headers: 
                {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(change_pass_data)
            })
            .then((response)=> response.json())
            .then((json)=>
            {
                csrf_input.value = json.csrf_hash

                if (json.http_code == 200)
                {
                    window.location = json.redirectTo ?? "/"
                }

                else if ([403, 405, 500, 429].indexOf(json.http_code) != -1) 
                {
                    change_pass_form.querySelector(".notification").classList.remove("hide")
                    change_pass_form.querySelector(".notification p").innerHTML = json.http_reason
                }
                else if (json.form_errors != {})
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
                disable(inputs, false)
                disable([
                    submit_btn,
                    another_sign_btn
                ], false)
                submit_btn.classList.remove("is-loading")  
            })
        }
    }
    

})
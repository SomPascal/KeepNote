/**
 * 
 * @param {Object} values 
 * @param {Object} options
 */
function is_valid(values, options)
{

    let default_constraints = {
        username: {
            presence: true,

            exclusion: {
                within: ["sex", "xxx", "fuck", "change-password", "change-username"],
                message: "^'%{value}' is not allowed as username. Choose another one."
            },
            length: {
                maximum: 24,
                minimum: 3,
                message: "must be between 3 and 24 characters"
            },
            format: {
                pattern: new RegExp("^[a-zA-Z][a-zA-Z0-9_ ]{2,23}$"),
                message: "^Enter a valid username"
            }
        },
        password: {
            presence: true,
            length: {
                minimum: 6,
                maximum: 24,
                message: "must be between 6 and 24 characters"
            }
        },
        new_password: {
            presence: true,
            length: {
                minimum: 6,
                maximum: 24,
                message: "must be between 6 and 24 characters"
            }
        },
        password_confirmation: {
            presence: true,
            length: {
                minimum: 6,
                maximum: 24,
                message: "must be between 6 and 24 characters"
            },
            equality: "password"
        }
    }
    let constraints = {}

    for (const key in values) {
        if (Object.hasOwnProperty.call(values, key)) {
            constraints[key] = default_constraints[key]
        }
    }
    validate.validators.equality.message = "must be equal to the password"
    return validate(values, constraints, options)
}

/**
 * 
 * @param {HTMLInputElement} input,
 * @param {String} input_id1
 * @param {String} input_id2
 * 
 * @returns {Boolean}
 */
function set_form_error(input, input_id1, input_id2= "") {
    
    let values = {}
    values[input_id1] = (input.value ?? "").trim()

    if (input_id2 != "") {
        values[input_id2] = document.querySelector("#"+input_id2).value.trim()
    }

    let validation_res = is_valid(values)
    input.classList.replace("is-warning", "is-danger")

    if (validation_res === undefined) 
    {
        input.classList.replace("is-danger", "is-success")
        input.parentNode.parentNode.querySelector(".help").innerText = ""

        return true
    }
    else 
    {
        input.classList.replace("is-success", "is-danger")
        input.parentNode.parentNode.querySelector(".help").innerText = validation_res[input_id1][0]

        return false
    }
}

/**
 * 
 * @param {Object} inputs 
 * @param {String} withOut
 * @returns {Boolean}
 */
function set_form_errors(inputs, withOut="") 
{
    for (const id in inputs) {
        if (Object.hasOwnProperty.call(inputs, id) && id != withOut) {
            document.querySelector("#"+id).classList.replace("is-warning", "is-success")   
        }
    }
    let validation_res = is_valid(inputs)

    if (validation_res == undefined) 
    {
        for (const id in inputs) {
            if (Object.hasOwnProperty.call(inputs, id) && id != withOut) {
                const input = document.querySelector("#"+id)

                input.classList.replace("is-danger", "is-success")
                input.parentNode.parentNode.querySelector(".help").innerText = ""
            }
        }
        return true
    }
    else 
    {
        let input
        for (const id in validation_res) 
        {
            if (Object.hasOwnProperty.call(validation_res, id) && id != withOut) 
            {
                
                input = document.querySelector("#"+id)
                input.classList.replace("is-success", "is-danger")
                input.parentNode.parentNode.querySelector(".help").innerHTML = validation_res[id][0]
            }
        }
        return false
    }
}

/**
 * 
 * @param {NodeList} elements 
 * @param {Boolean} flag 
 * 
 * @description Disable or remove the "disabled" of all HTML elements passed as first argument
 */
function disable(elements, flag=true) 
{
    elements.forEach((el)=> {
        if (flag == true) el.setAttribute("disabled", "disabled")
        else el.removeAttribute("disabled")
    })
}

/**
 * @param {String} action
 * @description Enable the Bulma's delete btn of the notification block. If action is equal to hide 
 * it will hide the element, if action is equal to remove, it will remove the element...
 */
function enable_delete_btns(action="hide") 
{
    document.querySelectorAll(".notification .delete").forEach((delete_btn)=> {
        delete_btn.onclick = function() 
        {
            let notification = this.parentNode
            if (action == "hide")
                notification.classList.add("hide")
            else if(action == "remove")
                notification.parentNode.removeChild(notification)
            else
                throw new Error("Unknow action. The action should be: hide or remove...")
        }
    })
}

/**
 * 
 * @param {String} path
 */
const exitUrl = (path) => 
{
    let queryUrl = new URLSearchParams(window.location.search)
    let url = new URLSearchParams("?")
    let signupUrl = window.location.origin + path

    if (queryUrl.has("nextPage"))
    {
        url.append("nextPage", queryUrl.get("nextPage"))
        signupUrl += "/?" + url.toString()
    }
    return signupUrl
}
document.addEventListener("DOMContentLoaded", (e)=>{
    const navbar_burger = document.querySelector("#navbar_burger")

    if (navbar_burger != null)
    {
        navbar_burger.addEventListener("click", (e)=>{
            e.target.classList.toggle("is-active")
            document.querySelector(".navbar-menu").classList.toggle("is-active")
        })    
    }
})
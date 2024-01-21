let loginForm = document.querySelector(".login");
let errorContainer = document.querySelector(".error-holder-js");
let errorContainer2 = document.querySelector(".error-holder");
const emailRegex = /^[^\s@]+@[^\s@]+.[^\s@]+$/;
loginForm.addEventListener("submit", (e) => {
    clearErrors();

    const email = e.target.email?.value.trim();
    const password = e.target.password?.value.trim();

    var errors = [];

    if(!emailRegex.test(email)) {
        errors.push("Email doesn't meet the requirements");
    }

    if(password.length <= 7) {
        errors.push("Password should be longer than 7 characters");
    }

    if (errors.length > 0) {
        e.preventDefault();
        displayErrors(errors);
        errorContainer2.innerHTML = '';
        return false;
    }

    return true;
});

function clearErrors() {
    errorContainer.innerHTML = "";
}

function displayErrors(errors) {
    errors.forEach((error) => {
        const errorParagraph = document.createElement("p");
        errorParagraph.classList.add('error-message');
        errorParagraph.textContent = error;
        errorContainer.appendChild(errorParagraph);
    })
}
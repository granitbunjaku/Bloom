let registerForm = document.querySelector(".register");
let errorContainer = document.querySelector(".error-holder-js");
let errorContainer2 = document.querySelector(".error-holder");
const emailRegex = /^[^\s@]+@[^\s@]+.[^\s@]+$/;
registerForm.addEventListener("submit", (e) => {
    clearErrors();

    const name = e.target.fullname?.value.trim();
    const email = e.target.email?.value.trim();
    const password = e.target.password?.value.trim();
    const confirmPassword = e.target.confirmPassword?.value.trim();
    const gender = e.target.gender?.value.trim();

    var errors = [];

    if(name.length < 5) {
        errors.push("Name should be longer than 4 characters");
    }

    if(!emailRegex.test(email)) {
        errors.push("Email doesn't meet the requirements");
    }

    if(password.length <= 7) {
        errors.push("Password should be longer than 7 characters");
    }

    if(password !== confirmPassword) {
        console.log(password);
        console.log(confirmPassword);
        errors.push("Password and confirm password should match");
    }

    if(gender !== "Male" && gender !== "Female") {
        errors.push("Gender should be male or female");
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
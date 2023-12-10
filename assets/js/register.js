let registerForm = document.querySelector(".register");
let errorContainer = document.querySelector(".error-message");
const emailRegex = /^[^\s@]+@[^\s@]+.[^\s@]+$/;


registerForm.addEventListener("submit", (e) => {
    e.preventDefault();
    clearErrors();

    const name = e.target.name.value;
    const email = e.target.email.value;
    const password = e.target.password.value;
    const confirmPassword = e.target.confirm_password.value;

    var errors = [];
    
    if(name.length <= 5) {
        errors.push("Name should be longer than 5 characters");
    }

    if(!emailRegex.test(email)) {
        errors.push("Email doesn't meet the requirements");
    }

    if(password.length <= 7) {
        errors.push("Password should be longer than 7 characters");
    }

    if(confirmPassword != password) {
        errors.push("Confirm password should match the password");
    }
    
    
    if (errors.length > 0) {
        displayErrors(errors);
    } else {
        console.log("Login successful!");
    }
});

function clearErrors() {
    errorContainer.innerHTML = "";
}

function displayErrors(errors) {
    errors.forEach((error) => {
        const errorParagraph = document.createElement("p");
        errorParagraph.textContent = error;
        errorContainer.appendChild(errorParagraph);
    })
}
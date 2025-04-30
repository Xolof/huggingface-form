console.info("Hello from the console!");

const spinner = document.querySelector(".spinner");

const formTextField = document.querySelector("#question");

document.getElementById("submitFormButton").addEventListener("click", () => {
    if (formTextField.value) {
        spinner.style.opacity = 1;
    }
});

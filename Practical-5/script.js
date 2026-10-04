"use strict";

const form = document.getElementById("registrationForm");

const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const mobileInput = document.getElementById("mobile");
const passwordInput = document.getElementById("password");
const confirmPasswordInput =
    document.getElementById("confirmPassword");

const courseInput = document.getElementById("course");
const yearInput = document.getElementById("year");
const termsInput = document.getElementById("terms");

const strengthBar =
    document.getElementById("strengthBar");

const strengthText =
    document.getElementById("strengthText");

const successMessage =
    document.getElementById("successMessage");

const nameRegex = /^[A-Za-z ]{2,50}$/;
const emailRegex =
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const mobileRegex =
    /^[6-9][0-9]{9}$/;

function showError(id, message) {
    document.getElementById(id).textContent = message;
}

function clearErrors() {

    showError("nameError", "");
    showError("emailError", "");
    showError("mobileError", "");
    showError("passwordError", "");
    showError("confirmPasswordError", "");
    showError("courseError", "");
    showError("yearError", "");
    showError("genderError", "");
    showError("termsError", "");

    successMessage.textContent = "";
}

function checkPasswordStrength() {

    const password = passwordInput.value;
    let strength = 0;

    if (password.length >= 8) {
        strength++;
    }
    if (/[A-Z]/.test(password)) {
        strength++;
    }
    if (/[a-z]/.test(password)) {
        strength++;
    }
    if (/[0-9]/.test(password)) {
        strength++;
    }
    if (/[^A-Za-z0-9]/.test(password)) {
        strength++;
    }

    if (password.length === 0) {

        strengthBar.style.width = "0";
        strengthText.textContent = "";
    } else if (strength <= 2) {

        strengthBar.style.width = "35%";
        strengthText.textContent = "Weak password";
    } else if (strength <= 4) {

        strengthBar.style.width = "70%";
        strengthText.textContent = "Medium password";
    } else {

        strengthBar.style.width = "100%";
        strengthText.textContent = "Strong password";
    }
}

passwordInput.addEventListener(
    "input",
    checkPasswordStrength
);

form.addEventListener("submit", function (event) {

    event.preventDefault();
    clearErrors();
    let isValid = true;
    const name = nameInput.value.trim();

    if (name === "") {

        showError(
            "nameError",
            "Name is required."
        );
        isValid = false;

    } else if (!nameRegex.test(name)) {

        showError(
            "nameError",
            "Enter a valid name using letters only."
        );
        isValid = false;
    }

    const email = emailInput.value.trim();
    if (email === "") {

        showError(
            "emailError",
            "Email is required."
        );
        isValid = false;

    } else if (!emailRegex.test(email)) {

        showError(
            "emailError",
            "Enter a valid email address."
        );
        isValid = false;
    }

    const mobile = mobileInput.value.trim();
    if (mobile === "") {

        showError(
            "mobileError",
            "Mobile number is required."
        );
        isValid = false;

    } else if (!mobileRegex.test(mobile)) {

        showError(
            "mobileError",
            "Enter a valid 10-digit mobile number."
        );
        isValid = false;
    }

    const password = passwordInput.value;
    if (password === "") {

        showError(
            "passwordError",
            "Password is required."
        );
        isValid = false;

    } else if (password.length < 8) {

        showError(
            "passwordError",
            "Password must contain at least 8 characters."
        );
        isValid = false;

    } else if (!/[A-Z]/.test(password)) {

        showError(
            "passwordError",
            "Password must contain an uppercase letter."
        );
        isValid = false;

    } else if (!/[a-z]/.test(password)) {

        showError(
            "passwordError",
            "Password must contain a lowercase letter."
        );
        isValid = false;

    } else if (!/[0-9]/.test(password)) {

        showError(
            "passwordError",
            "Password must contain a number."
        );
        isValid = false;

    } else if (!/[^A-Za-z0-9]/.test(password)) {

        showError(
            "passwordError",
            "Password must contain a special character."
        );
        isValid = false;
    }

    const confirmPassword =
        confirmPasswordInput.value;

    if (confirmPassword === "") {

        showError(
            "confirmPasswordError",
            "Please confirm your password."
        );
        isValid = false;

    } else if (password !== confirmPassword) {

        showError(
            "confirmPasswordError",
            "Passwords do not match."
        );
        isValid = false;
    }

    if (courseInput.value === "") {

        showError(
            "courseError",
            "Please select a course."
        );
        isValid = false;
    }

    if (yearInput.value === "") {

        showError(
            "yearError",
            "Please select your year."
        );
        isValid = false;
    }

    const gender =
        document.querySelector(
            'input[name="gender"]:checked'
        );

    if (!gender) {

        showError(
            "genderError",
            "Please select your gender."
        );
        isValid = false;
    }

    if (!termsInput.checked) {

        showError(
            "termsError",
            "You must accept the terms."
        );
        isValid = false;
    }

    if (isValid) {

        successMessage.textContent =
            "Registration successful!";

        alert(
            "Student registration completed successfully."
        );
        form.reset();

        strengthBar.style.width = "0";
        strengthText.textContent = "";
    }

});

form.addEventListener("reset", function () {

    setTimeout(function () {
        clearErrors();
        strengthBar.style.width = "0";
        strengthText.textContent = "";

    }, 0);

});
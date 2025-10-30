document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#signUpForm");
  const generalErrorMessage = document.getElementById("generalErrorMessage");
  const languageCheckboxes = document.querySelectorAll(
    'input[name="languages[]"]'
  );
  const languageFluencyContainer = document.querySelector(".mb-3:has(.row)");

  const languageFluencySections = [
    { language: "English", nameInput: "lang-1", levelSelect: "lang-1-level" },
    { language: "Luganda", nameInput: "lang-2", levelSelect: "lang-2-level" },
    {
      language: "Runyankole",
      nameInput: "lang-3",
      levelSelect: "lang-3-level",
    },
  ];

  function hideAllLanguageFluencySections() {
    languageFluencySections.forEach((section) => {
      const sectionElement = document
        .querySelector(`input[name="${section.nameInput}"]`)
        .closest(".row > div");
      const levelSelect = document
        .querySelector(`select[name="${section.levelSelect}"]`)
        .closest(".row > div");
      sectionElement.style.display = "none";
      levelSelect.style.display = "none";
    });
  }

  function updateLanguageFluencySections() {
    hideAllLanguageFluencySections();

    const selectedLanguages = Array.from(languageCheckboxes)
      .filter((checkbox) => checkbox.checked)
      .map((checkbox) => checkbox.value);

    selectedLanguages.forEach((selectedLanguage, index) => {
      const section = languageFluencySections.find(
        (s) => s.language === selectedLanguage
      );
      if (section) {
        const sectionElement = document
          .querySelector(`input[name="${section.nameInput}"]`)
          .closest(".row > div");
        const levelSelect = document
          .querySelector(`select[name="${section.levelSelect}"]`)
          .closest(".row > div");
        sectionElement.style.display = "block";
        levelSelect.style.display = "block";
      }
    });
  }

  languageCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", updateLanguageFluencySections);
  });

  hideAllLanguageFluencySections();

  function showError(inputName, message) {
    const input = document.querySelector(`[name="${inputName}"]`);
    if (!input) return;

    const existingError = input.parentElement.querySelector(".error-message");
    if (existingError) {
      existingError.remove();
    }

    const errorElement = document.createElement("div");
    errorElement.className = "error-message text-danger small mt-1";
    errorElement.textContent = message;

    input.parentElement.appendChild(errorElement);
    input.classList.add("is-invalid");
  }

  function clearErrors() {
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((el) => el.remove());

    const invalidInputs = document.querySelectorAll(".is-invalid");
    invalidInputs.forEach((input) => input.classList.remove("is-invalid"));
    generalErrorMessage.textContent = "";
    generalErrorMessage.className = "text-danger mb-3";
  }

  function validateName(name) {
    const nameRegex = /^[A-Za-z\s]+$/;
    return nameRegex.test(name);
  }

  function validateContact(contact) {
    if (!contact) return true;
    const contactRegex = /^(\+?[0-9]{10}|[0-9]{10})$/;
    return contactRegex.test(contact);
  }

  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function validateDateOfBirth(dob) {
    if (!dob) return true;
    const selectedYear = new Date(dob).getFullYear();
    return selectedYear <= 2007;
  }

  function validatePassword(password) {
    return password.length >= 4;
  }

  function validateForm() {
    clearErrors();
    let isValid = true;

    const nameInput = document.querySelector('input[id="user_name"]');
    const mainContactInput = document.querySelector('input[id="main_contact"]');
    const altContactInput = document.querySelector('input[id="alt_contact"]');
    const dobInput = document.querySelector('input[id="age"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const addressInput = document.querySelector('input[id="user_address"]');

    if (!nameInput || !nameInput.value.trim()) {
      showError("user_name", "Name is required");
      isValid = false;
    } else if (!validateName(nameInput.value.trim())) {
      showError("user_name", "Name should only contain letters A-Z and spaces");
      isValid = false;
    }

    if (!addressInput || !addressInput.value.trim()) {
      showError("user_address", "Address is required");
      isValid = false;
    }

    if (!mainContactInput || !mainContactInput.value.trim()) {
      showError("main_contact", "Telephone number is required");
      isValid = false;
    } else if (!validateContact(mainContactInput.value.trim())) {
      showError(
        "main_contact",
        "Contact should be 10 digits or 13 digits with +"
      );
      isValid = false;
    }

    if (
      altContactInput &&
      altContactInput.value.trim() &&
      !validateContact(altContactInput.value.trim())
    ) {
      showError(
        "alt_contact",
        "Contact should be 10 digits or 13 digits with +"
      );
      isValid = false;
    }

    if (dobInput && dobInput.value && !validateDateOfBirth(dobInput.value)) {
      showError("age", "You must be 18 years and above.");
      isValid = false;
    }

    if (!emailInput || !emailInput.value.trim()) {
      showError("email", "Email is required");
      isValid = false;
    } else if (!validateEmail(emailInput.value.trim())) {
      showError("email", "Please enter a valid email address");
      isValid = false;
    }

    if (!passwordInput || !passwordInput.value) {
      showError("password", "Password is required");
      isValid = false;
    } else if (!validatePassword(passwordInput.value)) {
      showError("password", "Password must be at least 4 characters long");
      isValid = false;
    }

    if (
      mainContactInput &&
      altContactInput &&
      altContactInput.value.trim() !== "" &&
      mainContactInput.value.trim() === altContactInput.value.trim()
    ) {
      showError(
        "alt_contact",
        "Alternative contact cannot be the same as main contact"
      );
      isValid = false;
    }

    const selectedLanguages = Array.from(languageCheckboxes)
      .filter((checkbox) => checkbox.checked)
      .map((checkbox) => checkbox.value);

    if (selectedLanguages.length === 0) {
      generalErrorMessage.textContent =
        "Please select at least one preferred language";
      isValid = false;
    }

    const consentCheckbox = document.querySelector('input[name="consent"]');
    if (!consentCheckbox || !consentCheckbox.checked) {
      showError("consent", "You must agree to the consent form");
      isValid = false;
    }

    return isValid;
  }

  const inputs = form.querySelectorAll("input, select");
  inputs.forEach((input) => {
    input.addEventListener("input", function () {
      const existingError = this.parentElement.querySelector(".error-message");
      if (existingError) {
        existingError.remove();
      }
      this.classList.remove("is-invalid");

      const value = this.value.trim();

      switch (this.name) {
        case "user_name":
          if (value && !validateName(value)) {
            showError(
              "user_name",
              "Name should only contain letters A-Z and spaces"
            );
          }
          break;
        case "main_contact":
        case "alt_contact":
          if (value !== "" && !validateContact(value)) {
            showError(
              this.name,
              "Contact should be 10 digits or 13 digits with +"
            );
          }
          break;
        case "age":
          if (value && !validateDateOfBirth(value)) {
            showError("age", "You must be 18 years and above");
          }
          break;
        case "email":
          if (value && !validateEmail(value)) {
            showError("email", "Please enter a valid email address");
          }
          break;
        case "password":
          if (value && !validatePassword(value)) {
            showError(
              "password",
              "Password must be at least 4 characters long"
            );
          }
          break;
      }

      const mainContactInput = document.querySelector(
        'input[id="main_contact"]'
      );
      const altContactInput = document.querySelector('input[id="alt_contact"]');
      if (
        mainContactInput &&
        altContactInput &&
        altContactInput.value.trim() !== "" &&
        mainContactInput.value.trim() === altContactInput.value.trim()
      ) {
        showError(
          "alt_contact",
          "Alternative contact cannot be the same as main contact"
        );
      }
    });
  });

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    if (!validateForm()) {
      generalErrorMessage.textContent =
        "Please fix the form errors before submitting";
      return;
    }

    const formData = new FormData(form);
    formData.append("submit", "1");

    console.log("Form data being submitted:");
    for (let pair of formData.entries()) {
      console.log(pair[0] + ": " + pair[1]);
    }

    try {
      console.log("Submitting to: /api/auth/signup.php");
      const response = await fetch("/natural_language/lingobaraza/api/auth/signup.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`Server responded with status: ${response.status}`);
      }

      const contentType = response.headers.get("content-type");
      if (contentType && contentType.includes("application/json")) {
        responseData = await response.json();
      } else {
        const textResponse = await response.text();
        throw new Error(
          `Server did not return JSON: ${textResponse.substring(0, 100)}`
        );
      }

      console.log("Response received:", responseData);

      if (!responseData.success) {
        if (responseData.errors) {
          Object.keys(responseData.errors).forEach((key) => {
            if (key === "general") {
              generalErrorMessage.textContent = responseData.errors[key];
            } else {
              showError(key, responseData.errors[key]);
            }
          });
        } else {
          generalErrorMessage.textContent =
            "Server returned an error without details";
        }
      } else {
        generalErrorMessage.textContent = "Signup successful!";
        generalErrorMessage.className = "text-success mb-3";

        if (responseData.redirect) {
          setTimeout(() => {
            window.location.href = responseData.redirect;
          }, 1500);
        }
      }
    } catch (error) {
      console.error("Error processing response:", error);
      generalErrorMessage.textContent =
        "An unexpected error occurred: " + error.message;
    }
  });
});

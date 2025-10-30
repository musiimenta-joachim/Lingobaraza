document.addEventListener("DOMContentLoaded", function () {
  function detectBrowser() {
    const userAgent = window.navigator.userAgent.toLowerCase();

    const isChrome =
      userAgent.includes("chrome") &&
      userAgent.includes("google") &&
      !userAgent.includes("edge") &&
      !userAgent.includes("opr") &&
      !userAgent.includes("edg");

    return {
      isChrome: isChrome,
      browserName: isChrome ? "Google Chrome" : "Non-Chrome Browser",
    };
  }

  function createBrowserWarning() {
    if (document.getElementById("browser-warning")) return;

    const warningDiv = document.createElement("div");
    warningDiv.id = "browser-warning";
    warningDiv.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: #e6f3e6;
      color: #2c7a2c;
      text-align: center;
      padding: 10px;
      z-index: 1000;
      border-bottom: 2px solid #90ee90;
      font-weight: bold;
    `;
    warningDiv.innerHTML = `
      <strong>Attention:</strong> This website is optimized for Google Chrome only. 
      Please switch to Google Chrome for the best experience.
    `;

    document.body.insertBefore(warningDiv, document.body.firstChild);

    const closeButton = document.createElement("span");
    closeButton.innerHTML = "✕";
    closeButton.style.cssText = `
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-weight: bold;
    `;
    closeButton.onclick = () => {
      warningDiv.remove();
    };
    warningDiv.appendChild(closeButton);
  }

  const browserInfo = detectBrowser();
  console.log("Browser Detection:", browserInfo);

  if (!browserInfo.isChrome) {
    createBrowserWarning();
  }

  const loginForm = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");
  const generalError = document.getElementById("generalError");

  emailInput.addEventListener("input", () => {
    emailError.textContent = "";
    generalError.textContent = "";
  });

  passwordInput.addEventListener("input", () => {
    passwordError.textContent = "";
    generalError.textContent = "";
  });

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    emailError.textContent = "";
    passwordError.textContent = "";
    generalError.textContent = "";

    let isValid = true;

    if (!emailInput.value.trim()) {
      emailError.textContent = "Email is required";
      isValid = false;
    } else if (!isValidEmail(emailInput.value)) {
      emailError.textContent = "Invalid email format";
      isValid = false;
    }

    if (!passwordInput.value.trim()) {
      passwordError.textContent = "Password is required";
      isValid = false;
    }

    if (!isValid) {
      return;
    }

    const formData = new FormData(loginForm);

    fetch("./api/auth/login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        console.log("Raw response:", response);

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
      })
      .then((data) => {
        console.log("Parsed data:", data);

        if (data.success) {
          switch (data.user_type) {
            case "admin":
              window.location.href = "./admin/index.php";
              break;
            case "validator":
              window.location.href = "./validator/index.php";
              break;
            case "contributor":
              window.location.href = "./contributor/index.php";
              break;
            default:
              window.location.href = "./index.html";
          }
        } else {
          switch (data.error_type) {
            case "email_not_found":
              emailError.textContent = data.message;
              break;
            case "wrong_password":
              passwordError.textContent = data.message;
              break;
            case "account_suspended":
              generalError.textContent = data.message;
              break;
            case "invalid_email":
              emailError.textContent = data.message;
              break;
            default:
              generalError.textContent = data.message || "Login failed";
          }
        }
      })
      .catch((error) => {
        console.error("Full error:", error);

        if (error instanceof TypeError) {
          generalError.textContent = "Network error or invalid JSON";
        } else {
          generalError.textContent =
            "An unexpected error occurred: " + error.message;
        }
      });
  });

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
});

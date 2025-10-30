document.addEventListener("DOMContentLoaded", function () {
  fetch("../api/contributor/user_details.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((userData) => {
      if (userData.length > 0) {
        var user = userData[0];

        document.getElementById("nav_name").textContent = user.user_name || "";
      }
    })
    .catch((error) => {
      document.getElementById("userData").innerHTML =
        "<p>Failed to load user data.</p>";
    });
});

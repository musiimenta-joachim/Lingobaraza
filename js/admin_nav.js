document.addEventListener("DOMContentLoaded", function () {
  fetch("../api/admin/user_details.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((userData) => {
      if (userData.user_data && userData.user_data.length > 0) {
        var user = userData.user_data[0];

        const navNameElement = document.getElementById("nav_name");
        if (navNameElement) {
          navNameElement.textContent = user.user_name || "No Name";
        } else {
          console.error("nav_name element not found");
        }
      } else {
        console.error("No user data found");
      }
    })
    .catch((error) => {
      console.error("Error fetching user data:", error);
      const userDataElement = document.getElementById("userData");
      if (userDataElement) {
        userDataElement.innerHTML = "<p>Failed to load user data.</p>";
      }
    });
});

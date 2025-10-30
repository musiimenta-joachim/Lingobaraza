<?php
require('../api/shared/verify_status.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="../img/icons/logo.svg" />
    <title>Lingobaraza</title>
    <link href="../css/app.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
    <style>
      .navbar-items {
        list-style-type: none;
        padding: 0 0 0 2em;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
      }

      @media screen and (max-width: 767px) {
        .navbar-items {
          display: none;
        }
      }

      .navbar-items li {
        margin: 0;
        padding: 0;
      }

      .navbar-items li a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: color 0.3s ease;
      }

      .navbar-items li a:hover {
        color: #007bff;
      }

      .logo{
        display: flex;
        flex-direction: row;
      }

      .logo h3{
        padding-left: 10px;
        padding-top: 2px;
      }
    </style>
  </head>

  <body>
    <div class="wrapper">
      <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg fixed-top">
          <div class="navbar-collapse collapse">
            <div class="logo">
              <img src="../img/icons/logo.svg" class="img-fluid" style="width:30px; height:30px">
              <a href="./index.php">
                <h3>Lingobaraza</h3>
              </a>
            </div>
            <ul class="navbar-items">
              <li><a href="db_sentences.php">Contribute voice</a></li>
              <li><a href="contribute.php">Contribute Sentence</a></li>
           </ul> 
            <ul class="navbar-nav navbar-align">
              <li class="nav-item dropdown">
                <a
                  class="nav-icon dropdown-toggle d-inline-block d-sm-none"
                  href="#"
                  data-bs-toggle="dropdown"
                >
                  <i class="align-middle" data-feather="settings"></i>
                </a>

                <a
                  class="nav-link dropdown-toggle d-none d-sm-inline-block"
                  href="#"
                  data-bs-toggle="dropdown"
                >
                  <img
                    src="../img/avatars/avatar.png"
                    class="avatar img-fluid rounded me-1"
                    alt="user"
                  />
                  <span class="text-dark" id="nav_name"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="db_sentences.php">
                    <i class="align-middle me-1" data-feather="mic"></i>
                    Voice Contribution
                  </a>
                  <a class="dropdown-item" href="contribute.php">
                    <i class="align-middle me-1" data-feather="plus"></i>
                    Add Sentence
                  </a>
                  <a class="dropdown-item" href="sentences_to_translate.php">
                    <i class="align-middle me-1" data-feather="refresh-cw"></i>
                    Add Translation
                  </a>
                  <a class="dropdown-item" href="profile.php">
                    <i class="align-middle me-1" data-feather="user"></i>
                    Profile
                  </a>
                  <a class="dropdown-item" href="all_contributions.php">
                    <i class="align-middle me-1" data-feather="pie-chart"></i>
                    All Contributions
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="../logout.php">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </nav>

        <main class="content mt-5">
          <div class="container-fluid p-0">
            <div class="mt-1 mb-2">
              <h1 class="h3 d-inline align-middle" style="margin-top: -1rem !important">
                Profile Details
              </h1>
            </div>
            <div class="row">
              <div class="col-md-4 col-xl-3">
                <div class="card mb-3">
                  <div class="card-body text-center">
                    <img src="../img/avatars/avatar.png" alt="profile_pic" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                    <h5 class="card-title mb-0" id="user_name"></h5>
                    <div class="text-muted mb-2" id="account_type"></div>
                  </div>
                  <hr class="my-0" />
                </div>
              </div>

              <div class="col-md-8 col-xl-9">
                <div class="card">
                  <div class="card-body h-100">
                    <form method="post">
                      <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="" /> 
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" value="" />
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Main Contact</label>
                            <input type="text" class="form-control" name="contact-1" id="contact-1" value="" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Alternative Contact</label>
                            <input type="text" class="form-control" name="contact-2" id="contact-2" value="" />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                              <option value="male">Male</option>
                              <option value="female">Female</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="age" id="age" value="" />
                          </div>
                        </div>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Preferred Language(s)</label>
                        <div>
                          <label class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              value="English"
                              name="languages[]"
                              id="lang-english"
                            />
                            <span class="form-check-label">English</span>
                          </label>
                          <label class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              value="Luganda"
                              name="languages[]"
                              id="lang-luganda"
                            />
                            <span class="form-check-label">Luganda</span>
                          </label>
                          <label class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              value="Runyankole"
                              name="languages[]"
                              id="lang-runyankole"
                            />
                            <span class="form-check-label">Runyankole</span>
                          </label>
                        </div>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Level of Language Fluency</label>
                        <div class="row mb-2">
                          <div class="col-9">
                            <input
                              class="form-control"
                              type="text"
                              value="English"
                              disabled
                            />
                            <input
                              type="hidden"
                              name="lang-1"
                              value="English"
                            />
                          </div>
                          <div class="col-3">
                            <select name="lang-1-level" id="lang-1-level" class="form-select">
                              <option value="Primary">Primary</option>
                              <option value="O-level">O-Level</option>
                              <option value="A-level">A-Level</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="row mb-2">
                          <div class="col-9">
                            <input
                              class="form-control"
                              type="text"
                              value="Luganda"
                              disabled
                            />
                            <input
                              type="hidden"
                              name="lang-2"
                              value="Luganda"
                            />
                          </div>
                          <div class="col-3">
                            <select name="lang-2-level" id="lang-2-level" class="form-select">
                              <option value="Primary">Primary</option>
                              <option value="O-level">O-Level</option>
                              <option value="A-level">A-Level</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-9">
                            <input
                              class="form-control"
                              type="text"
                              value="Runyankole"
                              disabled
                            />
                            <input
                              type="hidden"
                              name="lang-3"
                              value="Runyankole"
                            />
                          </div>
                          <div class="col-3">
                            <select name="lang-3-level" id="lang-3-level" class="form-select">
                              <option value="Primary">Primary</option>
                              <option value="O-level">O-Level</option>
                              <option value="A-level">A-Level</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Enter New Password</label>
                        <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter new password"/>
                      </div>
                      <div class="d-grid">
                        <button name="save_changes" class="btn btn-primary">Save changes</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>

        <footer class="footer">
          <div class="container-fluid">
            <div class="row text-muted">
              <div class="col-6 text-start">
                <p class="mb-0">
                  <a class="text-muted"><strong>Lingobaraza</strong></a>
                </p>
              </div>
              <div class="col-6 text-end">
                <ul class="list-inline">
                  <li class="list-inline-item">
                    <a class="text-muted">&copy; Copyright 2024.</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            const languages = [];
            ['english', 'luganda', 'runyankole'].forEach(lang => {
                const checkbox = document.getElementById(`lang-${lang}`);
                if (checkbox && checkbox.checked) {
                    languages.push(checkbox.value);
                }
                formData.set(`lang-${lang.toLowerCase()}`, checkbox && checkbox.checked ? 'true' : 'false');
            });

            fetch('../api/contributor/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    if (data.data) {
                        updateProfileDisplay(data.data);
                    }
                } else {
                    alert(data.message || 'An error occurred while updating the profile');
                }
            })
            .catch(error => {
                alert('An error occurred while updating the profile');
            });
        });
        
        function updateProfileDisplay(data) {
            if (data.name) document.getElementById('user_name').textContent = data.name;
            if (data.name) document.getElementById('nav_name').textContent = data.name;
        }
    });
    </script>
    <script>
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
              document.getElementById("name").value = user.user_name || "";
              document.getElementById("address").value = user.address || "";
              document.getElementById("contact-1").value = user.main_contact || "";
              document.getElementById("contact-2").value = user.alt_contact || "";
              document.getElementById("email").value = user.email || "";
              document.getElementById("user_name").textContent = user.user_name || "";
              document.getElementById("account_type").textContent = user.acc_type || "";
              
              if (user.gender) {
                document.getElementById("gender").value = user.gender;
              }
              
              if (user.age) {
                document.getElementById("age").value = user.age;
              }
              
              if (user.preferred_languages) {
                try {
                  const languages = JSON.parse(user.preferred_languages);
                  if (Array.isArray(languages)) {
                    languages.forEach(lang => {
                      const checkbox = document.getElementById(`lang-${lang.toLowerCase()}`);
                      if (checkbox) {
                        checkbox.checked = true;
                      }
                    });
                  }
                } catch (e) {
                  console.error("Error parsing preferred languages:", e);
                }
              }
              
              if (user.level_of_fluency) {
                try {
                  let fluencyLevels;
                  if (user.level_of_fluency.startsWith('[')) {
                    fluencyLevels = JSON.parse(user.level_of_fluency);
                    fluencyLevels.forEach(item => {
                      switch(item.label) {
                        case "English":
                          document.getElementById("lang-1-level").value = item.fluency;
                          break;
                        case "Luganda":
                          document.getElementById("lang-2-level").value = item.fluency;
                          break;
                        case "Runyankole":
                          document.getElementById("lang-3-level").value = item.fluency;
                          break;
                      }
                    });
                  } else {
                    fluencyLevels = JSON.parse(user.level_of_fluency);
                    if (fluencyLevels.English) {
                      document.getElementById("lang-1-level").value = fluencyLevels.English;
                    }
                    if (fluencyLevels.Luganda) {
                      document.getElementById("lang-2-level").value = fluencyLevels.Luganda;
                    }
                    if (fluencyLevels.Runyankole) {
                      document.getElementById("lang-3-level").value = fluencyLevels.Runyankole;
                    }
                  }
                } catch (e) {
                  console.error("Error parsing fluency levels:", e);
                }
              }
            }
          })
          .catch((error) => {
            console.error("Failed to load user data:", error);
          });
      });
    </script>
    <script src="../js/app.js"></script>
  </body>
</html>

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
  </head>

  <body>
    <div class="wrapper">
      <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
          <a class="sidebar-brand" href="index.php">
            <img src="../img/icons/logo.svg" alt="logo" style="width:30px; height:30px">
            <span class="align-middle">Lingobaraza</span>
          </a>

          <ul class="sidebar-nav">
            <li class="sidebar-header">Pages</li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle" data-feather="sliders"></i>
                <span class="align-middle">Dashboard</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="sentences.php">
                <i class="align-middle" data-feather="file-text"></i>
                <span class="align-middle">Sentences</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="recordings.php">
                <i class="align-middle" data-feather="music"></i>
                <span class="align-middle">Voice Recordings</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="adduser.php">
                <i class="align-middle" data-feather="user-plus"></i>
                <span class="align-middle">Add User</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="users.php">
                <i class="align-middle" data-feather="users"></i>
                <span class="align-middle">Users</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="corrections.php">
                <i class="align-middle" data-feather="filter"></i>
                <span class="align-middle">Corrections</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="sendmail.php">
                <i class="align-middle" data-feather="mail"></i>
                <span class="align-middle">Send Email</span>
              </a>
            </li>

            <li class="sidebar-item active">
              <a class="sidebar-link" href="profile.php">
                <i class="align-middle" data-feather="user"></i>
                <span class="align-middle">Profile</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../logout.php">
                <i class="align-middle" data-feather="power"></i>
                <span class="align-middle">Log Out</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
          <a class="sidebar-toggle js-sidebar-toggle">
            <i class="hamburger align-self-center"></i>
          </a>

          <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
              <li class="nav-item">
                <a class="nav-link d-sm-inline-block">
                  <img
                    src="../img/avatars/avatar.png"
                    class="avatar img-fluid rounded me-1"
                    alt="admin"
                  />
                  <span class="text-dark" id="nav_name"></span>
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main class="content">
          <div class="container-fluid p-0">
            <div class="mb-3">
              <h1 class="h3 d-inline align-middle">Profile</h1>
            </div>
            <div class="row">
              <div class="col-md-4 col-xl-3">
                <div class="card mb-3">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Profile Details</h5>
                  </div>
                  <div class="card-body text-center">
                    <img
                      src="../img/avatars/avatar.png"
                      alt="profile_pic"
                      class="img-fluid rounded-circle mb-2"
                      width="128"
                      height="128"
                    />
                    <h5 class="card-title mb-0" id="user_name"></h5>
                    <div class="text-muted mb-2" id="account_type"></div>
                  </div>
                  <hr class="my-0" />
                </div>
              </div>

              <div class="col-md-8 col-xl-9">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Personal Details</h5>
                  </div>
                  <div class="card-body h-100">
                    <form action="" method="post">
                      <!-- <div class="mb-2">
                        <label for="profile_pic" class="form-label">Profile Picture</label>
                        <input type="file" name="profile_pic" id="profile_pic" class="form-control"/>
                      </div> -->
                      <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="" /> 
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" value="" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Main Contact</label>
                        <input type="text" class="form-control" name="contact-1" id="contact-1" value="" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Alternative Contact</label>
                        <input type="text" class="form-control" name="contact-2" id="contact-2" value="" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="" />
                      </div>
                      <div class="mb-2">
                        <label class="form-label">About Admin</label>
                        <textarea name="about-admin" class="form-control" rows="5" id="about_admin"></textarea>
                      </div>

                      <div class="mb-2">
                        <label class="form-label">About Project</label>
                        <textarea name="about-project" class="form-control" rows="10" id="about_project"></textarea>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Enter New Password</label>
                        <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter new password"/>
                      </div>
                      <div class="d-grid">
                        <button name="save_changes" class="btn btn-primary">
                          Save changes
                        </button>
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
                    &copy; Copyright 2024. All rights reserved.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <script src="../js/app.js"></script>
    <script src="../js/admin_nav.js"></script>
    <script>
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
              const elements = {
                "nav_name": user.user_name || "",
                "name": user.user_name || "",
                "address": user.address || "",
                "contact-1": user.main_contact || "",
                "contact-2": user.alt_contact || "",
                "email": user.email || "",
                "user_name": user.user_name || "",
                "account_type": user.acc_type || "",
                "about_admin": userData.about_admin_data.length > 0 ? userData.about_admin_data[0].details || "" : "",
                "about_project": userData.about_project_data.length > 0 ? userData.about_project_data[0].project_details || "" : ""
              };

              Object.keys(elements).forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                  if (element.tagName === "INPUT") {
                    element.value = elements[id];
                  } else if (element.tagName === "TEXTAREA") {
                    element.value = elements[id];
                  } else {
                    element.textContent = elements[id];
                  }
                }
              });

              const languages = user.preferred_languages
                ? JSON.parse(user.preferred_languages)
                : [];
              
              languages.forEach(function (language) {
                const langInput = document.getElementById(
                  `lang-${language.toLowerCase()}`
                );
                if (langInput) {
                  langInput.checked = true;
                }
              });

              const fluency = user.level_of_fluency
                ? JSON.parse(user.level_of_fluency)
                : [];

              fluency.forEach(function (lang, index) {
                const langInput = document.getElementById(`lang-${index + 1}`);
                if (langInput) {
                  langInput.value = lang.label;
                }

                const langSelect = document.getElementById(
                  `lang-${index + 1}-level`
                );
                if (langSelect) {
                  langSelect.value = lang.fluency;
                }
              });
            }
          })
          .catch((error) => {
            console.error("Error fetching user data:", error);
            alert("Failed to load user data.");
          });

        const form = document.querySelector('form');
        if (form) {
          form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            ['English', 'Luganda', 'Runyankole'].forEach(lang => {
              const checkbox = document.getElementById(`lang-${lang.toLowerCase()}`);
              if (checkbox) {
                formData.set(`lang-${lang}`, checkbox.checked ? 'true' : 'false');
              }
            });

            fetch('../api/admin/update_profile.php', {
              method: 'POST',
              body: formData
            })
            .then(response => {
              const contentType = response.headers.get('content-type');
              if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Oops, we haven't got JSON!");
              }
              return response.json();
            })
            .then(data => {
              if (data.success) {
                alert(data.message);
                if (data.data) {
                  updateProfileDisplay(data.data);
                }
              } else {
                console.error('Update Error:', data);
                alert(data.message || 'An error occurred while updating the profile');
              }
            })
            .catch(error => {
              console.error('Fetch Error:', error);
              alert('An unexpected error occurred. Please try again.');
            });
          });
        }
      });

      function updateProfileDisplay(data) {
        const userNameElement = document.getElementById('user_name');
        const navNameElement = document.getElementById('nav_name');
        
        if (data.name) {
          if (userNameElement) userNameElement.textContent = data.name;
          if (navNameElement) navNameElement.textContent = data.name;
        }
      }
    </script>
  </body>
</html>

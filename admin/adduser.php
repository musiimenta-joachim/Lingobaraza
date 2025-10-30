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

            <li class="sidebar-item active">
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

            <li class="sidebar-item">
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
            <h1 class="h3 mb-3">Add User</h1>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <form method="post" action="">
                      <div class="row">
                        <div class="col-8">
                          <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input
                              class="form-control form-control-lg"
                              type="text"
                              name="name"
                              placeholder="Enter user name"
                              required
                            />
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="mb-3">
                            <label class="form-label">Account Type</label>
                            <select name="user_type" class="form-select">
                              <option value="admin">Admin</option>
                              <option value="expert">Validator</option>
                              <option value="user">User</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input
                          class="form-control form-control-lg"
                          type="text"
                          name="address"
                          placeholder="Enter user address"
                          required
                        />
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="mb-3">
                            <label class="form-label">Main Contact</label>
                            <input
                              class="form-control form-control-lg"
                              type="text"
                              name="main_contact"
                              placeholder="Enter user contact"
                              required
                            />
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="mb-3">
                            <label class="form-label"
                              >Alternative Contact</label
                            >
                            <input
                              class="form-control form-control-lg"
                              type="text"
                              name="alt_contact"
                              placeholder="Enter user alt contact"
                              required
                            />
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                              <option value="male">Male</option>
                              <option value="female">Female</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input
                              class="form-control form-control-lg"
                              type="date"
                              name="age"
                              placeholder="Enter user date of birth"
                            />
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
                            />
                            <span class="form-check-label">English</span>
                          </label>
                          <label class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              value="Luganda"
                            />
                            <span class="form-check-label">Luganda</span>
                          </label>
                          <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="Runyankole"/>
                            <span class="form-check-label">Runyankole</span>
                          </label>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Select Level of Language Fluency</label>
                        <div class="row">
                          <div class="col-9">
                            <input class="form-control form-control-lg" type="text" name="lang-1" value="English" disabled/>
                            <input class="form-control form-control-lg" type="text" name="lang-1" value="English" style="display: none"/>
                          </div>
                          <div class="col-3">
                            <select name="lang-1-level" class="form-select">
                              <option value="Primary">Primary</option>
                              <option value="O-level">O-Level</option>
                              <option value="A-level">A-Level</option>
                            </select>
                          </div>

                          <div class="mb-2"></div>

                          <div class="col-9">
                            <input class="form-control form-control-lg" type="text" name="lang-2" value="Runyankole" disabled/>
                            <input class="form-control form-control-lg" type="text" name="lang-2" value="Runyankole" style="display: none"/>
                          </div>
                          <div class="col-3">
                            <select name="lang-2-level" class="form-select">
                              <option value="Primary">Primary</option>
                              <option value="O-level">O-Level</option>
                              <option value="A-level">A-Level</option>
                            </select>
                          </div>

                          <div class="mb-2"></div>

                          <div class="col-9">
                            <input class="form-control form-control-lg" type="text" name="lang-3" value="Luganda" disabled/>
                            <input class="form-control form-control-lg" type="text" name="lang-3" value="Luganda" style="display: none"/>
                          </div>
                          <div class="col-3">
                            <select name="lang-3-level" class="form-select">
                              <option value="Primary">Primary</option>
                              <option value="O-level">O-Level</option>
                              <option value="A-level">A-Level</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter user email" required/>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" required/>
                      </div>
                      <div class="text-center mt-3">
                        <button type="submit" class="btn btn-lg btn-primary">
                          Add User
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
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const languagesCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            const selectedLanguages = Array.from(languagesCheckboxes).map(checkbox => checkbox.value);
            
            const formData = new FormData(form);
            
            formData.delete('languages');
            selectedLanguages.forEach(lang => {
                formData.append('languages[]', lang);
            });
            
            formData.append('submit', 'Add User');
            
            fetch('../api/admin/add_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.includes('Success!')) {
                    alert('User added successfully!');
                    window.location.href = 'users.php';
                } else {
                    alert(result);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the form.');
            });
        });
    });
    </script>
  </body>
</html>

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

            <li class="sidebar-item active">
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
                    <h1 class="h3 mb-3">User Details</h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" id="user-details-container">
                                    <div class="text-center">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
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
    class UserDetailsPage {
        constructor() {
            this.container = document.getElementById('user-details-container');
            this.userId = null;
        }

        async fetchUserDetails() {
            const urlParams = new URLSearchParams(window.location.search);
            this.userId = urlParams.get('user_id');

            if (!this.userId) {
                this.container.innerHTML = `
                    <div class="alert alert-danger">
                        No user ID provided. Please return to the users list.
                    </div>
                `;
                return;
            }

            try {
                const response = await fetch(`../api/admin/single_user.php?user_id=${this.userId}`);
                
                if (!response.ok) {
                    throw new Error('Failed to fetch user details');
                }

                const userData = await response.json();
                this.renderUserDetails(userData);
            } catch (error) {
                console.error('Error:', error);
                this.container.innerHTML = `
                    <div class="alert alert-danger">
                        ${error.message}. Please try again later.
                    </div>
                `;
            }
        }

        renderUserDetails(user) {
            const formattedRegDate = this.formatDate(user.reg_date);
            const formattedAge = this.formatDate(user.age);

            this.container.innerHTML = `
                <form id="user-details-form">
                    <input type="hidden" name="user_id" value="${this.userId}">
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input
                                    class="form-control form-control-lg"
                                    type="text"
                                    name="user_name"
                                    value="${user.user_name}"
                                />
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Account Type</label>
                                <select name="acc_type" class="form-select">
                                    <option value="admin" ${user.acc_type === 'admin' ? 'selected' : ''}>Admin</option>
                                    <option value="validator" ${user.acc_type === 'validator' ? 'selected' : ''}>Validator</option>
                                    <option value="contributor" ${user.acc_type === 'contributor' ? 'selected' : ''}>Contributor</option>
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
                            value="${user.address || ''}"
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
                                    value="${user.main_contact}"
                                />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Alternative Contact</label>
                                <input
                                    class="form-control form-control-lg"
                                    type="text"
                                    name="alt_contact"
                                    value="${user.alt_contact}"
                                />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="male" ${user.gender === 'male' ? 'selected' : ''}>Male</option>
                                    <option value="female" ${user.gender === 'female' ? 'selected' : ''}>Female</option>
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
                                    value="${user.age || ''}"
                                />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input
                                    class="form-control form-control-lg"
                                    type="text"
                                    name="password"
                                    placeholder="Enter new password (leave blank to keep current)"
                                />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Registration Date</label>
                                <input
                                    class="form-control form-control-lg"
                                    type="text"
                                    value="${formattedRegDate}"
                                    readonly
                                />
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-lg btn-primary">
                            Update User Details
                        </button>
                    </div>
                </form>
            `;

            document.getElementById('user-details-form').addEventListener('submit', this.handleSubmit.bind(this));
        }

        async handleSubmit(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('../api/admin/update_user.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    alert('User details updated successfully!');
                } else {
                    alert(`Error: ${result.error || 'Failed to update user details'}`);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating user details');
            }
        }

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });
            } catch {
                return 'Invalid Date';
            }
        }

        init() {
            this.fetchUserDetails();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const userDetailsPage = new UserDetailsPage();
        userDetailsPage.init();
    });
    </script>
</body>
</html>
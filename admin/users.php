<?php
require('../api/shared/verify_status.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../img/icons/logo.svg" />
    <title>Lingobaraza</title>
    <link href="../css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
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

                    <li class="sidebar-item active active">
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
                                <img src="../img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="admin" /> <span class="text-dark" id="nav_name"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">System Users</h1>
                    <div class="row">
                        <div class="col-12 col-lg-112 col-xxl-12 d-flex">
                            <div class="card flex-fill">
                                <table class="table table-hover my-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="d-none d-xl-table-cell">Address</th>
                                            <th class="d-none d-xl-table-cell">Contact</th>
                                            <th class="d-none d-md-table-cell">Account Type</th>
                                            <th>Joining Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-table-body">
                                        
                                    </tbody>
                                </table>
                                <div class="card-footer d-flex justify-content-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination">
                                            
                                        </ul>
                                    </nav>
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
        class UsersPagination {
            constructor() {
                this.currentPage = 1;
                this.totalPages = 0;
                this.usersPerPage = 10;
                this.maxUsers = 0;

                this.tableBody = document.getElementById('users-table-body');
                this.paginationContainer = document.getElementById('pagination');
                this.prevButton = document.createElement('li');
                this.prevButton.classList.add('page-item');
                this.prevButton.innerHTML = '<a class="page-link" href="#">Previous</a>';
                this.prevButton.addEventListener('click', () => this.changePage(this.currentPage - 1));
                this.nextButton = document.createElement('li');
                this.nextButton.classList.add('page-item');
                this.nextButton.innerHTML = '<a class="page-link" href="#">Next</a>';
                this.nextButton.addEventListener('click', () => this.changePage(this.currentPage + 1));
            }

            formatDate(dateString) {
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

            async fetchUsers(page = 1) {
                try {
                    const response = await fetch(`../api/admin/users.php?page=${page}`);
                    const data = await response.json();

                    this.tableBody.innerHTML = '';

                    data.users.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.user_name}</td>
                            <td class="d-none d-xl-table-cell">${user.address || 'N/A'}</td>
                            <td class="d-none d-xl-table-cell">${user.main_contact}/${user.alt_contact}</td>
                            <td class="d-none d-md-table-cell">${user.acc_type}</td>
                            <td>${this.formatDate(user.reg_date)}</td>
                            <td>
                                <a href="./userdetails.php?user_id=${user.user_id}">
                                    <button class="btn btn-info">Details</button>
                                </a>
                            </td>
                        `;
                        this.tableBody.appendChild(row);
                    });

                    this.totalPages = data.total_pages;
                    this.currentPage = page;
                    this.maxUsers = data.total_users;

                    this.paginationContainer.innerHTML = '';
                    this.paginationContainer.appendChild(this.prevButton);

                    for (let i = 1; i <= this.totalPages; i++) {
                        const pageButton = document.createElement('li');
                        pageButton.classList.add('page-item');
                        if (i === this.currentPage) pageButton.classList.add('active');
                        const pageLink = document.createElement('a');
                        pageLink.classList.add('page-link');
                        pageLink.href = '#';
                        pageLink.textContent = i;
                        pageLink.addEventListener('click', () => this.changePage(i));
                        pageButton.appendChild(pageLink);
                        this.paginationContainer.appendChild(pageButton);
                    }

                    this.paginationContainer.appendChild(this.nextButton);
                    this.updateNavigationButtons();
                } catch (error) {
                    console.error('Error fetching users:', error);
                    this.tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                Error loading users. Please try again later.
                            </td>
                        </tr>
                    `;
                }
            }

            updateNavigationButtons() {
                this.prevButton.classList.toggle('disabled', this.currentPage === 1);
                this.nextButton.classList.toggle('disabled', this.currentPage === this.totalPages);
            }

            changePage(newPage) {
                if (newPage < 1 || newPage > this.totalPages) return;
                this.fetchUsers(newPage);
            }

            init() {
                this.fetchUsers();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const usersPagination = new UsersPagination();
            usersPagination.init();
        });
    </script>
</body>

</html>
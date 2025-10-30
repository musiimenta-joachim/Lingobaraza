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
    <title>Lingobaraza - Corrections</title>
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

            <li class="sidebar-item active">
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
            <h1 class="h3 mb-3">Corrections</h1>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="table table-hover my-0">
                      <thead>
                        <tr>
                          <th class="d-none d-xl-table-cell">Sentence</th>
                          <th class="d-none d-xl-table-cell">Expert</th>
                          <th>Correction</th>
                          <th>Votes</th>
                          <th class="d-none d-md-table-cell">Submitted</th>
                        </tr>
                      </thead>
                      <tbody id="corrections-table-body">
                        
                      </tbody>
                    </table>

                    <nav aria-label="Corrections pagination" class="mt-3">
                      <ul class="pagination justify-content-center" id="pagination">
                        
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
      document.addEventListener('DOMContentLoaded', function() {
          const tableBody = document.getElementById('corrections-table-body');
          const paginationContainer = document.getElementById('pagination');

          function formatDate(dateString) {
              const date = new Date(dateString);
              return date.toLocaleDateString('en-GB', {
                  day: '2-digit',
                  month: '2-digit',
                  year: 'numeric'
              });
          }

          function fetchCorrections(page = 1) {
              fetch(`../api/admin/corrections.php?page=${page}`)
                  .then(response => response.json())
                  .then(data => {
                      tableBody.innerHTML = '';
                      paginationContainer.innerHTML = '';

                      if (!data.corrections || data.corrections.length === 0) {
                          const noDataRow = document.createElement('tr');
                          noDataRow.innerHTML = `
                              <td colspan="5" class="text-center">
                                  <div class="alert alert-info" role="alert">
                                      <i class="align-middle me-2" data-feather="info"></i>
                                      No corrections have been made yet. 
                                      <br>When validators submit corrections, they will be displayed.
                                  </div>
                              </td>
                          `;
                          tableBody.appendChild(noDataRow);
                          
                          if (typeof feather !== 'undefined') {
                              feather.replace();
                          }
                          
                          return;
                      }

                      data.corrections.forEach(correction => {
                          const row = document.createElement('tr');
                          row.innerHTML = `
                              <td class="d-none d-xl-table-cell">${correction.original_sentence}</td>
                              <td class="d-none d-xl-table-cell">${correction.expert_username || 'Unknown'}</td>
                              <td>${correction.correction}</td>
                              <td>
                                  <div>
                                      <div class="pr-2 pl-2 text-success">Supporting: ${correction.vote_counts.supporting}</div>
                                      <div class="pr-2 pl-2 text-warning">Not Sure: ${correction.vote_counts.not_sure}</div>
                                      <div class="pr-2 pl-2 text-danger">Not Supporting: ${correction.vote_counts.not_supporting}</div>
                                  </div>
                              </td>
                              <td class="d-none d-md-table-cell">${formatDate(correction.date)}</td>
                          `;
                          tableBody.appendChild(row);
                      });

                      for (let i = 1; i <= data.total_pages; i++) {
                          const pageLink = document.createElement('li');
                          pageLink.classList.add('page-item');
                          if (i === data.current_page) {
                              pageLink.classList.add('active');
                          }
                          pageLink.innerHTML = `
                              <a class="page-link" href="#" data-page="${i}">${i}</a>
                          `;
                          pageLink.addEventListener('click', function(e) {
                              e.preventDefault();
                              fetchCorrections(i);
                          });
                          paginationContainer.appendChild(pageLink);
                      }

                      if (typeof feather !== 'undefined') {
                          feather.replace();
                      }
                  })
                  .catch(error => {
                      console.error('Error fetching corrections:', error);
                      tableBody.innerHTML = `
                          <tr>
                              <td colspan="5" class="text-center text-danger">
                                  <div class="alert alert-danger" role="alert">
                                      <i class="align-middle me-2" data-feather="alert-triangle"></i>
                                      Failed to load corrections. 
                                      <br>Please check your network connection and try again.
                                  </div>
                              </td>
                          </tr>
                      `;
                      
                      if (typeof feather !== 'undefined') {
                          feather.replace();
                      }
                  });
          }

          fetchCorrections();
      });
    </script>
  </body>
</html>
<?php
require('../api/shared/verify_status.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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
              <a href="#">
                <h3>Lingobaraza</h3>
              </a>
            </div>
           <ul class="navbar-items">
              <li><a href="db_sentences.php">Contribute voice</a></li>
              <li><a href="contribute.php">Contribute Sentence</a></li>
           </ul> 
            <ul class="navbar-nav navbar-align">
              <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                  <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                  <img src="../img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="user" />
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
              <h1 class="h3 mb-3">Statistics Overtime</h1>
            </div>

            <div class="row">
              <div class="col-xl-6 col-xxl-5 d-flex">
                <div class="w-100">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col mt-0">
                              <h5 class="card-title">Recordings</h5>
                            </div>
                            <div class="col-auto">
                              <div class="stat text-primary">
                                <i class="align-middle" data-feather="music"></i>
                              </div>
                            </div>
                          </div>
                          <h1 class="mt-1 mb-3">0 <i class="user-card-hours">Notes</i></h1>
                          <div class="mb-0">
                            <span class="text-info">
                              <i class="mdi mdi-arrow-bottom-right"></i> 0%
                            </span>
                            <span class="text-muted">Recordings of Total Recordings</span>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col mt-0">
                              <h5 class="card-title">Rejected</h5>
                            </div>
                            <div class="col-auto">
                              <div class="stat text-danger">
                                <i class="align-middle" data-feather="x"></i>
                              </div>
                            </div>
                          </div>
                          <h1 class="mt-1 mb-3">0 <i class="user-card-hours">Minutes</i></h1>
                          <div class="mb-0">
                            <span class="text-danger">
                              <i class="mdi mdi-arrow-bottom-right"></i> 0%
                            </span>
                            <span class="text-muted">Minutes of Total Rejected</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col mt-0">
                              <h5 class="card-title">Accepted</h5>
                            </div>
                            <div class="col-auto">
                              <div class="stat text-success">
                                <i class="align-middle" data-feather="check"></i>
                              </div>
                            </div>
                          </div>
                          <h1 class="mt-1 mb-3" >0 <i class="user-card-hours">Minutes</i></h1>
                          <div class="mb-0">
                            <span class="text-success">
                              <i class="mdi mdi-arrow-bottom-right"></i> 0%
                            </span>
                            <span class="text-muted">Minutes of Total Accepted</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-xxl-7">
                <div class="card h-100 position-relative overflow-hidden border-0">
                  <div class="card-body custom-gradient card-hover-effect p-0" style="height: 200px;">
                    <div class="circle-decoration"></div>
                    <a href="./db_sentences.php" class="text-decoration-none">
                      <div class="hover-overlay h-100 d-flex flex-column justify-content-center align-items-center p-4">
                        <div class="icon-hover mb-3 animate-pulse">
                          <i data-feather="mic" class="text-white" style="width: 36px; height: 36px;"></i>
                        </div>
                        <p class="text-white fs-4 fw-bold mb-1">Speak</p>
                        <p class="text-white fs-4 fw-semibold mb-0 text-center">Contribute to the project by recording a sentence today.</p>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <h1 class="h3 mb-2">About the Project</h1>
              <div class="card">
                <div class="card-body">
                  <div id="about_project"></div>
                  <div class="row">
                    <div class="col-md-12 col-lg-6">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-3">
                              <img src="" alt="lead-1" class="img-fluid img-thumbnail" id="admin_image" />
                            </div>
                            <div class="col-9">
                              <p id="about_admin"></p>
                              <p id="admin_email"></p>
                            </div>
                          </div>
                        </div>
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
                    <a class="text-muted">&copy; Copyright 2024.</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <script src="../js/app.js"></script>
    <script src="../js/navbar_details.js"></script>
    <script>
      function findCardByTitle(titleText) {
        const cardTitles = document.querySelectorAll('.card-title');
        for (let title of cardTitles) {
          if (title.textContent.trim() === titleText) {
            return title.closest('.card');
          }
        }
        return null;
      }

      function updateStatistics(stats) {
        try {
          const recordingsCard = findCardByTitle('Recordings');
          if (recordingsCard) {
            recordingsCard.querySelector('.mt-1.mb-3').innerHTML = 
              `${stats.total_duration} <i class="user-card-hours">Recordings</i>`;
            recordingsCard.querySelector('.mb-0').innerHTML = 
              `<span class="text-info">${stats.total_percentage}%</span> 
              <span class="text-muted">Recordings of Total Recordings</span>`;
          }

          const acceptedCard = findCardByTitle('Accepted');
          if (acceptedCard) {
            acceptedCard.querySelector('.mt-1.mb-3').innerHTML = 
              `${stats.accepted_duration} <i class="user-card-hours">Recordings</i>`;
            acceptedCard.querySelector('.mb-0').innerHTML = 
              `<span class="text-success">${stats.accepted_percentage}%</span> 
              <span class="text-muted">Recordings of Total Accepted</span>`;
          }

          const rejectedCard = findCardByTitle('Rejected');
          if (rejectedCard) {
            rejectedCard.querySelector('.mt-1.mb-3').innerHTML = 
              `${stats.rejected_duration} <i class="user-card-hours">Recordings</i>`;
            rejectedCard.querySelector('.mb-0').innerHTML = 
              `<span class="text-danger">${stats.rejected_percentage}%</span> 
              <span class="text-muted">Recordings of Total Rejected</span>`;
          }
        } catch (error) {
        }
      }

      function updateProjectDetails(details) {
        try {
          const aboutProject = document.getElementById('about_project');
          if (aboutProject && details) {
            aboutProject.innerHTML = details.project_details || 'Project details not available';
          }
        } catch (error) {
        }
      }

      function updateAdminDetails(admins) {
        try {
          const adminContainer = document.querySelector('.col-md-12.col-lg-6');
          if (adminContainer && Array.isArray(admins)) {
            adminContainer.innerHTML = admins.map(admin => `
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-3">
                      <img
                        src="${admin.admin_image || '../img/avatars/avatar.png'}"
                        alt="Admin - ${admin.user_name}"
                        class="img-fluid img-thumbnail"
                        onerror="this.src='../img/avatars/avatar.png'"
                      />
                    </div>
                    <div class="col-9">
                      <p class="mb-2"><strong>${admin.user_name}</strong></p>
                      <p class="mb-3">${admin.details || 'No admin description available'}</p>
                      <p class="mb-0"><i class="align-middle me-1" data-feather="mail"></i> ${admin.email}</p>
                    </div>
                  </div>
                </div>
              </div>
            `).join('');
            
            if (typeof feather !== 'undefined') {
              feather.replace();
            }
          }
        } catch (error) {
        }
      }

      document.addEventListener('DOMContentLoaded', function() {
        fetch('../api/contributor/dashboard.php')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.status === 'error') {
              return;
            }
            
            if (data.statistics) updateStatistics(data.statistics);
            if (data.project_details) updateProjectDetails(data.project_details);
            if (data.admins) updateAdminDetails(data.admins);
          })
          .catch(error => {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.innerHTML = 'Error loading dashboard data. Please refresh the page or contact support.';
            document.querySelector('.content').prepend(errorDiv);
          });
      });
    </script>
  </body>
</html>
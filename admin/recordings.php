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

            <li class="sidebar-item active">
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
            <h1 class="h3 mb-3">User Recordings</h1>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table id="recordingsTable" class="table table-hover my-0">
                      <thead>
                        <tr>
                          <th class="d-none d-md-table-cell">User</th>
                          <th>Sentence</th>
                          <th class="d-none d-md-table-cell">Recording</th>
                          <th class="d-none d-md-table-cell">Status</th>
                          <th class="d-none d-md-table-cell">Recorded</th>
                          <th>Details</th>
                        </tr>
                      </thead>
                      <tbody id="recordingsTableBody">
                        <tr id="recordingsMessage" style="display:none;">
                          <td colspan="6" class="text-center text-info">No approved recordings found.</td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                      <button id="prevPage" class="btn btn-secondary" style="display:none;">Previous</button>
                      <span id="pageInfo"></span>
                      <button id="nextPage" class="btn btn-secondary" style="display:none;">Next</button>
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
      document.addEventListener('DOMContentLoaded', function() {
          let currentPage = 1;
          let totalPages = 0;

          function createNoRecordingsMessage(message, iconName, messageType = 'muted') {
              return `
                  <td colspan="6" class="text-center text-${messageType}">
                      <div class="py-4">
                          <i class="align-middle" data-feather="${iconName}" style="width: 48px; height: 48px;"></i>
                          <h5 class="mt-3">${message}</h5>
                          <p class="text-secondary">
                              ${messageType === 'muted' 
                                  ? 'There are currently no verified voice recordings in the system.' 
                                  : 'We couldn\'t retrieve the recordings at this time. Please try again later.'}
                          </p>
                      </div>
                  </td>
              `;
          }

          function fetchRecordings(page) {
              fetch(`../api/admin/verified_recordings.php?page=${page}`)
                  .then(response => {
                      if (!response.ok) {
                          throw new Error('Network response was not ok');
                      }
                      return response.json();
                  })
                  .then(data => {
                      const tableBody = document.getElementById('recordingsTableBody');
                      const recordingsMessage = document.getElementById('recordingsMessage');
                      const prevButton = document.getElementById('prevPage');
                      const nextButton = document.getElementById('nextPage');
                      const pageInfo = document.getElementById('pageInfo');

                      tableBody.innerHTML = '';

                      if (!data.voice_notes || data.voice_notes.length === 0) {
                        const noRecordingsRow = `
                            <tr>
                                <td colspan="6" class="text-center text-info">
                                    No recordings at the moment
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML = noRecordingsRow;
                        prevButton.style.display = 'none';
                        nextButton.style.display = 'none';
                        pageInfo.textContent = '';
                        return;
                      }

                      data.voice_notes.forEach(recording => {
                          const row = `
                              <tr>
                                  <td class="d-none d-md-table-cell">${recording.username || 'N/A'}</td>
                                  <td>${recording.original_sentence || 'No sentence'}</td>
                                  <td class="d-none d-md-table-cell">
                                      <audio src="../api/contributor/${recording.voice_note_path}" controls></audio>
                                  </td>
                                  <td class="d-none d-md-table-cell">${recording.status || 'Unknown'}</td>
                                  <td class="d-none d-md-table-cell">${recording.validation_date || 'No date'}</td>
                                  <td>
                                      <a href="./recordingsdetails.php?id=${recording.voice_note_id}">
                                          <button class="btn btn-info btn-sm">Details</button>
                                      </a>
                                  </td>
                              </tr>
                          `;
                          tableBody.innerHTML += row;
                      });

                      recordingsMessage.style.display = 'none';

                      totalPages = data.total_pages || 1;
                      currentPage = data.current_page || 1;

                      pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

                      prevButton.style.display = currentPage > 1 ? 'block' : 'none';
                      prevButton.onclick = () => {
                          if (currentPage > 1) {
                              fetchRecordings(currentPage - 1);
                          }
                      };

                      nextButton.style.display = currentPage < totalPages ? 'block' : 'none';
                      nextButton.onclick = () => {
                          if (currentPage < totalPages) {
                              fetchRecordings(currentPage + 1);
                          }
                      };
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      const recordingsMessage = document.getElementById('recordingsMessage');
                      recordingsMessage.innerHTML = createNoRecordingsMessage(
                          'Unable to Load Recordings', 
                          'alert-circle', 
                          'danger'
                      );
                      recordingsMessage.style.display = 'table-row';

                      document.getElementById('prevPage').style.display = 'none';
                      document.getElementById('nextPage').style.display = 'none';
                      document.getElementById('pageInfo').textContent = '';
                  });
          }

          fetchRecordings(1);
      });
    </script>
  </body>
</html>

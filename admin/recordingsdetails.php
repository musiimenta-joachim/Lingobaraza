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
            <h1 class="h3 mb-3">Recording Details</h1>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body" id="recordingDetailsContainer">
                    <div class="text-center py-5">
                      <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                      <p class="mt-2">Loading recording details...</p>
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
        const urlParams = new URLSearchParams(window.location.search);
        const recordingId = urlParams.get('id');

        if (!recordingId) {
          document.getElementById('recordingDetailsContainer').innerHTML = `
            <div class="text-center text-danger py-5">
              <h4>Error</h4>
              <p>No recording ID provided</p>
            </div>
          `;
          return;
        }
        
        fetch(`../api/admin/recordings_details.php?id=${recordingId}`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Could not fetch recording details');
            }
            return response.json();
          })
          .then(recording => {
            document.getElementById('recordingDetailsContainer').innerHTML = `
              <div class="mb-2">
                <div class="row">
                  <div class="col-6">
                    <label class="form-label">Submitted by</label>
                    <input type="text" value="${recording.submitter_name || 'N/A'}" class="form-control" disabled>
                  </div>
                  <div class="col-6">
                    <label class="form-label">Validated by</label>
                    <input type="text" value="${recording.validator_name || 'Not validated'}" class="form-control" disabled>
                  </div>
                </div>
              </div>
              <div class="mb-2">
                <div class="row">
                  <div class="col-6">
                    <label class="form-label">Date recorded</label>
                    <input type="text" value="${recording.recording_date || 'N/A'}" class="form-control" disabled>
                  </div>
                  <div class="col-6">
                    <label class="form-label">Date validated</label>
                    <input type="text" value="${recording.validated_date || 'Not validated'}" class="form-control" disabled>
                  </div>
                </div>
              </div>
              <div class="mb-2">
                <label class="form-label">Sentence</label>
                <input type="text" value="${recording.original_sentence || 'No sentence'}" class="form-control" disabled>
              </div>
              <div class="mb-2">
                <label class="form-label">Recording</label>
                <audio src="../api/contributor/${recording.voice_note_path}" controls class="form-control"></audio>
              </div>
              <div class="mb-2">
                <label class="form-label">Status</label>
                <input type="text" value="${recording.status || 'Unknown'}" class="form-control" disabled>
              </div>
            `;
          })
          .catch(error => {
            console.error('Error:', error);
            document.getElementById('recordingDetailsContainer').innerHTML = `
              <div class="text-center text-danger py-5">
                <h4>Error</h4>
                <p>Failed to load recording details</p>
              </div>
            `;
          });
      });
    </script>
  </body>
</html>

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
    <link rel="stylesheet" href="../css/style.css">
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
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <img src="../img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="user">
                                <span class="text-dark" id="nav_name"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="db_sentences.php"><i class="align-middle me-1" data-feather="mic"></i>Voice Contribution</a>
                                <a class="dropdown-item" href="contribute.php"><i class="align-middle me-1" data-feather="plus"></i>Add Sentence</a>
                                <a class="dropdown-item" href="sentences_to_translate.php"><i class="align-middle me-1" data-feather="refresh-cw"></i>Add Translation</a>
                                <a class="dropdown-item" href="profile.php"><i class="align-middle me-1" data-feather="user"></i>Profile</a>
                                <a class="dropdown-item" href="all_contributions.php"><i class="align-middle me-1" data-feather="pie-chart"></i>All Contributions</a>
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
                        <h1 class="h3 mb-3">Contributions</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover my-0">
                                        <thead>
                                            <tr>
                                                <th class="d-none d-md-table-cell">Sentence</th>
                                                <th>Recording</th>
                                                <th class="d-none d-md-table-cell">Sentence</th>
                                                <th class="d-none d-md-table-cell">Recording</th>
                                                <th class="d-none d-md-table-cell">Recorded</th>
                                            </tr>
                                        </thead>
                                        <tbody id="contributions-table">
                                        </tbody>
                                    </table>
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
      document.addEventListener("DOMContentLoaded", () => {
        const fetchContributions = async () => {
            try {
                const response = await fetch("../api/contributor/user_contributions.php");
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                const tbody = document.querySelector("#contributions-table");
                tbody.innerHTML = data.map(row => {
                    const audioElement = row.voice_note_path ? createAudioElement(row.voice_note_path) : null;
                    
                    return `
                        <tr>
                            <td class="d-none d-md-table-cell">${escapeHtml(row.sentence || "N/A")}</td>
                            <td>
                                ${audioElement ? audioElement : "No recording available"}
                            </td>
                            <td class="d-none d-md-table-cell">${escapeHtml(row.validation_status || "Not validated")}</td>
                            <td class="d-none d-md-table-cell">${escapeHtml(row.voice_note_status || "Not validated")}</td>
                            <td class="d-none d-md-table-cell">${row.voice_note_id ? "Yes" : "No"}</td>
                        </tr>
                    `;
                }).join('');

            } catch (error) {
                const tbody = document.querySelector("#contributions-table");
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">
                    Error loading contributions. Please try again later. Error: ${escapeHtml(error.message)}
                </td></tr>`;
            }
        };

        const createAudioElement = (path) => {
            const cleanPath = path.replace(/\\/g, '/');
            const audioPath = cleanPath.startsWith('../') ? cleanPath : `../api/contributor/audio/${cleanPath}`;
            
            return `
                <div class="audio-container">
                    <audio
                        src="${escapeHtml(audioPath)}"
                        controls
                        class="form-control"
                        onloadeddata="this.classList.add('loaded')"
                        onerror="handleAudioError(this)"
                        preload="metadata"
                    >
                        Your browser does not support the audio element.
                    </audio>
                    <div class="audio-error-message" style="display: none; color: red;"></div>
                </div>
            `;
        };

        const escapeHtml = (unsafe) => {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        };

        window.handleAudioError = (audioElement) => {
            const container = audioElement.closest('.audio-container');
            const errorMessage = container.querySelector('.audio-error-message');
            errorMessage.textContent = "Error loading audio file. Please try again later.";
            errorMessage.style.display = 'block';
            audioElement.style.display = 'none';
          };

          fetchContributions();
      });
    </script>
</body>
</html>

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
    <title>Lingobaraza - Sentences to Translate</title>
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
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                  <i class="align-middle" data-feather="settings"></i>
                </a>
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                  <img src="../img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="user"/>
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
              <h1 class="h3 mb-3">Sentences to Translate</h1>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover my-0">
                        <thead>
                          <tr>
                            <th>Sentence</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody id="sentencesTableBody">
                        </tbody>
                      </table>
                    </div>
                    <div id="loadingIndicator" class="text-center my-3 d-none">
                      <div class="spinner-border text-primary" role="status">
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
    async function fetchSentences() {
    const tableBody = document.getElementById('sentencesTableBody');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    try {
        loadingIndicator.classList.remove('d-none');
        
        const response = await fetch('../api/contributor/sentences_to_translate.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        tableBody.innerHTML = '';
        
        const baseSentences = data.base_sentences || [];
        const preferredLanguages = data.preferred_languages || [];
        
        if (!Array.isArray(baseSentences) || baseSentences.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="2" class="text-center">
                    No sentences available for translation at this time.
                </td>
            `;
            tableBody.appendChild(row);
            return;
        }
        
        baseSentences.forEach(item => {
            const sentence = item.english_sentence;
            const sentenceId = item.base_id;

            if (!sentenceId || !sentence) {
                return;
            }

            const row = document.createElement('tr');
            
            const sentenceCell = document.createElement('td');
            sentenceCell.className = 'text-break';
            sentenceCell.textContent = sentence;
            
            const actionCell = document.createElement('td');
            
            // Create a dropdown for language selection
            const languageSelect = document.createElement('select');
            languageSelect.className = 'form-select';
            
            // Populate language options dynamically
            preferredLanguages.forEach(lang => {
                const langLower = lang.toLowerCase();
                // Check if the language exists in the item
                if (item.hasOwnProperty(langLower) && item[langLower] === '*') {
                    const option = document.createElement('option');
                    option.value = langLower;
                    option.textContent = lang;
                    languageSelect.appendChild(option);
                }
            });
            
            const translateButton = document.createElement('button');
            translateButton.className = 'btn btn-info mt-2';
            translateButton.textContent = 'Translate';
            
            translateButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const selectedLanguage = languageSelect.value;
                
                if (saveSentenceInfo(sentenceId, sentence, selectedLanguage)) {
                    window.location.href = `./translate.php?sentence_id=${sentenceId}&language=${selectedLanguage}`;
                } else {
                    alert('Error saving sentence information. Please try again.');
                }
            });
            
            actionCell.appendChild(languageSelect);
            actionCell.appendChild(translateButton);
            
            row.appendChild(sentenceCell);
            row.appendChild(actionCell);
            tableBody.appendChild(row);
        });
        
    } catch (error) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="2">
                    <div class="alert alert-danger">
                        Error loading sentences: ${error.message}
                    </div>
                </td>
            </tr>
        `;
        console.error('Fetch error:', error);
    } finally {
        loadingIndicator.classList.add('d-none');
    }
}

function saveSentenceInfo(sentenceId, sentence, language) {
    try {
        if (!sentenceId || !sentence || !language) {
            return false;
        }
        
        const cleanSentenceId = String(sentenceId).trim();
        const cleanSentence = String(sentence).trim();
        const cleanLanguage = String(language).trim();
        
        sessionStorage.setItem('currentSentenceId', cleanSentenceId);
        sessionStorage.setItem('currentSentence', cleanSentence);
        sessionStorage.setItem('currentLanguage', cleanLanguage);
        
        return true;
    } catch (error) {
        return false;
    }
}

        function escapeHtml(unsafe) {
            if (unsafe === null || unsafe === undefined) return '';
            return String(unsafe)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

      document.addEventListener('DOMContentLoaded', fetchSentences);
    </script>

    <script src="../js/app.js"></script>
    <script src="../js/navbar_details.js"></script>
  </body>
</html>
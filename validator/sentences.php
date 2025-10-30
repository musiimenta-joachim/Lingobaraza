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
              <a class="sidebar-link" href="listen.php">
                <i class="align-middle" data-feather="headphones"></i>
                <span class="align-middle">Listen</span>
              </a>
            </li>

            <li class="sidebar-item active">
              <a class="sidebar-link" href="sentences.php">
                <i class="align-middle" data-feather="file-text"></i>
                <span class="align-middle">Sentences</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="vote.php">
                <i class="align-middle" data-feather="inbox"></i>
                <span class="align-middle">Vote</span>
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
                    alt="Expert"
                  />
                  <span class="text-dark" id="nav_name"></span>
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main class="content">
          <div class="container-fluid p-0">
            <h1 class="h3 mb-3" style="margin-top: -1rem !important">
              Sentences to validate
            </h1>

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
      let language_id = null;

      function escapeHtml(unsafe) {
          if (unsafe === null || unsafe === undefined) return '';
          return String(unsafe)
              .replace(/&/g, "&amp;")
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/"/g, "&quot;")
              .replace(/'/g, "&#039;");
      }

      function saveSentenceInfo(sentenceId, sentence, languageId) {
          try {
              if (!sentenceId) {
                  return false;
              }
              
              if (!sentence) {
                  return false;
              }

              if (!languageId) {
                  return false;
              }

              const cleanSentenceId = String(sentenceId).trim();
              const cleanSentence = String(sentence).trim();
              const cleanLanguageId = String(languageId).trim();
              
              sessionStorage.setItem('currentSentenceId', cleanSentenceId);
              sessionStorage.setItem('currentSentence', cleanSentence);
              sessionStorage.setItem('currentLanguage', cleanLanguageId);
              
              const storedId = sessionStorage.getItem('currentSentenceId');
              const storedSentence = sessionStorage.getItem('currentSentence');
              const storedLanguageId = sessionStorage.getItem('currentLanguage');
              
              return storedId && storedSentence && storedLanguageId;
              
          } catch (error) {
              return false;
          }
      }

      async function fetchSentences() {
          const tableBody = document.getElementById('sentencesTableBody');
          
          try {
              const response = await fetch('../api/validator/sentences.php');
              if (!response.ok) {
                  throw new Error(`HTTP error! status: ${response.status}`);
              }
              
              const data = await response.json();
              
              if (data.language_ids && data.language_ids.length > 0) {
                  language_id = data.language_ids[0];
              }
              
              tableBody.innerHTML = '';
              
              const sentences = data.sentences;
              
              if (!Array.isArray(sentences) || sentences.length === 0) {
                  const row = document.createElement('tr');
                  row.innerHTML = `
                      <td colspan="2" class="text-center">
                          No sentences available for translation at this time.
                      </td>
                  `;
                  tableBody.appendChild(row);
                  return;
              }
              
              sentences.forEach(item => {
                  const sentenceId = item.sentence_id;
                  const sentence = item.sentence;
                  const currentLanguageId = item.language_id || language_id;

                  const row = document.createElement('tr');
                  
                  const sentenceCell = document.createElement('td');
                  sentenceCell.className = 'text-break';
                  sentenceCell.textContent = sentence;
                  
                  const actionCell = document.createElement('td');
                  const translateLink = document.createElement('a');
                  translateLink.href = `./sentencedetails.php?sentence_id=${sentenceId}&sentence=${encodeURIComponent(sentence)}&language=${encodeURIComponent(currentLanguageId)}`;
                  translateLink.className = 'btn btn-info';
                  translateLink.textContent = 'Validate';
                  
                  translateLink.addEventListener('click', function(e) {
                      e.preventDefault();
                      
                      if (saveSentenceInfo(sentenceId, sentence, currentLanguageId)) {
                          window.location.href = this.href;
                      } else {
                          alert('Error saving sentence information. Please try again.');
                      }
                  });
                  
                  actionCell.appendChild(translateLink);
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
          }
      }

      document.addEventListener('DOMContentLoaded', fetchSentences);
    </script>
    <script src="../js/app.js"></script>
    <script src="../js/validator_nav.js"></script>
  </body>
</html>

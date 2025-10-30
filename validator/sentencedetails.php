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
              Approve or Suggest
            </h1>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <form id="approvalForm">
                      <div class="mb-3">
                        <label class="form-label">Sentence</label>
                        <div class="row">
                          <div class="col-md-8 mb-1">
                            <input
                              type="text"
                              class="form-control"
                              id="retrieved_sentence"
                              disabled
                            />
                            <input 
                              type="hidden" 
                              id="sentence_id" 
                              name="sentence_id"
                            />
                          </div>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="retrieved_language" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="sentence_status" class="form-select">
                          <option value="suggestion">Suggestion</option>
                          <option value="approved">Approve</option>
                          <option value="rejected">Reject</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Correction if any</label>
                        <input
                          type="text"
                          class="form-control"
                          name="expert_suggestion"
                        />
                      </div>

                      <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                          Submit
                        </button>
                      </div>
                      
                      <div id="formMessage" class="mt-3"></div>
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
      document.addEventListener('DOMContentLoaded', function() {
        const sentenceId = sessionStorage.getItem('currentSentenceId');
        const sentence = sessionStorage.getItem('currentSentence');
        const language = sessionStorage.getItem('currentLanguage');
        
        let displayLanguage = 'Unknown';
        
        if (sentenceId) {
            document.getElementById('retrieved_sentence').value = sentence || '';
            document.getElementById('sentence_id').value = sentenceId;

            switch(language) {
                case '1':
                    displayLanguage = 'English';
                    break;
                case '2':
                    displayLanguage = 'Luganda';
                    break;
                case '3':
                    displayLanguage = 'Runyankole';
                    break;
                default:
                    console.warn('Unexpected language value:', language);
            }

            document.getElementById('retrieved_language').value = displayLanguage;
        } else {
        }

        document.getElementById('approvalForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('../api/validator/validate_sentence.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageContainer = document.getElementById('formMessage');
                
                if (data.success) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                    this.reset();
                    sessionStorage.removeItem('currentSentenceId');
                    sessionStorage.removeItem('currentSentence');
                    sessionStorage.removeItem('currentLanguage');

                    setTimeout(() => {
                        window.location.href = 'sentences.php';
                    }, 2000);
                } else {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                const messageContainer = document.getElementById('formMessage');
                messageContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        An error occurred. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            });
        });
      });
    </script>
    <script src="../js/app.js"></script>
    <script src="../js/validator_nav.js"></script>
  </body>
</html>
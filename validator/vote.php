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

            <li class="sidebar-item">
              <a class="sidebar-link" href="sentences.php">
                <i class="align-middle" data-feather="file-text"></i>
                <span class="align-middle">Sentences</span>
              </a>
            </li>

            <li class="sidebar-item active">
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
              Voting Page
            </h1>

            <div class="row" id="corrections-container">
              <div class="col-md-6 col-lg-4">
                <form action="" method="post">
                  <div class="card h-100">
                    
                  </div>
                </form>
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
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const correctionsContainer = document.getElementById('corrections-container');
        
        if (!correctionsContainer) {
          return;
        }

        fetch('../api/validator/expert_suggestions.php')
          .then(response => {
            
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
          })
          .then(data => {
            
            correctionsContainer.innerHTML = '';
            
            if (data.length === 0) {
              correctionsContainer.innerHTML = `
                <div class="col-12">
                  <div class="card">
                    <div class="card-body text-center">
                      <p class="card-text">No suggestions available at the moment.</p>
                    </div>
                  </div>
                </div>
              `;
            } else {
              data.forEach(item => {
                const card = document.createElement('div');
                card.classList.add('col-md-6', 'col-lg-4');
                card.innerHTML = `
                  <div class="card h-100">
                    <div class="card-body">
                      <p>Original: ${item.sentence}</p>
                      <p>Suggestion: ${item.correction}</p>
                      <div class="row">
                        <div class="col-4">
                          <button class="btn btn-primary w-100" data-correction-id="${item.correction_id}" data-vote="supporting">Yes</button>
                        </div>
                        <div class="col-5">
                          <button class="btn btn-secondary w-100" data-correction-id="${item.correction_id}" data-vote="not_sure">Not Sure</button>
                        </div>
                        <div class="col-3">
                          <button class="btn btn-danger w-100" data-correction-id="${item.correction_id}" data-vote="not_supporting">No</button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
                correctionsContainer.appendChild(card);
              });
            }
          })
          .catch(error => {
            if (correctionsContainer) {
              correctionsContainer.innerHTML = `
                <div class="col-12">
                  <div class="card">
                    <div class="card-body text-center">
                      <p class="card-text">Error loading suggestions: ${error.message}</p>
                    </div>
                  </div>
                </div>
              `;
            }
          });

        correctionsContainer.addEventListener('click', function(event) {
          const button = event.target.closest('button[data-correction-id]');
          if (button) {
            const correctionId = button.dataset.correctionId;
            const vote = button.dataset.vote;
            
            fetch('../api/validator/vote.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ 
                correction_id: correctionId, 
                vote: vote 
              })
            })
            .then(response => {
              if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
              }
              return response.json();
            })
            .then(data => {
              button.closest('.card').remove();
            })
            .catch(error => {
              alert('Failed to submit vote. Please try again.');
            });
          }
        });
      });
    </script>
    <script src="../js/validator_nav.js"></script>
  </body>
</html>

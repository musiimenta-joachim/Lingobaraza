<!DOCTYPE html>
<html lang="en">
  <st>
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

            <li class="sidebar-item active">
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
              Recordings to Validate
            </h1>

            <div class="row">
              <div class="col-md-8 col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <p id="sentence-text"></p>
                    <audio
                      id="sentence-audio"
                      class="form-control"
                      controls
                      controlsList="nodownload"
                    ></audio>
                    <div class="mb-3 mt-4">
                      <button
                        class="btn btn-primary"
                        onclick="approveSentence()"
                      >
                        Accept
                      </button>
                      <button class="btn btn-danger" onclick="rejectSentence()">
                        Reject
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5>Approved Sentences</h5>
                    <div id="approved-sentences"></div>
                    <form action="" method="post">
                      <button type="submit" class="btn btn-success mt-3">
                        Submit Approvals
                      </button>
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

    <script src="../js/app.js"></script>
    <script>
        let sentences = [];
        let currentIndex = 0;
        let approvedSentences = [];
        let validationResults = [];

        async function fetchSentences() {
            try {
                document.getElementById('sentence-text').textContent = 'Loading sentences...';
                
                const response = await fetch("../api/validator/user_voice_notes.php", {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                sentences = data.voice_notes.map((note) => ({
                    id: note.voice_note_id,
                    text: note.sentence,
                    audio: "../api/contributor/" + note.voice_note_path,
                    language: data.processed_languages[note.language_id - 1] || 'Unknown'
                }));

                if (sentences.length === 0) {
                    document.getElementById('sentence-text').textContent = 'No sentences available for validation.';
                    document.getElementById('sentence-audio').style.display = 'none';
                    return;
                }

                displaySentence();

            } catch (error) {
                document.getElementById('sentence-text').textContent = 'Error loading sentences. Please try again later.';
            }
        }

        function displaySentence() {
            if (currentIndex < sentences.length) {
                const currentSentence = sentences[currentIndex];
                
                document.getElementById("sentence-text").textContent = 
                    `[${currentSentence.language}] ${currentSentence.text}`;
                
                const audioElement = document.getElementById("sentence-audio");
                audioElement.src = currentSentence.audio;
                
                audioElement.style.display = 'block';
                audioElement.load();
            } else {
                document.getElementById("sentence-text").textContent = "No more sentences to validate.";
                document.getElementById("sentence-audio").style.display = 'none';
                
                document.querySelector('button[onclick="approveSentence()"]').disabled = true;
                document.querySelector('button[onclick="rejectSentence()"]').disabled = true;
            }
        }

        function approveSentence() {
            if (currentIndex < sentences.length) {
                const currentSentence = sentences[currentIndex];
                
                validationResults.push({
                    id: currentSentence.id,
                    status: 'approved'
                });

                approvedSentences.push({
                    id: currentSentence.id,
                    text: currentSentence.text,
                    audio: currentSentence.audio,
                    status: 'approved'
                });

                updateApprovedSentencesList();
                nextSentence();
            }
        }

        function rejectSentence() {
            if (currentIndex < sentences.length) {
                const currentSentence = sentences[currentIndex];
                
                validationResults.push({
                    id: currentSentence.id,
                    status: 'rejected'
                });

                approvedSentences.push({
                    id: currentSentence.id,
                    text: currentSentence.text,
                    audio: currentSentence.audio,
                    status: 'rejected'
                });

                updateApprovedSentencesList();
                nextSentence();
            }
        }

        function nextSentence() {
            currentIndex++;
            displaySentence();
        }

        function updateApprovedSentencesList() {
            const approvedDiv = document.getElementById("approved-sentences");
            
            approvedDiv.innerHTML = '';

            approvedSentences.forEach((sentence, index) => {
                const sentenceItem = document.createElement("div");
                sentenceItem.classList.add("approved-item", "mb-3", 
                    sentence.status === 'approved' ? 'bg-success-subtle' : 'bg-danger-subtle'
                );
                sentenceItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-1">${sentence.text} 
                            <span class="badge ${sentence.status === 'approved' ? 'bg-success' : 'bg-danger'}">
                                ${sentence.status.charAt(0).toUpperCase() + sentence.status.slice(1)}
                            </span>
                        </p>
                        <button class="btn btn-sm btn-danger" onclick="removeApprovedSentence(${index})">Remove</button>
                    </div>
                    <audio src="${sentence.audio}" class="form-control" controls controlsList="nodownload"></audio>`;
                approvedDiv.appendChild(sentenceItem);
            });
        }

        function removeApprovedSentence(index) {
            const removedSentence = approvedSentences.splice(index, 1)[0];
            
            const validationIndex = validationResults.findIndex(result => 
                result.id === removedSentence.id && result.status === removedSentence.status
            );
            if (validationIndex !== -1) {
                validationResults.splice(validationIndex, 1);
            }
            
            updateApprovedSentencesList();
        }

        function submitValidationResults() {
            if (validationResults.length === 0) {
                alert('No sentences validated to submit.');
                return;
            }

            const dataToSubmit = {
                validation_results: validationResults
            };

            fetch('../api/validator/validate_voice_note.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSubmit)
            })
            .then(response => {
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    alert('Validation results submitted successfully!');
                    validationResults = [];
                    approvedSentences = [];
                    updateApprovedSentencesList();
                    fetchSentences();
                } else {
                    alert('Failed to submit validation results: ' + result.message);
                }
            })
            .catch(error => {
                alert('Error submitting validation results. Please try again.');
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchSentences();

            document.querySelector('button[type="submit"]').addEventListener('click', (e) => {
                e.preventDefault();
                submitValidationResults();
            });
        });
    </script>
    <script src="../js/validator_nav.js"></script>
  </body>
</html>

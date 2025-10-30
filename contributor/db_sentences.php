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
    <link rel="stylesheet" href="../css/contribute.css" />
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
              <h1 class="h3 mb-3">Record</h1>
            </div>

            <div class="row">
              <div class="col-md-8 col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <p id="current-sentence">
                      Loading sentences...
                    </p>
                    <div style="margin-left: 1.5rem; margin-top: 2rem !important">
                      <button class="mic-toggle" id="mic">
                        <span class="d" data-feather="mic"></span>
                      </button>
                    </div>
                    <audio class="playback" controls></audio>
                    <div class="mb-3 mt-4">
                      <button class="btn btn-primary" id="accept-btn">Accept</button>
                      <button class="btn btn-danger" id="reject-btn">Redo</button>
                      <button class="btn btn-secondary" id="next-btn">Next Sentence</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5>Recorded Sentences</h5>
                    <div id="recorded-list">
                    </div>
                    <form id="recordings-form" method="post">
                      <button type="submit" class="btn btn-success mt-2">
                        Submit Recordings
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
      document.addEventListener("DOMContentLoaded", function () {
        const style = document.createElement("style");
        style.textContent = `
          #current-sentence {
            display: block !important;
            min-height: 20px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
          }
        `;
        document.head.appendChild(style);

        const elements = {
          mic_btn: document.querySelector("#mic"),
          playback: document.querySelector(".playback"),
          accept_btn: document.querySelector("#accept-btn"),
          reject_btn: document.querySelector("#reject-btn"),
          next_btn: document.querySelector("#next-btn"),
          current_sentence: document.querySelector("#current-sentence"),
          recordedList: document.querySelector("#recorded-list"),
          submitButton: document.querySelector(".btn-success"),
        };

        Object.entries(elements).forEach(([key, element]) => {
        });

        let can_record = false;
        let is_recording = false;
        let recorder = null;
        let chunks = [];
        let savedAudioBlob = null;
        let recordingCount = 0;
        let sentences = [];
        let currentIndex = 0;

        async function fetchSentences() {
          try {
            const response = await fetch("../api/contributor/db_sentences.php", {
              method: "GET",
              headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
              },
              credentials: "include",
            });

            if (!response.ok) {
              const errorText = await response.text();
              throw new Error(
                `HTTP error! status: ${response.status}, body: ${errorText}`
              );
            }

            const data = await response.json();

            if (data.data && Array.isArray(data.data) && data.data.length > 0) {
              sentences = data.data;

              currentIndex = 0;
              displayCurrentSentence();
            } else {
              displayError("No sentences available");
            }
          } catch (error) {
            displayError("Error loading sentences. Please try again later.");
          }
        }

        function displayError(message) {
          if (elements.current_sentence) {
            elements.current_sentence.textContent = message;
            elements.current_sentence.style.display = "block";
            enableControls(false);
            updateSubmitButtonVisibility();
          } else {
          }
        }

        function enableControls(enabled) {
          if (elements.mic_btn) elements.mic_btn.disabled = !enabled;
          if (elements.accept_btn) elements.accept_btn.disabled = !enabled;
          if (elements.reject_btn) elements.reject_btn.disabled = !enabled;
          if (elements.next_btn) elements.next_btn.disabled = !enabled;
        }

        function updateSubmitButtonVisibility() {
          if (elements.submitButton) {
            elements.submitButton.style.display =
              recordingCount > 0 ? "block" : "none";
          }
        }

        function displayCurrentSentence() {

          if (!elements.current_sentence) {
            return;
          }

          if (currentIndex < sentences.length) {
            const currentSentence = sentences[currentIndex].sentence;

            elements.current_sentence.style.display = "block";
            elements.current_sentence.innerHTML = currentSentence;

            enableControls(true);
          } else {
            elements.current_sentence.textContent =
              "No more sentences available. You can submit your recordings now.";
            enableControls(false);
          }
          updateSubmitButtonVisibility();
        }

        function SetupAudio() {
          if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices
              .getUserMedia({ audio: true })
              .then(SetupStream)
              .catch((err) => {
                displayError(
                  "Unable to access microphone. Please check your permissions."
                );
              });
          } else {
            displayError("Audio recording is not supported in your browser.");
          }
        }

        function SetupStream(stream) {
          recorder = new MediaRecorder(stream);

          recorder.ondataavailable = (e) => {
            chunks.push(e.data);
          };

          recorder.onstop = () => {
            savedAudioBlob = new Blob(chunks, { type: "audio/ogg; codecs=opus" });
            chunks = [];
            const audioURL = URL.createObjectURL(savedAudioBlob);
            if (elements.playback) {
              elements.playback.src = audioURL;
            }
          };

          can_record = true;
          ToggleMic();
        }

        function ToggleMic() {
          if (!can_record) {
            SetupAudio();
            return;
          }
          is_recording = !is_recording;

          if (is_recording) {
            recorder.start();
            if (elements.mic_btn) elements.mic_btn.classList.add("is-recording");
          } else {
            recorder.stop();
            if (elements.mic_btn) elements.mic_btn.classList.remove("is-recording");
          }
        }

        function createRecordingElement(sentenceId, sentenceText, audioBlob) {
          const newRecordingDiv = document.createElement("div");
          newRecordingDiv.classList.add("recording-item", "mb-3");

          const label = document.createElement("strong");
          label.textContent = `Recording ${recordingCount}: `;
          newRecordingDiv.appendChild(label);

          const sentenceTextElem = document.createElement("p");
          sentenceTextElem.textContent = sentenceText;
          newRecordingDiv.appendChild(sentenceTextElem);

          const audio = document.createElement("audio");
          audio.controls = true;
          audio.src = URL.createObjectURL(audioBlob);
          newRecordingDiv.appendChild(audio);

          const sentenceIdInput = document.createElement("input");
          sentenceIdInput.type = "hidden";
          sentenceIdInput.name = `sentence_id_${recordingCount}`;
          sentenceIdInput.value = sentenceId;
          newRecordingDiv.appendChild(sentenceIdInput);

          const audioInput = document.createElement("input");
          audioInput.type = "hidden";
          audioInput.name = `audio_${recordingCount}`;
          audioInput.value = URL.createObjectURL(audioBlob);
          newRecordingDiv.appendChild(audioInput);

          return newRecordingDiv;
        }

        if (elements.accept_btn) {
          elements.accept_btn.addEventListener("click", () => {
            if (savedAudioBlob && elements.recordedList) {
              recordingCount++;
              const currentSentence = sentences[currentIndex];
              const recordingElement = createRecordingElement(
                currentSentence.sentence_id,
                currentSentence.sentence,
                savedAudioBlob
              );
              elements.recordedList.appendChild(recordingElement);

              if (elements.playback) elements.playback.src = "";
              savedAudioBlob = null;
              currentIndex++;
              displayCurrentSentence();
              updateSubmitButtonVisibility();
            }
          });
        }

        if (elements.next_btn) {
          elements.next_btn.addEventListener("click", () => {
            currentIndex++;
            displayCurrentSentence();
          });
        }

        if (elements.reject_btn) {
          elements.reject_btn.addEventListener("click", () => {
            if (elements.playback) elements.playback.src = "";
            savedAudioBlob = null;
          });
        }

        if (elements.submitButton) {
          elements.submitButton.addEventListener("click", async (e) => {
            e.preventDefault();

            if (recordingCount > 0) {
              const formData = new FormData();

              const recordings =
                elements.recordedList.getElementsByClassName("recording-item");
              for (let i = 0; i < recordings.length; i++) {
                const sentenceId = recordings[i].querySelector(
                  `input[name="sentence_id_${i + 1}"]`
                ).value;
                const audioBlob = await fetch(
                  recordings[i].querySelector(`input[name="audio_${i + 1}"]`).value
                ).then((r) => r.blob());

                formData.append(`sentence_id_${i + 1}`, sentenceId);
                formData.append(
                  `audio_${i + 1}`,
                  audioBlob,
                  `recording_${i + 1}.ogg`
                );
              }

              try {
                const response = await fetch(
                  "../api/contributor/recorded_sentences.php",
                  {
                    method: "POST",
                    body: formData,
                  }
                );

                if (response.ok) {
                  alert("Recordings submitted successfully!");
                  window.location.reload();
                } else {
                  alert("Error submitting recordings. Please try again.");
                }
              } catch (error) {
                alert("Error submitting recordings. Please try again.");
              }
            } else {
              alert("Please record at least one sentence before submitting.");
            }
          });
        }

        if (elements.mic_btn) {
          elements.mic_btn.addEventListener("click", ToggleMic);
        }

        const debugCurrentSentence = () => {
          const element = document.getElementById("current-sentence");
        };

        debugCurrentSentence();
        fetchSentences();
      });
    </script>
    <script src="../js/navbar_details.js"></script>
  </body>
</html>
<?php
require('../api/shared/verify_status.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="shortcut icon" href="../img/icons/icon-48x48.png"/>
    <title>Lingobaraza</title>
    <link href="../css/app.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <link rel="stylesheet" href="../css/contribute.css"/>
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
      .playback {
        margin-bottom: 1rem;
        box-shadow: 0px 0px 1rem rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
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
                    Submit Translation
        
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
              <h1 class="h3 mb-3">Translate</h1>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <form action="" method="post" id="translateForm">
                      <div class="mb-3">
                        <label class="form-label">Sentence to Translate</label>
                        <input type="text" class="form-control" id="retrived_sentence" disabled />
                        <input type="hidden" id="sentence_id" name="sentence_id" />
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-md-4">
                            <label for="language" class="form-label">Select Language</label>
                            <select name="language" id="language" class="form-select" required>
                              <option value="english">English</option>
                              <option value="runyankole">Runyankole</option>
                              <option value="luganda">Luganda</option>
                            </select>
                          </div>
                          <div class="col-md-8">
                            <label for="sentence" class="form-label">
                              Enter Translation of the above Sentence
                            </label>
                            <input type="text" class="form-control" name="sentence" id="sentence" required />
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label mb-5">Its Voice Recording</label>
                        <div style="margin-left: 1.5rem">
                          <button type="button" class="mic-toggle" id="mic">
                            <span data-feather="mic"></span>
                          </button>
                        </div>
                        <div id="recordingStatus" class="mt-2 is-hidden">
                          Recording in progress...
                        </div>
                        <audio class="playback form-control" controls style="display: none;"></audio>
                      </div>
                      <div class="mt-2">
                        <button class="btn btn-primary" type="submit" name="submit">
                          Submit Translation
                        </button>
                      </div>
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
    <script src="../js/navbar_details.js"></script>
    
    <script>
    let isRecording = false;
    let mediaRecorder;
    let audioChunks = [];
    let hasRecording = false;
    let stream = null;

    document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sentenceId = urlParams.get('sentence_id');
    const urlLanguage = urlParams.get('language');
    
    const retrievedSentenceInput = document.getElementById('retrived_sentence');
    const sentenceIdInput = document.getElementById('sentence_id');
    const languageSelect = document.getElementById('language');
    const recordingStatus = document.getElementById('recordingStatus');
    
    const storedSentence = sessionStorage.getItem('currentSentence');
    const storedSentenceId = sessionStorage.getItem('currentSentenceId');
    const storedLanguage = sessionStorage.getItem('currentLanguage');
    
    if (urlLanguage || storedLanguage) {
        const languageToSet = (urlLanguage || storedLanguage).toLowerCase();
        const languageOptions = Array.from(languageSelect.options).map(option => option.value);
        
        if (languageOptions.includes(languageToSet)) {
            languageSelect.value = languageToSet;
        }
    }
        
        if (storedSentence && storedSentenceId && storedSentenceId === sentenceId) {
            retrievedSentenceInput.value = storedSentence;
            sentenceIdInput.value = storedSentenceId;
        } else if (sentenceId) {
            fetchSentenceById(sentenceId);
        } else {
            alert('Invalid page access. Please select a sentence to translate from the previous page.');
            window.location.href = 'sentences_to_translate.php';
        }
        
        document.getElementById('translateForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const currentSentenceId = document.getElementById('sentence_id').value;
            
            if (!currentSentenceId || currentSentenceId.trim() === '') {
                alert('Missing sentence ID. Please return to the sentence list and try again.');
                return;
            }
            
            const audioElement = document.querySelector('.playback');
            if (!hasRecording && (!audioElement || !audioElement.src || audioElement.src === '')) {
                alert('Please record audio before submitting');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('sentence_id', currentSentenceId);
            
            if (audioChunks.length > 0) {
                const audioBlob = new Blob(audioChunks, { type: 'audio/ogg; codecs=opus' });
                formData.append('audio_file', audioBlob, 'recording.ogg');
            }

            console.log("Form Data Entries:");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: `, value);
                if (value instanceof File) {
                    console.log(`${key} size: `, value.size);
                }
            }

            try {
                if (isRecording && mediaRecorder) {
                    mediaRecorder.stop();
                    isRecording = false;
                    document.getElementById('mic').classList.remove('recording');
                    recordingStatus.style.display = 'none';
                }

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                const response = await fetch('../api/contributor/add_translation.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                console.log("API Response:", data);

                console.log("Response Status:", response.status);
                console.log("Response OK:", response.ok);

                if (data.status === 'success') {
                    alert('Translation added successfully!');
                    sessionStorage.removeItem('currentSentenceId');
                    sessionStorage.removeItem('currentSentence');
                    window.location.href = 'sentences_to_translate.php';
                } else {
                    console.error("Translation Submission Error:", data.message);
                    alert(data.message || 'Error adding translation');
                }
            } catch (error) {
                console.error("Submission Error:", error);
                console.error("Error Name:", error.name);
                console.error("Error Message:", error.message);
                alert('Error submitting translation. Please try again.');
            }
        });

        initializeMicrophoneHandler();
    });

    async function fetchSentenceById(sentenceId) {
        if (!sentenceId) {
            return;
        }

        try {
            const response = await fetch(`../api/contributor/get_sentence.php?sentence_id=${sentenceId}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            console.log("Fetched Sentence Data:", data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            document.getElementById('retrived_sentence').value = data.sentence;
            document.getElementById('sentence_id').value = sentenceId;
            
            sessionStorage.setItem('currentSentenceId', sentenceId);
            sessionStorage.setItem('currentSentence', data.sentence);
            
        } catch (error) {
            console.error("Sentence Fetching Error:", error);
            alert('Error loading sentence. Please try again or return to the sentence list.');
            document.getElementById('retrived_sentence').value = 'Error loading sentence. Please try again.';
        }
    }

    function initializeMicrophoneHandler() {
      const micButton = document.getElementById('mic');
      const recordingStatus = document.getElementById('recordingStatus');
      
      micButton.addEventListener('click', async () => {
          if (!isRecording) {
              try {
                  stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                  mediaRecorder = new MediaRecorder(stream);
                  
                  mediaRecorder.ondataavailable = (event) => {
                      if (event.data.size > 0) {
                          audioChunks.push(event.data);
                          
                          console.log("Audio Chunk Added:", {
                              size: event.data.size,
                              type: event.data.type
                          });
                      }
                  };

                  mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    const audioElement = document.querySelector('.playback');
                      
                    audioElement.src = audioUrl;
                    audioElement.style.display = 'block';
                    
                    console.log("Audio Recording Details:", {
                        totalChunks: audioChunks.length,
                        blobSize: audioBlob.size,
                        blobType: audioBlob.type
                    });
                    
                    hasRecording = true;
                    recordingStatus.classList.add('is-hidden');
                  };

                  audioChunks = [];
                  hasRecording = false;
                  
                  mediaRecorder.start();
                  isRecording = true;
                  micButton.classList.add('is-recording');
                  recordingStatus.classList.remove('is-hidden');
              } catch (err) {
                  console.error("Microphone Access Error:", {
                      name: err.name,
                      message: err.message
                  });
                  
                  alert('Error accessing microphone. Please check your permissions.');
                  recordingStatus.classList.add('is-hidden');
              }
          } else {
              if (mediaRecorder && mediaRecorder.state === 'recording') {
                  mediaRecorder.stop();
              }
              isRecording = false;
              micButton.classList.remove('is-recording');
              recordingStatus.classList.add('is-hidden');
          }
      });
    }
</script>
  </body>
</html>
document.addEventListener("DOMContentLoaded", function () {
  const style = document.createElement("style");
  style.textContent = `
    #current-sentence {
      display: block !important;
      min-height: 20px;
      padding: 10px;
      border: 1px solid #ccc;
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
            "../api/contributor/submit_recordings.php",
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

  debugCurrentSentence();
  fetchSentences();
});

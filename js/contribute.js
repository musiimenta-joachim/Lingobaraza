document.addEventListener("DOMContentLoaded", function () {
  let recorder = null;
  let chunks = [];
  let audioBlob = null;
  let isRecording = false;

  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.name = "audio_file";
  fileInput.style.display = "none";
  document.body.appendChild(fileInput);

  async function setupAudio() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      recorder = new MediaRecorder(stream, {
        mimeType: "audio/webm;codecs=opus",
      });

      recorder.ondataavailable = (e) => {
        if (e.data.size > 0) {
          chunks.push(e.data);
        }
      };

      recorder.onstop = () => {
        audioBlob = new Blob(chunks, { type: "audio/webm;codecs=opus" });
        const audioURL = URL.createObjectURL(audioBlob);
        const playback = document.querySelector(".playback");

        if (playback) {
          playback.src = audioURL;
          playback.style.display = "block";
          playback.onended = () => URL.revokeObjectURL(audioURL);
        }

        chunks = [];
        const file = new File([audioBlob], "recorded_audio.webm", {
          type: "audio/webm",
        });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
      };
    } catch (err) {
      console.error("Microphone setup error:", err);
      alert(
        "Microphone access denied or not available. Please check your browser settings."
      );
      throw err;
    }
  }

  async function startRecording() {
    try {
      if (!recorder) {
        await setupAudio();
      }

      if (recorder.state === "inactive") {
        recorder.start();
        isRecording = true;
        updateUI(true);
      }
    } catch (err) {
      console.error("Recording start error:", err);
      alert(
        "Failed to start recording. Please check your microphone settings."
      );
    }
  }

  function stopRecording() {
    if (recorder && recorder.state === "recording") {
      recorder.stop();
      isRecording = false;
      updateUI(false);
    }
  }

  function updateUI(recording) {
    const micButton = document.querySelector("#mic");
    const playback = document.querySelector(".playback");

    if (micButton) {
      micButton.classList.toggle("is-recording", recording);
      micButton.textContent = recording ? "Stop Recording" : "Start Recording";
    }

    if (playback) {
      playback.style.display = recording ? "none" : "block";
    }
  }

  async function submitForm() {
    const form = document.getElementById("sentenceForm");
    const submitButton = form.querySelector('button[type="submit"]');
    const sentence = document.getElementById("sentence").value.trim();
    const language = document.getElementById("language").value.trim();

    if (!sentence || !language) {
      alert("Please fill in both sentence and language fields.");
      return;
    }

    if (!fileInput.files.length) {
      alert("Please record audio before submitting.");
      return;
    }

    const formData = new FormData(form);
    formData.append("audio_file", fileInput.files[0]);

    submitButton.disabled = true;
    submitButton.textContent = "Submitting...";

    try {
      const response = await fetch("../api/contributor/contribute.php", {
        method: "POST",
        body: formData,
      });

      let data;
      const contentType = response.headers.get("content-type");
      if (contentType && contentType.includes("application/json")) {
        data = await response.json();
      } else {
        const textResponse = await response.text();
        console.error("Unexpected response:", textResponse);
        throw new Error("Server returned invalid response format");
      }

      if (!response.ok) {
        throw new Error(data.message || `Server error: ${response.status}`);
      }

      if (data.status === "error") {
        throw new Error(data.message);
      }

      alert("Submission successful!");
      form.reset();
      const playback = document.querySelector(".playback");
      if (playback) {
        playback.src = "";
        playback.style.display = "none";
      }
      fileInput.value = "";
    } catch (error) {
      console.error("Submission error:", error);
      alert("Submission failed: " + error.message);
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = "Submit";
    }
  }

  const micButton = document.querySelector("#mic");
  const form = document.getElementById("sentenceForm");

  if (!form || !micButton) {
    return;
  }

  const playback = document.querySelector(".playback");
  if (playback) {
    playback.style.display = "none";
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    submitForm();
  });

  micButton.addEventListener("click", (e) => {
    e.preventDefault();
    if (isRecording) {
      stopRecording();
    } else {
      startRecording();
    }
  });

  window.addEventListener("beforeunload", () => {
    if (recorder && recorder.state === "recording") {
      stopRecording();
    }
    if (recorder && recorder.stream) {
      recorder.stream.getTracks().forEach((track) => track.stop());
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const mic_btn = document.querySelector("#mic");
  const playback = document.querySelector(".playback");
  const recordingInput = document.createElement("input");
  recordingInput.type = "hidden";
  recordingInput.name = "recording_url";
  document.querySelector("form").appendChild(recordingInput);

  let can_record = false;
  let is_recording = false;
  let recorder = null;
  let chunks = [];
  let savedAudioURL = "";

  function SetupAudio() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices
        .getUserMedia({ audio: true })
        .then(SetupStream)
        .catch((err) => {
          alert(
            "We couldn't access your microphone. Please check your permissions."
          );
        });
    } else {
    }
  }

  function SetupStream(stream) {
    recorder = new MediaRecorder(stream);

    recorder.ondataavailable = (e) => {
      chunks.push(e.data);
    };

    recorder.onstop = () => {
      const blob = new Blob(chunks, { type: "audio/ogg; codecs=opus" });
      chunks = [];
      const audioURL = URL.createObjectURL(blob);
      playback.src = audioURL;
      savedAudioURL = audioURL;
      recordingInput.value = savedAudioURL;
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
      mic_btn.classList.add("is-recording");
    } else {
      recorder.stop();
      mic_btn.classList.remove("is-recording");
    }
  }

  feather.replace();

  mic_btn.addEventListener("click", function (e) {
    e.preventDefault();
    ToggleMic();
  });
});

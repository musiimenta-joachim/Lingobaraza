<?php
require('../api/shared/verify_status.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="../img/icons/logo.svg" />
	<title>Lingobaraza</title>
	<link href="../css/app.css" rel="stylesheet">
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
              <a class="sidebar-link" href="sentences.php">
                <i class="align-middle" data-feather="file-text"></i>
                <span class="align-middle">Sentences</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="recordings.php">
                <i class="align-middle" data-feather="music"></i>
                <span class="align-middle">Voice Recordings</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="adduser.php">
                <i class="align-middle" data-feather="user-plus"></i>
                <span class="align-middle">Add User</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="users.php">
                <i class="align-middle" data-feather="users"></i>
                <span class="align-middle">Users</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="corrections.php">
                <i class="align-middle" data-feather="filter"></i>
                <span class="align-middle">Corrections</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="sendmail.php">
                <i class="align-middle" data-feather="mail"></i>
                <span class="align-middle">Send Email</span>
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
                			<img src="../img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="admin" /> <span class="text-dark" id="nav_name"></span>
              				</a>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3">Add Sentences</h1>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">Many Sentences</h5>
								</div>
								<div class="card-body">
									<form action="" method="post">
										<div class="mb-3">
											<label class="form-label">Please select file with data</label>
											<input type="file" name="sentence_data" id="sentence_data" class="form-control">
										</div>
										<div class="text-center mt-4">
											<button type="submit" class="btn btn-lg btn-primary" name="multiple_sentences">Submit Sentences</button>
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
									&copy; Copyright 2024. All rights reserved.
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="../js/app.js"></script>
	<script src="../js/admin_nav.js"></script>
	<script>
		document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const fileInput = document.getElementById('sentence_data');
    const progressBar = document.createElement('div');
    progressBar.id = 'upload-progress';
    progressBar.style.width = '0%';
    progressBar.style.height = '20px';
    progressBar.style.backgroundColor = 'blue';
    progressBar.style.transition = 'width 0.5s';

    const progressText = document.createElement('div');
    progressText.id = 'progress-text';
    progressText.style.textAlign = 'center';
    progressText.style.marginTop = '10px';

    const progressContainer = document.createElement('div');
    progressContainer.style.width = '100%';
    progressContainer.style.backgroundColor = '#e0e0e0';
    progressContainer.style.marginTop = '10px';
    progressContainer.appendChild(progressBar);
    progressContainer.appendChild(progressText);

    event.target.appendChild(progressContainer);

    if (!fileInput.value) {
        alert('Please select a file before submitting.');
        return;
    }

    const formData = new FormData();
    formData.append('sentence_data', fileInput.files[0]);

    const xhr = new XMLHttpRequest();
    
    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.style.width = percentComplete + '%';
            progressText.textContent = `File Upload: ${percentComplete.toFixed(2)}%`;
        }
    };

    xhr.onload = function() {
        try {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                progressBar.style.backgroundColor = 'green';
                updateProgressDetails(data);
            } else {
                alert('Error uploading sentences: ' + data.error);
                progressBar.style.backgroundColor = 'red';
            }
        } catch (error) {
            console.error('Error parsing response:', error);
            alert('An unexpected error occurred');
            progressBar.style.backgroundColor = 'red';
        }
    };

    function updateProgressDetails(data) {
        const progressText = document.getElementById('progress-text');
        progressText.innerHTML = `
            Total Sentences Processed: ${data.total_sentences}<br>
            Successfully Inserted: ${data.successful_inserts}<br>
            Skipped Duplicates: ${data.skipped_duplicates}
        `;
        progressBar.style.width = '100%';
    }

    xhr.onerror = function() {
        console.error('Network error');
        alert('A network error occurred');
        progressBar.style.backgroundColor = 'red';
    };

    xhr.open('POST', '../api/admin/upload_sentences.php', true);
    xhr.send(formData);
});
	</script>

</body>
</html>
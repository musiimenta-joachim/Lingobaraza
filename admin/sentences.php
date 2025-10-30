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
	<link rel="stylesheet" href="../css/sentence.css">
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
				<div class="row mb-2">
					<div class="col-7">
						<h1 class="h3 mt-1">All Sentences</h1>
					</div>
					<div class="col-5">
						<a href="./addsentence.php"><button class="btn btn-primary">Add Sentence</button></a>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<table class="table table-hover my-0">
									<thead>
										<tr>
											<th>Sentence</th>
											<th>Status</th>
											<th class="d-none d-md-table-cell">Language</th>
											<th>Uploaded Date</th>
										</tr>
									</thead>
									<tbody id="sentencesTableBody">
										
									</tbody>
								</table>
								
								<div class="pagination" id="paginationControls">
									<button id="prevPage">Previous</button>
									<div id="pageNumbers"></div>
									<button id="nextPage">Next</button>
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
		class SentencesPagination {
			constructor() {
				this.currentPage = 1;
				this.totalPages = 0;
				this.sentencesPerPage = 10;
				this.maxSentences = 50;

				this.tableBody = document.getElementById('sentencesTableBody');
				this.pageNumbersContainer = document.getElementById('pageNumbers');
				this.prevButton = document.getElementById('prevPage');
				this.nextButton = document.getElementById('nextPage');

				this.prevButton.addEventListener('click', () => this.changePage(this.currentPage - 1));
				this.nextButton.addEventListener('click', () => this.changePage(this.currentPage + 1));
			}

			getLanguageName(languageId) {
				const languages = {
					'1': 'English',
					'2': 'Luganda',
					'3': 'Runyankole'
				};
				return languages[languageId] || 'Unknown';
			}

			async fetchSentences(page = 1) {
				try {
					const response = await fetch(`../api/admin/sentences.php?page=${page}`);
					const data = await response.json();

					this.tableBody.innerHTML = '';

					data.sentences.forEach(sentence => {
						const row = document.createElement('tr');
						row.innerHTML = `
							<td>${this.truncateSentence(sentence.sentence)}</td>
							<td>${sentence.validation_status || 'Not Validated'}</td>
							<td class="d-none d-md-table-cell">${this.getLanguageName(sentence.language_id)}</td>
							<td>${sentence.date ? this.formatDate(sentence.date) : 'N/A'}</td>
						`;
						this.tableBody.appendChild(row);
					});

					this.totalPages = data.total_pages;
					this.currentPage = page;

					this.pageNumbersContainer.innerHTML = '';

					const totalSentencesSpan = document.createElement('span');
					totalSentencesSpan.textContent = `Total Sentences: ${data.total_sentences}`;
					totalSentencesSpan.classList.add('mr-3', 'text-muted');
					this.pageNumbersContainer.appendChild(totalSentencesSpan);

					this.createPageNumberButtons();

					this.updateNavigationButtons();
				} catch (error) {
					console.error('Error fetching sentences:', error);
					this.tableBody.innerHTML = `
						<tr>
							<td colspan="4" class="text-center text-danger">
								Error loading sentences. Please try again later.
							</td>
						</tr>
					`;
				}
			}

			truncateSentence(sentence, maxLength = 100) {
				return sentence.length > maxLength 
					? sentence.substring(0, maxLength) + '...' 
					: sentence;
			}

			formatDate(dateString) {
				try {
					const date = new Date(dateString);
					return date.toLocaleDateString('en-US', {
						year: 'numeric',
						month: '2-digit',
						day: '2-digit'
					});
				} catch {
					return 'Invalid Date';
				}
			}

			createPageNumberButtons() {
				for (let i = 1; i <= this.totalPages; i++) {
					const pageButton = document.createElement('button');
					pageButton.textContent = i;
					pageButton.addEventListener('click', () => this.changePage(i));
					
					if (i === this.currentPage) {
						pageButton.classList.add('active');
					}

					this.pageNumbersContainer.appendChild(pageButton);
				}
			}

			updateNavigationButtons() {
				this.prevButton.disabled = this.currentPage === 1;
				this.nextButton.disabled = this.currentPage === this.totalPages;
			}

			changePage(newPage) {
				if (newPage < 1 || newPage > this.totalPages) return;
				this.fetchSentences(newPage);
			}

			init() {
				this.fetchSentences();
			}
		}

		document.addEventListener('DOMContentLoaded', () => {
			const sentencesPagination = new SentencesPagination();
			sentencesPagination.init();
		});
	</script>
</body>

</html>
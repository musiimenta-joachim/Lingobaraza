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
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            padding-left: 0;
            list-style: none;
        }

        .page-item {
            margin: 0 0.25rem;
        }

        .page-link {
            position: relative;
            display: block;
            color: #007bff;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease-in-out;
        }

        .page-link:hover {
            z-index: 2;
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }
    </style>
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

            <li class="sidebar-item active">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle" data-feather="sliders"></i>
                <span class="align-middle">Dashboard</span>
              </a>
            </li>

            <li class="sidebar-item">
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
                    <h1 class="h3 mb-3">Dashboard</h1>

                    <div class="row">
                        <div class="col-xl-6 col-xxl-5 d-flex">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">System Users</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3 total-users">0</h1>
                                                <div class="mb-0">
                                                    <span class="text-danger active-users-change"><i class="mdi mdi-arrow-bottom-right"></i> 0 </span>
                                                    <span class="text-muted">Active Contributors</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Experts</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="user-check"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3 total-experts">0</h1>
                                                <div class="mb-0">
                                                    <span class="text-success active-experts-change"><i class="mdi mdi-arrow-bottom-right"></i> 0 </span>
                                                    <span class="text-muted">Active Validators</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Sentences</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="file-text"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3 total-sentences">0</h1>
                                                <div class="mb-0">
                                                    <span class="text-success english-sentences-change"><i class="mdi mdi-arrow-bottom-right"></i> 0 </span>
                                                    <span class="text-muted">each version</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Verified Sentences</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="check"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3 total-verified-sentences">0</h1>
                                                <div class="mb-0">
                                                    <span class="text-danger rejection-percentage"><i class="mdi mdi-arrow-bottom-right"></i> 0% </span>
                                                    <span class="text-muted">rejections</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-xxl-7">
                            <div class="card flex-fill w-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recordings Validation</h5>
                                </div>
                                <div class="card-body py-3">
                                    <div class="chart chart-sm">
                                        <canvas id="chartjs-dashboard-line"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
                            <div class="card flex-fill w-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Sentence Validation</h5>
                                </div>
                                <div class="card-body d-flex">
                                    <div class="align-self-center w-100">
                                        <div class="py-3">
                                            <div class="chart chart-xs">
                                                <canvas id="chartjs-dashboard-pie"></canvas>
                                            </div>
                                        </div>

                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Verified sentences</td>
                                                    <td class="text-end verified-sentences-count">0</td>
                                                </tr>
                                                <tr>
                                                    <td>Unverified sentences</td>
                                                    <td class="text-end unverified-sentences-count">0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-1 order-xxl-1">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Most Verified Language</h5>
                                </div>
                                <div class="card-body d-flex w-100">
                                    <div class="align-self-center chart chart-lg">
                                        <canvas id="chartjs-dashboard-bar"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

			<div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">

                    <table class="table table-hover my-0">
                      <thead>
                        <tr>
                          <th class="d-none d-xl-table-cell" rowspan="2">Name</th>
                          <th class="d-none d-xl-table-cell" rowspan="2">UserType</th>
						  <th class="d-none d-xl-table-cell text-center" colspan="2">sentences</th>
						  <th class="d-none d-xl-table-cell text-center" colspan="2">Audio</th>
                          <th class="d-none d-md-table-cell" rowspan="2">Action</th>
                        </tr>
						<tr>
            				<th>Accepted</th>
            				<th>Rejected</th>
            				<th>Accepted</th>
            				<th>Rejected</th>
        				</tr>
                      </thead>
                      <tbody id="stat-table-body">
                        
                      </tbody>
                    </table>

                    <nav aria-label="Corrections pagination" class="mt-3">
                        <ul class="pagination justify-content-center" id="pagination">
                            <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                            </li>
                        </ul>
                    </nav>
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
        document.addEventListener('DOMContentLoaded', function() {
            class UserStatsPagination {
            constructor() {
                this.currentPage = 1;
                this.totalPages = 0;
                this.usersPerPage = 10;
                
                this.tableBody = document.getElementById('stat-table-body');
                this.paginationContainer = document.getElementById('pagination');
            }

            renderPagination() {
            this.paginationContainer.innerHTML = '';

            const prevLi = document.createElement('li');
            prevLi.classList.add('page-item');
            if (this.currentPage === 1) prevLi.classList.add('disabled');
            const prevLink = document.createElement('a');
            prevLink.classList.add('page-link');
            prevLink.href = '#';
            prevLink.textContent = 'Previous';
            prevLink.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.currentPage > 1) {
                    this.changePage(this.currentPage - 1);
                }
            });
            prevLi.appendChild(prevLink);
            this.paginationContainer.appendChild(prevLi);

            for (let i = 1; i <= this.totalPages; i++) {
                const pageLi = document.createElement('li');
                pageLi.classList.add('page-item');
                if (i === this.currentPage) pageLi.classList.add('active');
                
                const pageLink = document.createElement('a');
                pageLink.classList.add('page-link');
                pageLink.href = '#';
                pageLink.textContent = i;
                pageLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.changePage(i);
                });
                
                pageLi.appendChild(pageLink);
                this.paginationContainer.appendChild(pageLi);
            }

            const nextLi = document.createElement('li');
            nextLi.classList.add('page-item');
            if (this.currentPage === this.totalPages) nextLi.classList.add('disabled');
            const nextLink = document.createElement('a');
            nextLink.classList.add('page-link');
            nextLink.href = '#';
            nextLink.textContent = 'Next';
            nextLink.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.currentPage < this.totalPages) {
                    this.changePage(this.currentPage + 1);
                }
            });
            nextLi.appendChild(nextLink);
            this.paginationContainer.appendChild(nextLi);
            }

            changePage(newPage) {
                if (newPage < 1 || newPage > this.totalPages) return;
                this.currentPage = newPage;
                this.populateTable(this.fullUserStats);
            }

            populateTable(users) {
                this.fullUserStats = users;
                if (!Array.isArray(users)) {
                    console.error('Expected an array but got:', typeof users, users);
                    return;
                }

                const startIndex = (this.currentPage - 1) * this.usersPerPage;
                const endIndex = startIndex + this.usersPerPage;
                const paginatedUsers = users.slice(startIndex, endIndex);

                const tableBody = document.getElementById('stat-table-body');
                tableBody.innerHTML = '';
                
                paginatedUsers.forEach(user_stats => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td class="d-none d-xl-table-cell">${user_stats.user_name}</td>
                        <td class="d-none d-xl-table-cell">${user_stats.acc_type.charAt(0).toUpperCase() + user_stats.acc_type.slice(1)}</td>
                        <td>${user_stats.approved_sentences_count || 0}</td>
                        <td>${user_stats.rejected_sentences_count || 0}</td>
                        <td>${user_stats.approved_voice_notes_count || 0}</td>
                        <td>${user_stats.rejected_voice_notes_count || 0}</td>
                        <td class="d-none d-md-table-cell">
                            <a href="mailto:${user_stats.email}" class="btn btn-primary btn-sm">Send Email</a>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                this.totalPages = Math.ceil(users.length / this.usersPerPage);
                this.renderPagination();
            }

            async fetchDashboardData() {
                try {
                    const response = await fetch('../api/admin/dashboard.php');
                    const data = await response.json();

                    if (data.status === 'success') {
                        this.updateDashboardCharts(data);
                        this.populateTable(data.user_stats || []);
                    } else {
                        console.error('Error fetching dashboard data:', data.error);
                    }
                } catch (error) {
                    console.error('Failed to fetch dashboard data:', error);
                }
            }

            updateDashboardCharts(data) {
                updateSystemUsers(data.system_users);
                updateExperts(data.experts);
                updateSentences(data.sentences);
                updateVerifiedSentences(data.verified_sentences);
                updateRecordingsValidationChart(data.recordings_validation);
                updateMostVerifiedLanguageChart(data.most_verified_language);
                updateSentenceValidationChart(data.sentence_validation);
            }

            init() {
                this.fetchDashboardData();
                setInterval(() => this.fetchDashboardData(), 5 * 60 * 1000);
            }
        }

        function updateSystemUsers(systemUsers) {
				if (systemUsers) {
					document.querySelector('.total-users').textContent = systemUsers.total_contributors || 0;
					document.querySelector('.active-users-change').innerHTML = 
						`<i class="mdi mdi-arrow-bottom-right"></i> ${systemUsers.active_contributors || 0}`;
				}
			}

			function updateExperts(experts) {
				if (experts) {
					document.querySelector('.total-experts').textContent = experts.total_experts || 0;
					document.querySelector('.active-experts-change').innerHTML = 
						`<i class="mdi mdi-arrow-bottom-right"></i> ${experts.active_experts || 0}`;
				}
			}

			function updateSentences(sentences) {
				if (sentences) {
					document.querySelector('.total-sentences').textContent = sentences.total_sentences || 0;
					document.querySelector('.english-sentences-change').innerHTML = 
						`<i class="mdi mdi-arrow-bottom-right"></i> ${sentences.english_sentences || 0}`;
				}
			}

			function updateVerifiedSentences(verifiedSentences) {
				if (verifiedSentences) {
					document.querySelector('.total-verified-sentences').textContent = verifiedSentences.total_validated || 0;
					document.querySelector('.rejection-percentage').innerHTML = 
						`<i class="mdi mdi-arrow-bottom-right"></i> ${verifiedSentences.rejection_percentage || 0}%`;
				}
			}

			function updateRecordingsValidationChart(recordingsValidation) {
				const ctx = document.getElementById('chartjs-dashboard-line').getContext('2d');
				new Chart(ctx, {
					type: 'line',
					data: {
						labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
						datasets: [
							{
								label: 'Recorded Voice Notes',
								data: recordingsValidation.map(item => item.recorded_notes),
								borderColor: 'rgb(75, 192, 192)',
								tension: 0.1
							},
							{
								label: 'Validated Validated',
								data: recordingsValidation.map(item => item.validated_notes),
								borderColor: 'rgb(255, 99, 132)',
								tension: 0.1
							}
						]
					},
					options: {
						responsive: true,
						plugins: {
							legend: {
								position: 'top',
							},
							title: {
								display: false
							}
						},
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				});
			}

			function updateSentenceValidationChart(sentenceValidation) {
				const ctx = document.getElementById('chartjs-dashboard-pie').getContext('2d');
				
				document.querySelector('.verified-sentences-count').textContent = sentenceValidation.verified_sentences || 0;
				document.querySelector('.unverified-sentences-count').textContent = sentenceValidation.unverified_sentences || 0;

				new Chart(ctx, {
					type: 'pie',
					data: {
						labels: ['Verified', 'Unverified'],
						datasets: [{
							data: [
								sentenceValidation.verified_sentences || 0, 
								sentenceValidation.unverified_sentences || 0
							],
							backgroundColor: [
								'rgb(75, 192, 192)',
								'rgb(255, 99, 132)'
							]
						}]
					},
					options: {
						responsive: true,
						plugins: {
							legend: {
								position: 'bottom',
							}
						}
					}
				});
			}

			function updateMostVerifiedLanguageChart(mostVerifiedLanguage) {
				const ctx = document.getElementById('chartjs-dashboard-bar').getContext('2d');
				new Chart(ctx, {
					type: 'bar',
					data: {
						labels: ['English', 'Luganda', 'Runyankole'],
						datasets: [
							{
								label: 'Sentence Verifications',
								data: [
									mostVerifiedLanguage.sen_english || 0, 
									mostVerifiedLanguage.sen_luganda || 0, 
									mostVerifiedLanguage.sen_runyankole || 0
								],
								backgroundColor: 'rgba(75, 192, 192, 0.6)'
							},
							{
								label: 'Recording Verifications',
								data: [
									mostVerifiedLanguage.rec_english || 0, 
									mostVerifiedLanguage.rec_luganda || 0, 
									mostVerifiedLanguage.rec_runyankole || 0
								],
								backgroundColor: 'rgba(255, 99, 132, 0.6)'
							}
						]
					},
					options: {
						responsive: true,
						plugins: {
							legend: {
								position: 'bottom',
							},
							title: {
								display: false
							}
						},
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				});
			}

        const userStatsPagination = new UserStatsPagination();
        userStatsPagination.init();
    });
    </script>
</body>
</html>
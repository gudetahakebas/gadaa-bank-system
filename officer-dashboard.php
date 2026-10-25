<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'officer') {
    header("Location: officer-login.html");
    exit();
}
require_once 'db_config.php';
$stmtStatus = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'election_status'");
$electionStatus = $stmtStatus->fetchColumn();
$results_published = ($electionStatus === 'Results Published');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard | Gadaa Bank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <link rel="stylesheet" href="assets/css/notifications.css">
</head>

<body>

    <!-- Top Information Bar -->
    <div class="top-bar">
        <span><i class="fas fa-phone-alt"></i> 641</span>
        <span><i class="fas fa-envelope"></i> info@gadaabank.com.et</span>
        <span><i class="fas fa-map-marker-alt"></i> Head Office – Addis Ababa</span>
    </div>

    <!-- Logo Section -->
    <div class="logo-bar">
        <img src="assets/images/logo.png" alt="Gadaa Bank Logo">
        <div>
            <h2>Gadaa Bank</h2>
            <small>New Generation’s Bank Dashboard</small>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-user-tie"></i> Election Officer</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-link active" onclick="showModule('register-candidate')">
                    <i class="fas fa-user-plus"></i> Register Candidate
                </a>
                <a href="#" class="nav-link" onclick="showModule('register-voter')">
                    <i class="fas fa-id-card"></i> Register Voter
                </a>
                <a href="#" class="nav-link" onclick="showModule('scrutiny')">
                    <i class="fas fa-filter"></i> Scrutiny (Filter)
                </a>
                <a href="#" class="nav-link" onclick="showModule('view-candidates')">
                    <i class="fas fa-users"></i> List Candidates
                </a>
                <a href="#" class="nav-link" onclick="showModule('change-password')">
                    <i class="fas fa-key"></i> Change Passwords
                </a>
                <a href="#" class="nav-link" onclick="showModule('view-results')">
                    <i class="fas fa-poll-h"></i> Election Results
                </a>
                <a href="logout.php" class="nav-link"
                    style="margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.1)">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Register Candidate Module -->
            <div id="register-candidate" class="module-content active">
                <div class="dashboard-card">
                    <h2><i class="fas fa-user-plus"></i> Candidate Registration</h2>
                    <form onsubmit="handleCandidateRegistration(event)">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" id="candFirstName" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" id="candMiddleName" placeholder="Enter middle name" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" id="candLastName" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" id="candAge" placeholder="Age" min="25" required>
                            </div>
                            <div class="form-group">
                                <label>Sex</label>
                                <select id="candSex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" id="candEmail" placeholder="email@example.com" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" id="candPhone" placeholder="+251..." required>
                            </div>
                            <div class="form-group">
                                <label>Candidate Username</label>
                                <input type="text" id="candID" placeholder="Assign unique username" required>
                            </div>
                            <div class="form-group">
                                <label>Candidate Category</label>
                                <select id="candCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="Non-Influential Shareholder Candidate">Non-Influential Shareholder Candidate</option>
                                    <option value="Influential Shareholder Candidate">Influential Shareholder Candidate</option>
                                    <option value="Independent Candidate">Independent Candidate</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Login Password</label>
                                <input type="password" id="candPwd" placeholder="Set initial password" required>
                            </div>
                            <div class="form-group">
                                <label>Education Level</label>
                                <select id="candEdu" required>
                                    <option value="">Select Level</option>
                                    <option value="BSc">Bachelor's Degree</option>
                                    <option value="MSc">Master's Degree</option>
                                    <option value="PhD">PhD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Experience (Years)</label>
                                <input type="number" id="candExp" placeholder="Years of experience" required>
                            </div>
                            <div class="form-group">
                                <label>Candidate Photograph (Image)</label>
                                <input type="file" id="candPhoto" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Education and Experience Document (PDF)</label>
                                <input type="file" id="candDocs" accept=".pdf">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Professional Biography</label>
                            <textarea id="candBio" rows="3" placeholder="Briefly describe background..."></textarea>
                        </div>
                        <button type="submit" class="btn-red"><i class="fas fa-save"></i> Register Candidate</button>
                    </form>
                </div>
            </div>

            <!-- Register Voter Module -->
            <div id="register-voter" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-id-card"></i> Voter Registration</h2>
                    <form onsubmit="handleVoterRegistration(event)">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" id="voterFirstName" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" id="voterMiddleName" placeholder="Enter middle name" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" id="voterLastName" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" id="voterAge" placeholder="Age" min="18" required>
                            </div>
                            <div class="form-group">
                                <label>Sex</label>
                                <select id="voterSex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" id="voterEmail" placeholder="email@example.com" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" id="voterPhone" placeholder="+251..." required>
                            </div>
                            <div class="form-group">
                                <label>Voter Username</label>
                                <input type="text" id="voterID" placeholder="Assign unique username" required>
                            </div>
                            <div class="form-group">
                                <label>Login Password</label>
                                <input type="password" id="voterPwd" placeholder="Set initial password" required>
                            </div>
                            <div class="form-group">
                                <label>Shareholder Status</label>
                                <select id="voterStatus" required>
                                    <option value="no">Not Approved Yet</option>
                                    <option value="yes">Approved Shareholder</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Share Value (ETB)</label>
                                <input type="number" id="voterShares" placeholder="Value of shares held" required>
                            </div>
                            <div class="form-group">
                                <label>Voter Photograph (Image)</label>
                                <input type="file" id="voterPhoto" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Upload Share Ownership Certificate</label>
                                <input type="file" id="voterShareDoc" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-red"><i class="fas fa-check-circle"></i> Register
                            Voter</button>
                    </form>
                </div>
            </div>

            <!-- Scrutiny Module -->
            <div id="scrutiny" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-filter"></i> Candidate Scrutiny</h2>
                    <p>Review and filter registered candidates to determine final list for election.</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Education</th>
                                <th>Experience</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="scrutinyTableBody">
                            <!-- JS will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- List Candidates Module -->
            <div id="view-candidates" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-users"></i> Registered Candidates</h2>
                    <div id="candidateListContainer">
                        <!-- JS will populate cards -->
                    </div>
                </div>
                <div class="dashboard-card">
                    <h2><i class="fas fa-user-check"></i> Final Scrutinized Candidates</h2>
                    <div id="finalCandidateListContainer">
                        <!-- JS will populate cards -->
                    </div>
                </div>
            </div>

            <!-- Password Management Module -->
            <div id="change-password" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-key"></i> Change Passwords</h2>
                    <div class="form-grid">
                        <div class="dashboard-card" style="margin-bottom:0; box-shadow:none; border:1px solid #eee;">
                            <h3>Reset Candidate Pwd</h3>
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" id="resetCandUserName" placeholder="Enter user name">
                                <label>New Password</label>
                                <input type="password" id="newCandPwd" placeholder="Enter new password">
                                <label>Confirm New Password</label>
                                <input type="password" id="confirmCandPwd" placeholder="Confirm new password">
                                <button onclick="resetPassword('candidate')" class="btn-red" style="width:100%"><i
                                        class="fas fa-redo"></i> Reset</button>
                            </div>
                        </div>
                        <div class="dashboard-card" style="margin-bottom:0; box-shadow:none; border:1px solid #eee;">
                            <h3>Reset Voter Pwd</h3>
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" id="resetVoterUserName" placeholder="Enter user name">
                                <label>New Password</label>
                                <input type="password" id="newVoterPwd" placeholder="Enter new password">
                                <label>Confirm New Password</label>
                                <input type="password" id="confirmVoterPwd" placeholder="Confirm new password">
                                <button onclick="resetPassword('voter')" class="btn-red" style="width:100%"><i
                                        class="fas fa-redo"></i> Reset</button>
                            </div>
                        </div>
                        <div class="dashboard-card" style="margin-bottom:0; box-shadow:none; border:1px solid #eee;">
                            <h3>Change My Password</h3>
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" id="currOffPwd" placeholder="Enter current">
                                <label>New Password</label>
                                <input type="password" id="newOffPwd" placeholder="Enter new">
                                <label>Confirm New Password</label>
                                <input type="password" id="confirmOffPwd" placeholder="Confirm new">
                                <button onclick="resetPassword('self')" class="btn-red" style="width:100%"><i
                                        class="fas fa-sync"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Results Module -->
            <div id="view-results" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-poll-h"></i> System Generated Results</h2>
                    <p>Official results as generated by the System Administrator.</p>
                    <div class="dashboard-card" style="background: #f8f9fa;">
                        <?php if ($results_published): ?>
                            <p style="text-align:center; padding: 20px;">The official election results have been published!</p>
                            <div style="text-align:center; margin-bottom: 20px;">
                                <a href="results.php" class="btn-red" style="padding: 15px 30px; text-decoration: none; display: inline-block;"><i class="fas fa-external-link-alt"></i> View Official Results</a>
                            </div>
                        <?php else: ?>
                            <p style="text-align:center; padding: 20px;">Results are hidden until the Administrator publishes them.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2026 Gadaa Bank. All Rights Reserved. | Officer Portal v1.0</p>
    </footer>

    <script>
        function showModule(moduleId) {
            // Hide all
            document.querySelectorAll('.module-content').forEach(m => m.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

            // Show selected
            document.getElementById(moduleId).classList.add('active');
            event.currentTarget.classList.add('active');

            if (moduleId === 'scrutiny') refreshScrutinyTable();
            if (moduleId === 'view-candidates') refreshCandidateLists();
        }

        async function handleCandidateRegistration(e) {
            e.preventDefault();
            
            const username = document.getElementById('candID').value;
            try {
                const checkRes = await fetch(`check_username.php?username=${encodeURIComponent(username)}`);
                const checkData = await checkRes.json();
                if (checkData.status === 'success' && checkData.exists) {
                    showNotification('Username already exists. Please choose another username.', 'error');
                    return;
                }
            } catch (err) {
                console.error('Username check error:', err);
            }

            const formData = new FormData();

            formData.append('firstName', document.getElementById('candFirstName').value);
            formData.append('middleName', document.getElementById('candMiddleName').value);
            formData.append('lastName', document.getElementById('candLastName').value);
            formData.append('age', document.getElementById('candAge').value);
            formData.append('sex', document.getElementById('candSex').value);
            formData.append('email', document.getElementById('candEmail').value);
            formData.append('phone', document.getElementById('candPhone').value);
            formData.append('candID', document.getElementById('candID').value);
            formData.append('category', document.getElementById('candCategory').value);
            formData.append('password', document.getElementById('candPwd').value);
            formData.append('education', document.getElementById('candEdu').value);
            formData.append('experience', document.getElementById('candExp').value);
            formData.append('photo', document.getElementById('candPhoto').files[0]);
            formData.append('docs', document.getElementById('candDocs').files[0]);
            formData.append('bio', document.getElementById('candBio').value);

            try {
                const response = await fetch('register_candidate.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.status === 'success') {
                    showNotification('', 'success');
                    e.target.reset();
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Connection error.', 'error');
            }
        }

        async function handleVoterRegistration(e) {
            e.preventDefault();
            
            const username = document.getElementById('voterID').value;
            try {
                const checkRes = await fetch(`check_username.php?username=${encodeURIComponent(username)}`);
                const checkData = await checkRes.json();
                if (checkData.status === 'success' && checkData.exists) {
                    showNotification('Username already exists. Please choose another username.', 'error');
                    return;
                }
            } catch (err) {
                console.error('Username check error:', err);
            }

            const formData = new FormData();

            formData.append('voterID', document.getElementById('voterID').value);
            formData.append('firstName', document.getElementById('voterFirstName').value);
            formData.append('middleName', document.getElementById('voterMiddleName').value);
            formData.append('lastName', document.getElementById('voterLastName').value);
            formData.append('age', document.getElementById('voterAge').value);
            formData.append('sex', document.getElementById('voterSex').value);
            formData.append('email', document.getElementById('voterEmail').value);
            formData.append('phone', document.getElementById('voterPhone').value);
            formData.append('password', document.getElementById('voterPwd').value);
            formData.append('status', document.getElementById('voterStatus').value);
            formData.append('shares', document.getElementById('voterShares').value);
            formData.append('photo', document.getElementById('voterPhoto').files[0]);
            formData.append('shareDoc', document.getElementById('voterShareDoc').files[0]);

            try {
                const response = await fetch('register_voter.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.status === 'success') {
                    showNotification('', 'success');
                    e.target.reset();
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Connection error.', 'error');
            }
        }

        async function refreshScrutinyTable() {
            const table = document.getElementById('scrutinyTableBody');

            try {
                const response = await fetch('get_candidates.php');
                const result = await response.json();

                if (result.status === 'success') {
                    const cands = result.data;
                    table.innerHTML = '';

                    cands.forEach((c) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${c.name}</td>
                            <td>${c.education}</td>
                            <td>${c.experience} yrs</td>
                            <td><span class="badge ${c.status === 'Final' ? 'badge-approved' : 'badge-pending'}">${c.status}</span></td>
                            <td>
                                <button onclick="approveCandidate('${c.id}')" class="action-btn btn-approve" ${c.status === 'Final' ? 'disabled' : ''}>
                                    <i class="fas fa-check"></i> Finalize
                                </button>
                            </td>
                        `;
                        table.appendChild(tr);
                    });
                }
            } catch (error) {
                console.error('Error fetching scrutiny data:', error);
            }
        }

        async function approveCandidate(candID) {
            const formData = new FormData();
            formData.append('candidateID', candID);

            try {
                const response = await fetch('manage_candidates.php?action=finalize', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showNotification(result.message, result.status === 'success' ? 'success' : 'error');
                    refreshScrutinyTable();
                } else {
                    showNotification('Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error finalizing candidate:', error);
            }
        }

        async function refreshCandidateLists() {
            const registered = document.getElementById('candidateListContainer');
            const finalized = document.getElementById('finalCandidateListContainer');

            try {
                const response = await fetch('get_candidates.php');
                const result = await response.json();

                if (result.status === 'success') {
                    const cands = result.data;
                    registered.innerHTML = '';
                    finalized.innerHTML = '';

                    cands.forEach(c => {
                        const card = `
                            <div style="background:#f9f9f9; padding:15px; border-radius:10px; margin-bottom:10px; border-left:4px solid var(--primary-red)">
                                <strong>${c.name}</strong> (${c.id})<br>
                                <small>${c.education} | ${c.experience} yrs exp</small><br>
                                <small style="color: #666"><i class="fas fa-camera"></i> ${c.photo || 'No photo'} | <i class="fas fa-file-pdf"></i> ${c.doc || 'No document'}</small>
                            </div>
                        `;
                        if (c.status === 'Final') {
                            finalized.innerHTML += card;
                        } else {
                            registered.innerHTML += card;
                        }
                    });
                }
            } catch (error) {
                console.error('Error refreshing lists:', error);
            }
        }

        function resetPassword(role) {
            let pass1, pass2, user;
            if (role === 'candidate') {
                user = document.getElementById('resetCandUserName').value;
                pass1 = document.getElementById('newCandPwd').value;
                pass2 = document.getElementById('confirmCandPwd').value;
            } else if (role === 'voter') {
                user = document.getElementById('resetVoterUserName').value;
                pass1 = document.getElementById('newVoterPwd').value;
                pass2 = document.getElementById('confirmVoterPwd').value;
            } else {
                pass1 = document.getElementById('newOffPwd').value;
                pass2 = document.getElementById('confirmOffPwd').value;
            }

            if (!pass1 || !pass2 || (role !== 'self' && !user)) {
                showNotification('Please fill in all fields.', 'error');
                return;
            }

            if (pass1 !== pass2) {
                showNotification('Passwords do not match! Please confirm again.', 'error');
                return;
            }

            alert('Password reset process successful for ' + (user || role) + '.');
        }
    </script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type="password"]').forEach(input => {
            const container = document.createElement('div');
            container.style.marginTop = '5px';
            container.style.marginBottom = '15px';
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.style.marginRight = '8px';
            checkbox.style.cursor = 'pointer';
            checkbox.style.width = 'auto';
            checkbox.style.display = 'inline-block';
            
            const label = document.createElement('label');
            label.style.fontSize = '14px';
            label.style.color = '#555';
            label.style.cursor = 'pointer';
            label.style.display = 'flex';
            label.style.alignItems = 'center';
            label.style.fontWeight = 'normal';
            label.appendChild(checkbox);
            label.appendChild(document.createTextNode(' Show Password'));
            
            container.appendChild(label);
            input.parentNode.insertBefore(container, input.nextSibling);
            
            checkbox.addEventListener('change', function() {
                input.type = this.checked ? 'text' : 'password';
            });
        });
    });
</script>
    <script src="assets/js/notifications.js"></script>
</body>

</html>




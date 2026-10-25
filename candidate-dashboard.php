<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'candidate') {
    header("Location: candidate-login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard | Gadaa Bank</title>
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
                <h3><i class="fas fa-user-tie"></i> Candidate Portal</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-link active" onclick="showModule('my-profile')">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
                <a href="#" class="nav-link" onclick="showModule('candidate-list')">
                    <i class="fas fa-users"></i> Other Candidates
                </a>
                <a href="#" class="nav-link" onclick="showModule('change-password')">
                    <i class="fas fa-key"></i> Change Password
                </a>
                <a href="logout.php" class="nav-link"
                    style="margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.1)">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <!-- My Profile Module -->
            <div id="my-profile" class="module-content active">
                <div class="dashboard-card">
                    <h2><i class="fas fa-user-circle"></i> My Account Details</h2>
                    <div class="profile-display" id="profileDisplay">
                        <!-- Data populated by JS -->
                    </div>
                </div>
            </div>

            <!-- Candidate List Module -->
            <div id="candidate-list" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-users"></i> All Candidates</h2>
                    <div id="candidateListContainer">
                        <!-- JS will populate cards -->
                    </div>
                </div>
            </div>

            <!-- Password Management Module -->
            <div id="change-password" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-key"></i> Change My Password</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" id="oldPwd" placeholder="Enter current password" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <div style="position: relative;">
                                <input type="password" id="newPwd" placeholder="Enter new password" required>
                                <i class="fas fa-eye" id="toggleNewPwd"
                                    style="position: absolute; right: 15px; top: 15px; cursor: pointer; color: #666;"
                                    onclick="togglePasswordVisibility('newPwd', this)"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" id="confirmPwd" placeholder="Confirm new password" required>
                        </div>
                        <button onclick="handleChangePassword()" class="btn-red"><i class="fas fa-sync"></i> Update
                            Password</button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2026 Gadaa Bank. All Rights Reserved. | Candidate Portal v1.0</p>
    </footer>

    <script>
        function showModule(moduleId) {
            document.querySelectorAll('.module-content').forEach(m => m.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

            document.getElementById(moduleId).classList.add('active');
            event.currentTarget.classList.add('active');

            if (moduleId === 'candidate-list') refreshCandidateLists();
        }

        function togglePasswordVisibility(id, icon) {
            const el = document.getElementById(id);
            if (el.type === "password") {
                el.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                el.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function handleChangePassword() {
            const oldPass = document.getElementById('oldPwd').value;
            const newPass = document.getElementById('newPwd').value;
            const confirmPass = document.getElementById('confirmPwd').value;

            if (!oldPass || !newPass || !confirmPass) {
                showNotification('Please fill in all fields.', 'error');
                return;
            }

            if (newPass !== confirmPass) {
                showNotification('', 'info');
                return;
            }

            // Simple simulation
            showNotification('', 'success');
            document.getElementById('oldPwd').value = '';
            document.getElementById('newPwd').value = '';
            document.getElementById('confirmPwd').value = '';
        }

        async function loadProfile() {
            try {
                const response = await fetch('get_profile.php');
                const result = await response.json();
                if (result.status === 'success') {
                    const profile = result.data;
                    const profileHTML = `
                        <div style="display: flex; gap: 30px; align-items: start;">
                            <img src="${profile.photo_path ? profile.photo_path : 'https://via.placeholder.com/150'}" alt="Profile"
                                style="width:150px; height:150px; border-radius: 15px; border: 4px solid #eee; object-fit: cover;">
                            <div style="flex-grow: 1;">
                                <h3 style="color: var(--primary-red); margin-bottom: 10px;">${profile.name}</h3>
                                <p><strong>Username:</strong> <span>${profile.username}</span></p>
                                <p><strong>Email:</strong> ${profile.email}</p>
                                <p><strong>ID:</strong> ${profile.candidate_id_str}</p>
                                <p><strong>Education:</strong> ${profile.education}</p>
                                <p><strong>Experience:</strong> ${profile.experience} years</p>
                                <p><strong>Status:</strong> <span class="badge ${profile.status === 'Final' ? 'badge-approved' : 'badge-pending'}">${profile.status}</span></p>
                            </div>
                        </div>
                    `;
                    document.getElementById('profileDisplay').innerHTML = profileHTML;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        async function refreshCandidateLists() {
            const container = document.getElementById('candidateListContainer');
            try {
                const response = await fetch('get_candidates.php');
                const result = await response.json();
                if (result.status === 'success') {
                    const cands = result.data;
                    if (cands.length === 0) {
                        container.innerHTML = '<p style="text-align:center; padding: 20px;">No other candidates registered yet.</p>';
                        return;
                    }
                    container.innerHTML = '';
                    cands.forEach(c => {
                        const card = `
                            <div style="background:#f9f9f9; padding:15px; border-radius:10px; margin-bottom:10px; border-left:4px solid var(--primary-red)">
                                <strong>${c.name}</strong> (${c.id})<br>
                                <small>${c.education} | ${c.experience} yrs exp</small><br>
                                <small><span class="badge ${c.status === 'Final' ? 'badge-approved' : 'badge-pending'}">${c.status}</span></small>
                            </div>
                        `;
                        container.innerHTML += card;
                    });
                }
            } catch (error) {
                console.error('Error refreshing list:', error);
            }
        }

        window.onload = function () {
            loadProfile();
        };
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




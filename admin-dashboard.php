<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    header("Location: admin-login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Gadaa Bank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-bottom: 4px solid var(--primary-red);
        }

        .stat-card i {
            font-size: 24px;
            color: var(--primary-red);
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
        }
    </style>
    <script>
        const ADMIN_ROLE = '<?php echo $_SESSION['role']; ?>';
        const CURRENT_USER_ID = <?php echo $_SESSION['user_id']; ?>;
    </script>
    <script src="assets/js/notifications.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
            <small>New Generation’s Bank | System Administrator</small>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-user-shield"></i> System Admin</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-link active" onclick="showModule('admin-overview')">
                    <i class="fas fa-tachometer-alt"></i> Overview
                </a>
                <a href="#" class="nav-link" onclick="showModule('manage-officers')">
                    <i class="fas fa-user-tie"></i> Register Staff
                </a>
                <a href="#" class="nav-link" onclick="showModule('candidate-scrutiny')">
                    <i class="fas fa-filter"></i> Scrutiny (Candidates)
                </a>
                <a href="#" class="nav-link" onclick="showModule('account-management')">
                    <i class="fas fa-users-cog"></i> Account Management
                </a>
                <a href="#" class="nav-link" onclick="showModule('system-control')">
                    <i class="fas fa-cogs"></i> System Control
                </a>
                <a href="#" class="nav-link" onclick="showModule('generate-reports')">
                    <i class="fas fa-chart-pie"></i> Election Reports
                </a>
                <a href="#" class="nav-link" onclick="showModule('categorize-voters')">
                    <i class="fas fa-list"></i> Categorize Voters
                </a>
                <a href="#" class="nav-link" onclick="showModule('categorize-candidates')">
                    <i class="fas fa-list-alt"></i> Categorize Candidates
                </a>
                <a href="logout.php" class="nav-link"
                    style="margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.1)">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Overview Module -->
            <div id="admin-overview" class="module-content active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h3 id="totalVoters">0</h3>
                        <p>Total Voters</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-user-tie"></i>
                        <h3 id="totalCandidates">0</h3>
                        <p>Total Candidates</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-vote-yea"></i>
                        <h3 id="totalVotes">0</h3>
                        <p>Votes Cast</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-clock"></i>
                        <h3 id="electionStatusDisplay">Registration</h3>
                        <p>Election Status</p>
                    </div>
                </div>

                <div class="dashboard-card">
                    <h2><i class="fas fa-info-circle"></i> Admin Insight
                        <button onclick="loadOverviewStats()" class="btn-red" style="float: right; padding: 5px 15px; font-size: 14px;"><i class="fas fa-sync"></i> Refresh Data</button>
                    </h2>
                    <p>Welcome to the Gadaa Bank Online Voting System Administrative Dashboard. Use the menu to manage
                        the system components and oversee the election process.</p>
                </div>
            </div>

            <!-- Manage Officers Module -->
            <div id="manage-officers" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-user-plus"></i> Register Staff (Admin/Officer)</h2>
                    <form id="officerRegForm">
                        <?php if ($_SESSION['role'] !== 'super_admin'): ?>
                        <input type="hidden" name="user_role" value="officer">
                        <?php endif; ?>
                        <div class="form-grid">
                            <?php if ($_SESSION['role'] === 'super_admin'): ?>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="user_role" required style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ddd; background: #fff;">
                                    <option value="officer">Election Officer</option>
                                    <option value="admin">Regular Admin</option>
                                </select>
                            </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label>Sex</label>
                                <select name="sex" required style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ddd; background: #fff;">
                                    <option value="" disabled selected>Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="officer@gadaabank.com" required>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" name="phone" placeholder="+251..." required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" placeholder="Assign username" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Set password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-red"><i class="fas fa-user-check"></i> Register User</button>
                    </form>
                </div>
            </div>

            <!-- Account Management Module -->
            <div id="account-management" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-users-cog"></i> Account Management</h2>
                    <div class="form-group">
                        <input type="text" id="userSearch" placeholder="Search by name or username..."
                            style="padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #ddd;">
                    </div>

                    <?php if ($_SESSION['role'] === 'super_admin'): ?>
                    <h3>Administrators</h3>
                    <table class="data-table" style="margin-bottom: 30px;">
                        <thead><tr><th>Full Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody id="adminsTable"></tbody>
                    </table>
                    <?php endif; ?>
                    
                    <h3>Officers</h3>
                    <table class="data-table" style="margin-bottom: 30px;">
                        <thead><tr><th>Full Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody id="officersTable"></tbody>
                    </table>
                    
                    <h3>Candidates</h3>
                    <table class="data-table" style="margin-bottom: 30px;">
                        <thead><tr><th>Full Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Document</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody id="candidatesTable"></tbody>
                    </table>

                    <h3>Voters</h3>
                    <table class="data-table">
                        <thead><tr><th>Full Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Document</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody id="votersTable"></tbody>
                    </table>
                </div>
            </div>

            <!-- Candidate Scrutiny Module -->
            <div id="candidate-scrutiny" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-filter"></i> Candidate Scrutiny</h2>
                    <p>Review and finalize candidates for the election.</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Education</th>
                                <th>Experience</th>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="scrutinyTableBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- System Control Module -->
            <div id="system-control" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-cogs"></i> System Control Panel</h2>
                    <div class="form-group">
                        <label>Current Election Phase</label>
                        <select id="electionPhaseSelect" class="form-control" style="width: 300px; padding: 10px;">
                            <option value="Registration">Registration Phase</option>
                            <option value="Scrutiny">Scrutiny Phase</option>
                            <option value="Voting">Voting Phase</option>
                            <option value="Results Published">Results Published</option>
                        </select>
                        <button onclick="updateElectionPhase()" class="btn-red" style="margin-left: 10px;">Update
                            Status</button>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h3>Audit Logs</h3>
                        <select id="auditRoleFilter" onchange="loadAuditLogs()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                            <option value="all">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="officer">Officer</option>
                            <option value="candidate">Candidate</option>
                            <option value="voter">Voter</option>
                        </select>
                    </div>
                    <div style="background: #f4f4f4; border-radius: 5px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 13px;">
                        <table class="data-table" style="margin: 0; box-shadow: none;">
                            <thead style="position: sticky; top: 0; background: #333;">
                                <tr>
                                    <th style="padding: 8px;">Time</th>
                                    <th style="padding: 8px;">User</th>
                                    <th style="padding: 8px;">Role</th>
                                    <th style="padding: 8px;">Action</th>
                                    <th style="padding: 8px;">Details</th>
                                </tr>
                            </thead>
                            <tbody id="auditLogsBody">
                                <!-- JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Generate Reports Module -->
            <div id="generate-reports" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-chart-pie"></i> Election Reports</h2>
                    <div class="stats-grid">
                        <button class="stat-card" style="cursor: pointer;" onclick="loadReport('voters')">
                            <i class="fas fa-users"></i>
                            <p>Voter Participation Report</p>
                        </button>
                        <button class="stat-card" style="cursor: pointer;" onclick="loadReport('audit')">
                            <i class="fas fa-shield-alt"></i>
                            <p>System Audit Report</p>
                        </button>
                        <button class="stat-card" style="cursor: pointer;" onclick="loadReport('results')">
                            <i class="fas fa-poll"></i>
                            <p>Final Election Results</p>
                        </button>
                    </div>
                    <div id="reportContainer" style="margin-top: 30px; display: none; padding: 20px; background: #f9f9f9; border-radius: 10px; border-left: 4px solid var(--primary-red);">
                        <h3 id="reportTitle" style="margin-bottom: 15px;"></h3>
                        <div id="reportContent"></div>
                    </div>
                </div>
            </div>

            <!-- Categorize Voters Module -->
            <div id="categorize-voters" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-list"></i> Categorize Voters</h2>
                    <p>Assign categories to voters. "Uncategorized" voters cannot cast a vote.</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Username/ID</th>
                                <th>Share Value (ETB)</th>
                                <th>Document</th>
                                <th>Current Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="categorizeVotersBody">
                            <!-- JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categorize Candidates Module -->
            <div id="categorize-candidates" class="module-content">
                <div class="dashboard-card">
                    <h2><i class="fas fa-list-alt"></i> Categorize Candidates</h2>
                    <p>Assign categories to candidates. "Uncategorized" candidates will not appear in the election list.</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Candidate Name</th>
                                <th>Username/ID</th>
                                <th>Profession</th>
                                <th>Document</th>
                                <th>Current Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="categorizeCandidatesBody">
                            <!-- JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2026 Gadaa Bank. All Rights Reserved. | Admin Portal v2.0</p>
    </footer>

    <script>
    async function loadOverviewStats() {
        try {
            const response = await fetch('get_admin_stats.php');
            const data = await response.json();
            if (data.status === 'success') {
                document.getElementById('totalVoters').innerText = data.total_voters;
                document.getElementById('totalCandidates').innerText = data.total_candidates;
                document.getElementById('totalVotes').innerText = data.total_votes;
                document.getElementById('electionStatusDisplay').innerText = data.election_status;
                document.getElementById('electionPhaseSelect').value = data.election_status;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async function loadUserAccounts() {
        try {
            const response = await fetch('manage_users.php?action=list');
            const data = await response.json();
            if (data.status === 'success') {
                const officersTable = document.getElementById('officersTable');
                const candidatesTable = document.getElementById('candidatesTable');
                const votersTable = document.getElementById('votersTable');
                const adminsTable = document.getElementById('adminsTable');
                
                if(officersTable) officersTable.innerHTML = '';
                if(candidatesTable) candidatesTable.innerHTML = '';
                if(votersTable) votersTable.innerHTML = '';
                if(adminsTable) adminsTable.innerHTML = '';

                data.data.forEach(user => {
                    const deleteBtnHtml = (user.id == CURRENT_USER_ID) ? '' : 
                        `<button onclick="deleteUser(${user.id})" class="action-btn" style="padding: 5px 10px; font-size: 12px; background: #dc3545; color: white; margin-left: 5px;">Delete</button>`;

                    let documentHtml = '';
                    if (user.role === 'candidate' || user.role === 'voter') {
                        documentHtml = `<td>${user.document ? `<a href="${user.document}" target="_blank" class="btn-blue" style="padding: 5px 10px; font-size: 12px; text-decoration: none; display: inline-block; border-radius: 3px; color: white; background-color: #007bff;"><i class="fas fa-file-alt"></i> View Document</a>` : '<span style="color: #999;">N/A</span>'}</td>`;
                    }

                    const row = `<tr>
                        <td>${user.first_name ? user.first_name + (user.middle_name ? ' ' + user.middle_name : '') + ' ' + (user.last_name || '') : 'N/A'}</td>
                        <td>${user.username}</td>
                        <td>${user.email || 'N/A'}</td>
                        <td>${user.phone || 'N/A'}</td>
                        ${(user.role === 'candidate' || user.role === 'voter') ? documentHtml : ''}
                        <td><span class="badge ${user.status === 'active' ? 'badge-approved' : 'badge-pending'}">${user.status}</span></td>
                        <td>
                            <button onclick="editUser(${user.id})" class="action-btn" style="padding: 5px 10px; font-size: 12px; margin-right: 5px; background: #007bff; color: white;">Edit</button>
                            <button onclick="toggleUserStatus(${user.id})" class="action-btn ${user.status === 'active' ? 'btn-red' : 'btn-approve'}" style="padding: 5px 10px; font-size: 12px;">
                                ${user.status === 'active' ? 'Deactivate' : 'Activate'}
                            </button>
                            ${deleteBtnHtml}
                        </td>
                    </tr>`;
                    
                    if (user.role === 'officer' && officersTable) officersTable.innerHTML += row;
                    else if (user.role === 'candidate' && candidatesTable) candidatesTable.innerHTML += row;
                    else if (user.role === 'voter' && votersTable) votersTable.innerHTML += row;
                    else if ((user.role === 'admin' || user.role === 'super_admin') && adminsTable) adminsTable.innerHTML += row;
                });
            }
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    async function deleteUser(userId) {
        if (!confirm("Are you sure you want to permanently delete this account?")) return;
        
        const formData = new FormData();
        formData.append('user_id', userId);
        try {
            const response = await fetch('manage_users.php?action=delete_user', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            showNotification(data.message, data.status === 'success' ? 'success' : 'error');
            if (data.status === 'success') {
                loadUserAccounts();
                loadOverviewStats();
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            showNotification('Failed to delete user.', 'error');
        }
    }

    async function loadAuditLogs() {
        const role = document.getElementById('auditRoleFilter').value;
        try {
            const response = await fetch(`get_audit_logs.php?role=${role}`);
            const data = await response.json();
            if (data.status === 'success') {
                const tbody = document.getElementById('auditLogsBody');
                tbody.innerHTML = '';
                data.data.forEach(log => {
                    tbody.innerHTML += `<tr>
                        <td>${log.created_at}</td>
                        <td>${log.username || 'N/A'}</td>
                        <td>${log.role || 'System'}</td>
                        <td>${log.action}</td>
                        <td>${log.details}</td>
                    </tr>`;
                });
            }
        } catch (error) {
            console.error('Error loading audit logs:', error);
        }
    }

    async function refreshScrutinyTable() {
        const table = document.getElementById('scrutinyTableBody');
        try {
            const response = await fetch('get_candidates.php');
            const result = await response.json();
            if (result.status === 'success') {
                table.innerHTML = '';
                result.data.forEach((c) => {
                    table.innerHTML += `<tr>
                        <td>${c.name}</td>
                        <td>${c.education}</td>
                        <td>${c.experience} yrs</td>
                        <td>
                            ${c.document ? `<a href="${c.document}" target="_blank" class="btn-blue" style="padding: 5px 10px; font-size: 12px; text-decoration: none; display: inline-block; border-radius: 3px; color: white; background-color: #007bff;"><i class="fas fa-file-alt"></i> View Document</a>` : '<span style="color: #999;">N/A</span>'}
                        </td>
                        <td><span class="badge ${c.status === 'Final' ? 'badge-approved' : 'badge-pending'}">${c.status}</span></td>
                        <td>
                            <button onclick="approveCandidate('${c.id}')" class="action-btn btn-approve" ${c.status === 'Final' ? 'disabled' : ''}>
                                <i class="fas fa-check"></i> Finalize
                            </button>
                        </td>
                    </tr>`;
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
            showNotification(result.message, result.status === 'success' ? 'success' : 'error');
            if (result.status === 'success') refreshScrutinyTable();
        } catch (error) {
            console.error('Error finalizing candidate:', error);
        }
    }

    async function loadReport(type) {
        const container = document.getElementById('reportContainer');
        const title = document.getElementById('reportTitle');
        const content = document.getElementById('reportContent');
        
        container.style.display = 'block';
        content.innerHTML = 'Loading report...';

        try {
            const response = await fetch(`get_election_reports.php?type=${type}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                if (type === 'voters') {
                    title.innerText = 'Voter Participation Report';
                    const d = result.data;
                    content.innerHTML = `<div class="stats-grid">
                        <div class="stat-card"><h3>${d.total_registered}</h3><p>Registered</p></div>
                        <div class="stat-card"><h3>${d.total_approved}</h3><p>Approved</p></div>
                        <div class="stat-card"><h3>${d.total_voted}</h3><p>Voted</p></div>
                    </div>`;
                } else if (type === 'audit') {
                    title.innerText = 'System Audit Summary';
                    let table = '<table class="data-table"><thead><tr><th>Action</th><th>Frequency</th></tr></thead><tbody>';
                    result.data.forEach(r => {
                        table += `<tr><td>${r.action}</td><td>${r.count}</td></tr>`;
                    });
                    table += '</tbody></table>';
                    content.innerHTML = table;
                } else if (type === 'results') {
                    title.innerText = 'Final Election Results';
                    let table = '<table class="data-table"><thead><tr><th>Candidate</th><th>Votes Received</th></tr></thead><tbody>';
                    result.data.forEach(r => {
                        table += `<tr><td>${r.name}</td><td><strong>${r.total_votes}</strong></td></tr>`;
                    });
                    table += '</tbody></table>';
                    content.innerHTML = table;
                }
            } else {
                content.innerHTML = `<p style="color:red;">${result.message}</p>`;
            }
        } catch (error) {
            content.innerHTML = 'Failed to load report.';
        }
    }

    async function toggleUserStatus(userId) {
        const formData = new FormData();
        formData.append('user_id', userId);
        try {
            const response = await fetch('manage_users.php?action=toggle_status', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.status === 'success') {
                loadUserAccounts();
                loadOverviewStats();
            } else {
                showNotification(data.message, data.status === 'success' ? 'success' : 'error');
            }
        } catch (error) {
            console.error('Error toggling status:', error);
        }
    }

    async function editUser(userId) {
        try {
            const response = await fetch(`manage_users.php?action=get_user&id=${userId}`);
            const data = await response.json();
            if (data.status === 'success') {
                const user = data.data;
                document.getElementById('edit_user_id').value = user.id;
                document.getElementById('edit_username').value = user.username;
                document.getElementById('edit_role').value = user.role.charAt(0).toUpperCase() + user.role.slice(1);
                document.getElementById('edit_first_name').value = user.first_name || '';
                
                const middleNameContainer = document.getElementById('edit_middle_name_container');
                if (user.role === 'voter' || user.role === 'candidate') {
                    middleNameContainer.style.display = 'block';
                    document.getElementById('edit_middle_name').value = user.middle_name || '';
                } else {
                    middleNameContainer.style.display = 'none';
                    document.getElementById('edit_middle_name').value = '';
                }

                document.getElementById('edit_last_name').value = user.last_name || '';
                document.getElementById('edit_email').value = user.email || '';
                document.getElementById('edit_phone').value = user.phone || '';
                document.getElementById('edit_password').value = '';
                
                document.getElementById('editUserModal').style.display = 'block';
            } else {
                showNotification(data.message, data.status === 'success' ? 'success' : 'error');
            }
        } catch (error) {
            console.error('Error fetching user:', error);
            showNotification('Failed to load user data.', 'error');
        }
    }

    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }

    document.getElementById('editUserForm').onsubmit = async function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure you want to save these changes?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            showClass: { popup: 'animate__animated animate__fadeInDown' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp' }
        }).then(async (result) => {
            if (result.isConfirmed) {
                const formData = new FormData(document.getElementById('editUserForm'));
                try {
                    const response = await fetch('manage_users.php?action=update_user', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Successfully edited.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            showClass: { popup: 'animate__animated animate__fadeInDown' }
                        });
                        closeEditModal();
                        loadUserAccounts();
                    } else {
                        Swal.fire({
                            title: 'Edit failed. Please try again.',
                            text: data.message,
                            icon: 'error',
                            showConfirmButton: true,
                            showClass: { popup: 'animate__animated animate__shakeX' }
                        });
                    }
                } catch (error) {
                    console.error('Error updating user:', error);
                    Swal.fire({
                        title: 'Edit failed. Please try again.',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        showConfirmButton: true
                    });
                }
            }
        });
    };

    document.getElementById('officerRegForm').onsubmit = async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const response = await fetch('register_officer.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            showNotification(data.message, data.status === 'success' ? 'success' : 'error');
            if (data.status === 'success') {
                this.reset();
                loadUserAccounts();
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('', 'info');
        }
    };

    function updateElectionPhase() {
        const phase = document.getElementById('electionPhaseSelect').value;
        Swal.fire({
            title: 'Confirm Phase Change',
            text: `Are you sure you want to change the election phase to "${phase}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'Cancel',
            showClass: { popup: 'animate__animated animate__fadeInDown' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp' }
        }).then(async (result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('phase', phase);
                try {
                    const response = await fetch('system_settings.php?action=update_phase', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Updated!',
                            text: 'The election phase has been changed successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            showClass: { popup: 'animate__animated animate__fadeInDown' }
                        });
                        loadOverviewStats();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to update election phase.',
                            icon: 'error',
                            showClass: { popup: 'animate__animated animate__shakeX' }
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                }
            } else {
                loadOverviewStats(); // reset select to current phase if cancelled
            }
        });
    }

    function showModule(moduleId) {
        document.querySelectorAll('.module-content').forEach(m => m.classList.remove('active'));
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

        document.getElementById(moduleId).classList.add('active');
        event.currentTarget.classList.add('active');

        if (moduleId === 'account-management') loadUserAccounts();
        if (moduleId === 'admin-overview') loadOverviewStats();
        if (moduleId === 'system-control') loadAuditLogs();
        if (moduleId === 'candidate-scrutiny') refreshScrutinyTable();
        if (moduleId === 'categorize-voters') loadVoterCategories();
        if (moduleId === 'categorize-candidates') loadCandidateCategories();
    }

    async function loadVoterCategories() {
        try {
            const response = await fetch('manage_categories.php?action=get_voters');
            const data = await response.json();
            if (data.status === 'success') {
                const tbody = document.getElementById('categorizeVotersBody');
                tbody.innerHTML = '';
                data.data.forEach(v => {
                    tbody.innerHTML += `<tr>
                        <td>${v.first_name} ${v.last_name}</td>
                        <td>${v.voter_id_str}</td>
                        <td>${v.share_value}</td>
                        <td>${v.document ? `<a href="${v.document}" target="_blank" class="btn-blue" style="padding: 5px 10px; font-size: 12px; text-decoration: none; display: inline-block; border-radius: 3px; color: white; background-color: #007bff;"><i class="fas fa-file-alt"></i> View Document</a>` : '<span style="color: #999;">N/A</span>'}</td>
                        <td><span class="badge ${v.voter_category === 'Uncategorized' ? 'badge-pending' : 'badge-approved'}">${v.voter_category}</span></td>
                        <td>
                            <select id="vc_${v.id}" style="padding:5px;">
                                <option value="Uncategorized" ${v.voter_category === 'Uncategorized' ? 'selected' : ''}>Uncategorized</option>
                                <option value="Non-Influential Shareholder" ${v.voter_category === 'Non-Influential Shareholder' ? 'selected' : ''}>Non-Influential Shareholder</option>
                                <option value="Influential Shareholder" ${v.voter_category === 'Influential Shareholder' ? 'selected' : ''}>Influential Shareholder</option>
                            </select>
                            <button onclick="updateCategory('voter', ${v.id}, 'vc_${v.id}')" class="action-btn btn-approve" style="padding: 5px 10px;">Save</button>
                        </td>
                    </tr>`;
                });
            }
        } catch (error) {
            console.error('Error loading voter categories:', error);
        }
    }

    async function loadCandidateCategories() {
        try {
            const response = await fetch('manage_categories.php?action=get_candidates');
            const data = await response.json();
            if (data.status === 'success') {
                const tbody = document.getElementById('categorizeCandidatesBody');
                tbody.innerHTML = '';
                data.data.forEach(c => {
                    tbody.innerHTML += `<tr>
                        <td>${c.first_name} ${c.last_name}</td>
                        <td>${c.candidate_id_str}</td>
                        <td>${c.education} / ${c.experience} yrs</td>
                        <td>${c.document ? `<a href="${c.document}" target="_blank" class="btn-blue" style="padding: 5px 10px; font-size: 12px; text-decoration: none; display: inline-block; border-radius: 3px; color: white; background-color: #007bff;"><i class="fas fa-file-alt"></i> View Document</a>` : '<span style="color: #999;">N/A</span>'}</td>
                        <td><span class="badge ${c.candidate_category === 'Uncategorized' ? 'badge-pending' : 'badge-approved'}">${c.candidate_category}</span></td>
                        <td>
                            <select id="cc_${c.id}" style="padding:5px;">
                                <option value="Uncategorized" ${c.candidate_category === 'Uncategorized' ? 'selected' : ''}>Uncategorized</option>
                                <option value="Non-Influential Shareholder Candidate" ${c.candidate_category === 'Non-Influential Shareholder Candidate' ? 'selected' : ''}>Non-Influential Shareholder Candidate</option>
                                <option value="Influential Shareholder Candidate" ${c.candidate_category === 'Influential Shareholder Candidate' ? 'selected' : ''}>Influential Shareholder Candidate</option>
                                <option value="Independent Candidate" ${c.candidate_category === 'Independent Candidate' ? 'selected' : ''}>Independent Candidate</option>
                            </select>
                            <button onclick="updateCategory('candidate', ${c.id}, 'cc_${c.id}')" class="action-btn btn-approve" style="padding: 5px 10px;">Save</button>
                        </td>
                    </tr>`;
                });
            }
        } catch (error) {
            console.error('Error loading candidate categories:', error);
        }
    }

    async function updateCategory(type, id, selectId) {
        const category = document.getElementById(selectId).value;
        const formData = new FormData();
        formData.append('id', id);
        formData.append('category', category);
        
        try {
            const response = await fetch(`manage_categories.php?action=update_${type}`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.status === 'success') {
                showNotification(result.message, result.status === 'success' ? 'success' : 'error');
                if (type === 'voter') loadVoterCategories();
                else loadCandidateCategories();
            } else {
                showNotification('Error: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error updating category:', error);
            showNotification('', 'info');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadOverviewStats();
        
        const searchInput = document.getElementById('userSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tables = ['adminsTable', 'officersTable', 'candidatesTable', 'votersTable'];
                
                tables.forEach(tableId => {
                    const tbody = document.getElementById(tableId);
                    if (tbody) {
                        const rows = tbody.getElementsByTagName('tr');
                        for (let row of rows) {
                            let text = row.innerText.toLowerCase();
                            if (text.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    }
                });
            });
        }
    });
    </script>
    <!-- Edit User Modal -->
    <div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
        <div style="background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px; margin: 50px auto; position: relative;">
            <h2 style="margin-bottom: 20px;"><i class="fas fa-edit"></i> Edit User Info</h2>
            <form id="editUserForm">
                <input type="hidden" name="edit_user_id" id="edit_user_id">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" id="edit_role" readonly style="background: #eee;">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="edit_username" id="edit_username" required>
                    </div>
                    <div class="form-group">
                        <label>New Password (leave blank to keep current)</label>
                        <input type="password" name="edit_password" id="edit_password" placeholder="Enter new password to change">
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="edit_first_name" id="edit_first_name" required>
                    </div>
                    <div class="form-group" id="edit_middle_name_container" style="display: none;">
                        <label>Middle Name</label>
                        <input type="text" name="edit_middle_name" id="edit_middle_name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="edit_last_name" id="edit_last_name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="edit_email" id="edit_email">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="edit_phone" id="edit_phone">
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-red" style="flex: 1; border: none; padding: 12px; border-radius: 5px; color: white; cursor: pointer; font-weight: bold;"><i class="fas fa-save"></i> Save Changes</button>
                    <button type="button" onclick="closeEditModal()" style="flex: 1; background: #6c757d; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
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




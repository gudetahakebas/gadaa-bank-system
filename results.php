<?php
require_once 'db_config.php';

// Fetch election status
$stmtStatus = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'election_status'");
$electionStatus = $stmtStatus->fetchColumn();

$results_published = ($electionStatus === 'Results Published');

$candidates = [];
$grouped_candidates = [];
$max_votes = 1;

if ($results_published) {
    // Fetch results
    $stmt = $pdo->query("SELECT 
            c.id, c.candidate_category, c.first_name, c.middle_name, c.last_name, c.photo_path,
            COUNT(v.id) as total_votes
        FROM candidates c
        LEFT JOIN votes v ON c.id = v.candidate_id
        WHERE c.status = 'Final'
        GROUP BY c.id
        ORDER BY candidate_category ASC, total_votes DESC");
    $candidates = $stmt->fetchAll();
    
    foreach ($candidates as $c) {
        $cat = $c['candidate_category'];
        if (!isset($grouped_candidates[$cat])) {
            $grouped_candidates[$cat] = [];
        }
        $grouped_candidates[$cat][] = $c;
        if ($c['total_votes'] > $max_votes) {
            $max_votes = $c['total_votes'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gadaa Bank Board of Directors Election Results. Transparency and integrity in leadership.">
    <title>Election Results | Gadaa Bank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        .results-hero {
            height: 40vh;
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.9) 0%, rgba(214, 31, 38, 0.4) 100%),
                url("assets/images/hero.jpg");
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .results-container {
            max-width: 1200px;
            margin: -50px auto 80px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .status-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 40px;
        }

        .status-card h2 {
            font-size: 32px;
            color: var(--deep-black);
            margin-bottom: 15px;
        }

        .status-card p {
            font-size: 18px;
            color: #666;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .candidate-result-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .candidate-result-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(214, 31, 38, 0.1);
        }

        .rank-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--primary-red);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            z-index: 5;
            box-shadow: 0 5px 15px rgba(214, 31, 38, 0.3);
        }

        .candidate-header {
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .candidate-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .candidate-result-card:hover .candidate-header img {
            transform: scale(1.1);
        }

        .candidate-info {
            padding: 30px;
            text-align: center;
            flex-grow: 1;
        }

        .candidate-info h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--deep-black);
        }

        .vote-count {
            font-size: 48px;
            font-weight: 800;
            color: var(--primary-red);
            margin: 15px 0;
        }

        .vote-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #888;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .status-elected {
            background: #e6fcf5;
            color: #0ca678;
            border: 1px solid #0ca678;
        }

        .status-bench {
            background: #fff4e6;
            color: #f76707;
            border: 1px solid #f76707;
        }

        .status-regular {
            background: #f1f3f5;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .coming-soon {
            padding: 100px 20px;
            text-align: center;
        }

        .coming-soon i {
            font-size: 80px;
            color: var(--primary-red);
            margin-bottom: 30px;
            opacity: 0.5;
        }

        .coming-soon h2 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        /* Chart Section */
        .chart-section {
            background: var(--deep-black);
            color: white;
            padding: 100px 0;
            margin-top: 50px;
        }

        .chart-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .bar-wrapper {
            margin-bottom: 25px;
        }

        .bar-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .bar-bg {
            height: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-red), #ff4d54);
            border-radius: 10px;
            width: 0;
            transition: width 1.5s cubic-bezier(0.1, 0.42, 0.41, 1);
        }
    </style>
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
            <small>New Generation’s Bank</small>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="main-nav">
        <a href="index.html">Home</a>
        <a href="about-us.php">About us</a>
        <a href="#">Candidates</a>
        <a href="results.php">Results</a>
        <a href="login-selection.html">Login</a>
        <a href="#">Contact</a>
    </nav>

    <header class="results-hero">
        <div class="breadcrumb">
            <a href="index.html">Home</a> / Results
        </div>
        <h1>Election <span>Results</span></h1>
    </header>

    <main class="results-container">
        <?php if ($results_published): ?>
            <div class="status-card">
                <h2>Official Results Published</h2>
                <p>The following candidates have been elected to the Board of Directors based on the total valid votes cast.</p>
            </div>

            <?php foreach ($grouped_candidates as $category => $category_candidates): ?>
                <h3 style="margin-top: 40px; margin-bottom: 20px; font-size: 28px; color: var(--primary-red); border-bottom: 2px solid #eee; padding-bottom: 10px;"><?php echo htmlspecialchars($category); ?></h3>
                <div class="results-grid">
                    <?php 
                    $rank = 1;
                    foreach ($category_candidates as $c): 
                        $fullName = $c['first_name'] . ' ' . $c['middle_name'] . ' ' . $c['last_name'];
                        $photo = !empty($c['photo_path']) ? 'uploads/candidates/' . $c['photo_path'] : 'assets/images/default-avatar.png';
                        
                        $statusClass = 'status-regular';
                        $statusText = 'Candidate';
                        
                        if ($rank <= 3) {
                            $statusClass = 'status-elected';
                            $statusText = 'Elected';
                        } elseif ($rank == 4) {
                            $statusClass = 'status-bench';
                            $statusText = 'Reserve (Bench)';
                        }
                    ?>
                        <div class="candidate-result-card">
                            <div class="candidate-header">
                                <div class="rank-badge">Rank #<?php echo $rank; ?></div>
                                <img src="<?php echo $photo; ?>" alt="<?php echo $fullName; ?>">
                            </div>
                            <div class="candidate-info">
                                <h3><?php echo $fullName; ?></h3>
                                <div class="vote-label">Total Votes</div>
                                <div class="vote-count"><?php echo number_format($c['total_votes']); ?></div>
                                <div class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $rank++;
                    endforeach; 
                    ?>
                </div>
            <?php endforeach; ?>

            <!-- Visual Analytics -->
            <div class="chart-section" style="border-radius: 40px; margin-top: 80px;">
                <div class="chart-container">
                    <h2 style="font-size: 36px; margin-bottom: 50px; text-align: center;">Vote Distribution</h2>
                    <?php foreach ($candidates as $c): 
                        $percentage = ($max_votes > 0) ? ($c['total_votes'] / $max_votes) * 100 : 0;
                        $fullName = $c['first_name'] . ' ' . $c['last_name'];
                    ?>
                        <div class="bar-wrapper">
                            <div class="bar-info">
                                <span><?php echo $fullName; ?></span>
                                <span><?php echo number_format($c['total_votes']); ?> Votes</span>
                            </div>
                            <div class="bar-bg">
                                <div class="bar-fill" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="coming-soon">
                <i class="fas fa-hourglass-half"></i>
                <h2>Results Coming Soon</h2>
                <p>The election is currently in progress or results are being verified. Please check back later for the official announcement.</p>
                <div style="margin-top: 40px;">
                    <a href="index.html" class="btn-red" style="padding: 15px 40px; display: inline-block;">Return Home</a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2026 Gadaa Bank. All Rights Reserved. | Secure Digital Governance</p>
    </footer>

    <script>
        // Trigger bar animations on load
        window.addEventListener('DOMContentLoaded', () => {
            const bars = document.querySelectorAll('.bar-fill');
            bars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>

</body>

</html>

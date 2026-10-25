<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gadaa Bank - Company Overview. Establishment, Vision, Mission and G.A.D.A.A Core Values.">
    <title>Company Overview | Gadaa Bank</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        /* Specific overrides to match the 'Login' style icon boxes */
        .icon-box {
            width: 90px;
            height: 90px;
            background: var(--deep-black);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: rotate(-5deg);
            margin-left: auto;
            margin-right: auto;
        }

        .vm-card:hover .icon-box, .gadaa-item:hover .icon-box {
            background: var(--primary-red);
            transform: rotate(0deg) scale(1.1);
            border-radius: 50%;
        }

        .icon-box img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            filter: invert(1);
        }

        .vm-card:hover .icon-box img, .gadaa-item:hover .icon-box img {
            filter: invert(0);
        }

        .about-hero {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.4)), url('assets/images/about_us_hero.jpg') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }

        .fade-in-image {
            animation: imageFadeIn 2.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            opacity: 0;
            transition: all 0.5s ease;
        }

        .fade-in-image:hover {
            transform: scale(1.03) !important;
            box-shadow: 0 50px 100px rgba(214, 31, 38, 0.3) !important;
            filter: brightness(1.1);
        }

        @keyframes imageFadeIn {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(50px);
                filter: blur(20px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
                filter: blur(0);
            }
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

    <!-- Hero Section -->
    <section class="about-hero" style="height: 55vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: white; padding: 0 20px;">
        <div class="breadcrumb" style="background: rgba(255,255,255,0.1); padding: 8px 20px; border-radius: 50px; backdrop-filter: blur(5px); margin-bottom: 20px; text-transform: uppercase; font-size: 14px; letter-spacing: 2px;">
            <a href="index.html" style="color: white; text-decoration: none;">Home</a> > Company Overview
        </div>
        <h1 style="font-size: 72px; font-weight: 900; letter-spacing: -2px; margin: 0;">Company Overview</h1>
    </section>

    <!-- Establishment Section -->
    <section class="section-container" style="margin-top: 100px;">
        <div class="establishment-section">
            <div class="establishment-text">
                <h2 style="font-size: 48px; font-weight: 900; margin-bottom: 30px;">Establishment</h2>
                <p style="font-size: 19px; line-height: 1.8; color: #444; margin-bottom: 25px;">
                    Gadaa Bank is established to contribute towards economic empowerment and holistic transformation of the low & medium-income section of the population in the country. The founding members made an intensive reflection on the livelihoods of large majority of Ethiopian farmers, pastoralists, young college graduates with entrepreneurial skills and women who do not have access to financial services.
                </p>
                <p style="font-size: 19px; line-height: 1.8; color: #444; margin-bottom: 25px;">
                    These has made the establishment of the bank with more than <strong>28,000 shareholders</strong>. Founding shareholders deserve appreciation in the establishment of Gadaa Bank.
                </p>
                <p style="font-size: 19px; line-height: 1.8; color: #444;">
                    Licensed on 26th April 2022, Gadaa Bank has joined the Ethiopian banking business with more than <strong>ETB 1.167 billion</strong> subscribed capital.
                </p>
            </div>
            <div class="establishment-image">
                <img src="assets/images/about_us_hero.jpg" alt="Gadaa Bank Corporate" class="fade-in-image" style="width: 100%; border-radius: 40px; box-shadow: 0 40px 80px rgba(0,0,0,0.25);">
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="section-container" style="margin-top: 80px;">
        <div class="vision-mission-grid">
            <div class="vm-card glass-card" style="text-align: center; padding: 60px;">
                <div class="icon-box">
                    <img src="assets/images/vision_icon.png" alt="Vision Icon">
                </div>
                <h3>Our Vision</h3>
                <p>“To Inspire and Enable Your Dream.”</p>
            </div>
            <div class="vm-card glass-card" style="text-align: center; padding: 60px;">
                <div class="icon-box">
                    <img src="assets/images/mission_icon.png" alt="Mission Icon">
                </div>
                <h3>Our Mission</h3>
                <p>“We are committed to deliver superior and customer-centric full-fledged banking services to our community in a friendly environment by deploying competent employees and art –of –technology whilst optimizing shareholders’ value.”</p>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="core-values-section" style="background: #0b0b0b; padding: 120px 0; margin-top: 100px;">
        <div class="core-values-header" style="text-align: center; margin-bottom: 80px;">
            <h2 style="font-size: 64px; font-weight: 950; color: white;">G.A.D.A.A Values</h2>
        </div>
        
        <div class="gadaa-grid">
            <!-- G -->
            <div class="gadaa-item">
                <div class="icon-box" style="width: 70px; height: 70px; margin-bottom: 20px;">
                    <i class="fas fa-arrow-up-right-dots" style="color: white; font-size: 24px;"></i>
                </div>
                <span class="letter">G</span>
                <span class="value-title">Growth Mindset</span>
                <ul class="value-list">
                    <li>Innovative</li>
                    <li>Hard Worker</li>
                    <li>Higher Achievement</li>
                </ul>
            </div>
            <!-- A -->
            <div class="gadaa-item">
                <div class="icon-box" style="width: 70px; height: 70px; margin-bottom: 20px;">
                    <i class="fas fa-handshake" style="color: white; font-size: 24px;"></i>
                </div>
                <span class="letter">A</span>
                <span class="value-title">Approachable</span>
                <ul class="value-list">
                    <li>Friendly Partner</li>
                    <li>Easy of doing</li>
                </ul>
            </div>
            <!-- D -->
            <div class="gadaa-item">
                <div class="icon-box" style="width: 70px; height: 70px; margin-bottom: 20px;">
                    <i class="fas fa-users-line" style="color: white; font-size: 24px;"></i>
                </div>
                <span class="letter">D</span>
                <span class="value-title">Diversity</span>
                <ul class="value-list">
                    <li>Inclusive</li>
                    <li>Respectful</li>
                    <li>Participatory</li>
                </ul>
            </div>
            <!-- A -->
            <div class="gadaa-item">
                <div class="icon-box" style="width: 70px; height: 70px; margin-bottom: 20px;">
                    <i class="fas fa-shield-halved" style="color: white; font-size: 24px;"></i>
                </div>
                <span class="letter">A</span>
                <span class="value-title">Accountability</span>
                <ul class="value-list">
                    <li>Professionalism</li>
                    <li>Reliable</li>
                    <li>Transparent</li>
                </ul>
            </div>
            <!-- A -->
            <div class="gadaa-item">
                <div class="icon-box" style="width: 70px; height: 70px; margin-bottom: 20px;">
                    <i class="fas fa-globe-africa" style="color: white; font-size: 24px;"></i>
                </div>
                <span class="letter">A</span>
                <span class="value-title">Accessibility</span>
                <ul class="value-list">
                    <li>Reachable</li>
                    <li>Digitized</li>
                    <li>Affordable</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Join Us CTA -->
    <section class="section-container" style="text-align: center; margin: 100px auto;">
        <div style="background: linear-gradient(135deg, #111 0%, var(--primary-red) 100%); padding: 100px 40px; border-radius: 50px; color: white; box-shadow: 0 40px 80px rgba(214, 31, 38, 0.2);">
            <h2 style="font-size: 52px; font-weight: 900; margin-bottom: 25px;">New Generation's Bank!</h2>
            <p style="font-size: 22px; margin-bottom: 45px; opacity: 0.9;">Ready to experience a first-class banking service?</p>
            <a href="voter-login.html" class="btn-red" style="padding: 20px 60px; font-size: 18px; background: white; color: var(--primary-red); font-weight: 800; text-decoration: none; border-radius: 50px;">Join us Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer style="margin-top: 100px;">
        <p>© 2026 Gadaa Bank. All Rights Reserved. | Secure Digital Governance</p>
    </footer>

</body>

</html>

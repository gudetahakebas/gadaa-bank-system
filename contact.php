<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Gadaa Bank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        .contact-hero {
            position: relative;
            height: 50vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            overflow: hidden;
            background-color: #111;
        }

        .hero-slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .hero-slideshow div {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation: slideCrossfade 25s linear infinite;
        }

        @keyframes slideCrossfade {
            0% { opacity: 0; transform: scale(1); }
            4% { opacity: 1; }
            20% { opacity: 1; }
            24% { opacity: 0; transform: scale(1.1); }
            100% { opacity: 0; }
        }

        .hero-slideshow div:nth-child(1) { 
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.3)), url('assets/images/Abba-Gadaa.jpg');
            animation-delay: 0s; 
        }
        .hero-slideshow div:nth-child(2) { 
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.3)), url('assets/images/event in mill.jpg');
            animation-delay: 5s; 
        }
        .hero-slideshow div:nth-child(3) { 
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.3)), url('assets/images/nomination process.jpg');
            animation-delay: 10s; 
        }
        .hero-slideshow div:nth-child(4) { 
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.3)), url('assets/images/old registration.jpg');
            animation-delay: 15s; 
        }
        .hero-slideshow div:nth-child(5) { 
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.3)), url('assets/images/small room.jpg');
            animation-delay: 20s; 
        }
        
        .contact-hero h1 {
            font-size: 84px;
            font-weight: 950;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -2px;
            text-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: slideInDown 1s ease-out;
            position: relative;
            z-index: 1;
        }

        .custom-icon {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--primary-red, #d61f26);
            color: white;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .social-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(214, 31, 38, 0.4);
            background-color: #b5151b;
            color: white;
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
        <a href="candidates.php">Candidates</a>
        <a href="results.php">Results</a>
        <a href="login-selection.html">Login</a>
        <a href="contact.php" class="active" style="background-color: rgba(0, 0, 0, 0.1);">Contact Us</a>
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="hero-slideshow">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <h1 style="color: white; text-shadow: 0 5px 15px rgba(0,0,0,0.5);">Contact Us</h1>
    </section>

    <!-- Main Contact Section -->
    <section class="section-container" style="margin: 80px auto;">
        <div class="contact-grid">
            
            <!-- Left Side: Information -->
            <div class="contact-info-wrapper">
                <h2 style="font-size: 42px; font-weight: 900; margin-bottom: 20px;">Contact us</h2>
                <p style="font-size: 18px; color: #555; line-height: 1.8; margin-bottom: 40px;">
                    Please let us know if you have a question, want to leave a comment, or would like further information about Gadaa Bank!
                </p>

                <div class="contact-info-card">
                    <div class="info-icon" style="padding: 0; overflow: hidden;"><img src="assets/images/call.jpg" alt="Call Us" class="custom-icon"></div>
                    <div class="info-text">
                        <h3>Call us</h3>
                        <p>+251 116392578</p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="info-icon" style="padding: 0; overflow: hidden;"><img src="assets/images/hotline.jpg" alt="Hotline" class="custom-icon"></div>
                    <div class="info-text">
                        <h3>Hotline</h3>
                        <p>641</p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="info-icon" style="padding: 0; overflow: hidden;"><img src="assets/images/question.jpg" alt="Questions" class="custom-icon"></div>
                    <div class="info-text">
                        <h3>Have any questions?</h3>
                        <p>info@gadaabank.com.et</p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="info-icon" style="padding: 0; overflow: hidden;"><img src="assets/images/head office.jpg" alt="Head Office" class="custom-icon"></div>
                    <div class="info-text">
                        <h3>Head Office</h3>
                        <p>Gotera | kirkos SubCity W-03, HNo-#745</p>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

                <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 20px;">Business Hours</h3>
                <div class="business-hours" style="margin-bottom: 30px;">
                    <p style="color: #666; margin-bottom: 5px;"><strong>Monday - Friday:</strong> 8:00 AM - 5:00 PM</p>
                    <p style="color: #666; margin-bottom: 5px;"><strong>Saturday:</strong> 8:00 AM - 12:00 PM</p>
                    <p style="color: var(--primary-red); font-weight: bold;"><strong>Sunday:</strong> Closed</p>
                </div>

                <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 20px;">Follow Us</h3>
                <div class="social-links">
                    <a href="#" class="social-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                    </a>
                    <a href="#" class="social-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.6 318.1 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
                    </a>
                    <a href="#" class="social-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
                    </a>
                    <a href="#" class="social-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                    </a>
                </div>

            </div>

            <!-- Right Side: Contact Form -->
            <div class="contact-form-wrapper">
                <h2 style="font-size: 36px; font-weight: 900; margin-bottom: 10px; color: white;">How can we help you out?</h2>
                <p style="color: #ccc; margin-bottom: 30px;">Reach out to us in the nearest office.</p>
                
                <form action="#" method="POST" class="premium-form">
                    <div class="input-group">
                        <input type="text" id="fullname" required placeholder=" ">
                        <label for="fullname">Full Name *</label>
                    </div>
                    
                    <div class="input-group">
                        <input type="email" id="email" required placeholder=" ">
                        <label for="email">E-mail *</label>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" id="subject" required placeholder=" ">
                        <label for="subject">Subject *</label>
                    </div>
                    
                    <div class="input-group" style="height: 150px;">
                        <textarea id="message" required placeholder=" "></textarea>
                        <label for="message">Your Message *</label>
                    </div>
                    
                    <button type="submit" class="cta-premium-btn" style="width: 100%; margin-top: 20px; border: none; cursor: pointer;">Submit</button>
                </form>
            </div>
            
        </div>
    </section>

    <!-- Custom Footer -->
    <footer style="background: #111; color: #888; padding: 40px 0; text-align: center; border-top: 1px solid #222; margin-top: 100px;">
        <p style="font-size: 16px; letter-spacing: 2px;">© 2026 Gadaa Bank - - - - - - - - - - - Webmail</p>
    </footer>

</body>
</html>

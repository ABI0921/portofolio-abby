<?php
require_once 'config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio - <?php echo $full_name; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #f97316;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            transition: color 0.3s ease;
            padding: 8px 15px !important;
        }
        
        .nav-link:hover {
            color: var(--secondary-color) !important;
        }
        
        .user-welcome {
            color: var(--primary-color);
            font-weight: 600;
            margin-right: 15px;
        }
        
        .btn-logout {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background-color: #ea580c;
            border-color: #ea580c;
            transform: translateY(-2px);
        }

        .navbar-toggler {
            border: none;
            padding: 5px 10px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Hero Section dengan Background Foto Sekolah */
        .hero {
            height: 100vh;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 0 15px;
        }
        
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('foto_onedek.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }
        
        /* Overlay gelap untuk background */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
        }
        
        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 1rem;
            opacity: 0;
            animation: fadeIn 1s ease forwards;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeIn 1s ease 0.5s forwards;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s ease 1s forwards;
            font-size: 1rem;
            border-radius: 50px;
        }
        
        .btn-primary:hover {
            background-color: #ea580c;
            border-color: #ea580c;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Section Styling */
        section {
            padding: 80px 0;
        }
        
        .section-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 3rem;
            position: relative;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            text-align: center;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background-color: var(--secondary-color);
        }
        
        /* About Section */
        .profile-img-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .profile-img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid var(--light-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.5s ease;
        }
        
        .profile-img:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }
        
        .about-content h3 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .about-content p {
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 20px;
            color: #333;
        }
        
        .skill-badge {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            margin: 5px;
            font-size: 0.9rem;
        }
        
        /* Experience Section */
        .experience {
            background-color: #f8f9fa;
        }
        
        .timeline {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: var(--primary-color);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 50px;
            padding-left: 80px;
        }
        
        .timeline-content {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            position: relative;
        }
        
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            z-index: 1;
        }
        
        .timeline-content h4 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .timeline-content h5 {
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .timeline-content .text-muted {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .timeline-content p {
            color: #333;
        }
        
        /* Projects Section */
        .project-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            margin-bottom: 30px;
        }
        
        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .project-img {
            height: 200px;
            object-fit: cover;
            transition: all 0.5s ease;
            width: 100%;
        }
        
        .project-card:hover .project-img {
            transform: scale(1.05);
        }
        
        .project-card .card-body {
            padding: 25px;
        }
        
        .project-card .card-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .project-card .card-text {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #333;
        }
        
        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Contact Section */
        .contact {
            background-color: var(--dark-color);
            color: white;
        }
        
        .contact .section-title {
            color: white;
        }
        
        .contact .section-title::after {
            background-color: var(--secondary-color);
        }
        
        .contact-form {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-control {
            border-radius: 5px;
            border: 1px solid #374151;
            padding: 0.75rem;
            background-color: #1f2937;
            color: white;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            background-color: #1f2937;
            border-color: var(--secondary-color);
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1.5rem;
            margin: 0 10px 10px;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background-color: var(--secondary-color);
            color: white;
            transform: translateY(-5px);
        }
        
        /* Footer */
        footer {
            background-color: #111827;
            color: white;
            padding: 30px 0;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }
        
        .footer-links a:hover {
            color: var(--secondary-color);
        }
        
        /* Responsive Adjustments */
        @media (min-width: 768px) {
            .navbar-brand {
                font-size: 1.8rem;
            }
            
            .timeline::before {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .timeline-item {
                padding-left: 0;
                width: 50%;
            }
            
            .timeline-item:nth-child(odd) {
                padding-right: 40px;
                text-align: right;
                left: 0;
            }
            
            .timeline-item:nth-child(even) {
                padding-left: 40px;
                left: 50%;
            }
            
            .timeline-item:nth-child(odd) .timeline-icon {
                left: auto;
                right: -30px;
            }
            
            .timeline-item:nth-child(even) .timeline-icon {
                left: -30px;
            }
            
            .profile-img {
                width: 300px;
                height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            section {
                padding: 60px 0;
            }
            
            .hero {
                min-height: 500px;
            }
            
            .profile-img {
                width: 200px;
                height: 200px;
            }
            
            .timeline-content {
                padding: 20px;
            }
            
            .project-card .card-body {
                padding: 20px;
            }
            
            .user-welcome {
                font-size: 0.9rem;
                margin-right: 10px;
            }
            
            .btn-logout {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar dengan User Info -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">Muhammad Abby</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#experience">Experience</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#projects">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                
                <!-- User Info dan Logout -->
                <div class="d-flex align-items-center">
                    <span class="user-welcome">
                        <i class="bi bi-person-circle me-1"></i>
                        Hello, <?php echo htmlspecialchars($full_name); ?>
                    </span>
                    <a href="logout.php" class="btn btn-logout btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section dengan Background Foto Sekolah -->
    <section id="home" class="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Hi, I'm Abby</h1>
            <p>Web Developer | UI/UX Designer | Student</p>
            <a href="#contact" class="btn btn-primary btn-lg">Contact Me</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">About Me</h2>
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="profile-img-container" data-aos="fade-right">
                        <img src="foto_abi.jpeg" alt="Profile Picture" class="profile-img">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="about-content" data-aos="fade-left">
                        <h3>Hello! I'm Muhammad Abby</h3>
                        <p class="lead">
                            I'm a passionate web developer and designer with a strong interest in creating beautiful, functional, and user-friendly websites and applications. Currently, I'm pursuing my degree in Computer Science while working on various freelance projects.
                        </p>
                        <p>
                            With expertise in HTML, CSS, JavaScript, and modern frameworks like Bootstrap and React, I enjoy turning complex problems into simple, elegant solutions. When I'm not coding, you can find me exploring new design trends, reading tech blogs, or hiking in nature.
                        </p>
                        <div class="mt-4">
                            <span class="skill-badge">HTML/CSS</span>
                            <span class="skill-badge">JavaScript</span>
                            <span class="skill-badge">Bootstrap</span>
                            <span class="skill-badge">Laravel</span>
                            <span class="skill-badge">UI/UX Design</span>
                            <span class="skill-badge">Responsive Design</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="experience py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">My Experience</h2>
            <div class="timeline">
                <!-- Experience 1 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Frontend Developer</h4>
                        <h5>Figma and Bootstrap</h5>
                        <p class="text-muted">SMKN 1 DEPOK</p>
                        <p>create a website design using figma and bootstrap with a better website and modern design</p>
                    </div>
                </div>
                
                <!-- Experience 2 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Back End</h4>
                        <h5>Laravel</h5>
                        <p class="text-muted">SMKN 1 DEPOK</p>
                        <p>
I use Laravel for my back end because Laravel is easy to use and very useful for my back end.</p>
                    </div>
                </div>
                
                <!-- Experience 3 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-icon">
                        <i class="bi bi-code-slash"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Game Design 3D</h4>
                        <h5>
Making 3D Games using Unity</h5>
                        <p class="text-muted">SMKN 1 DEPOK</p>
                        <p>Apart from being able to make websites, I can also make 3D games using Unity and the games I produce are about adventure.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">My Projects</h2>
            <div class="row">
                <!-- Project 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card project-card">
                        <img src="design-web1.jpg" class="card-img-top project-img" alt="E-Commerce Website">
                        <div class="card-body">
                            <h5 class="card-title">Design Website</h5>
                            <p class="card-text">A responsive portfolio website with a modern design, showcasing profile, experience, projects, and contact in an interactive way.</p>
                            <a href="#" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Project 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card project-card">
                        <img src="design-web2.jpg" class="card-img-top project-img" alt="Task Management App">
                        <div class="card-body">
                            <h5 class="card-title">Design Website</h5>
                            <p class="card-text">A clean and interactive website design focused on storytelling, smooth animations, and user-friendly navigation.</p>
                            <a href="#" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Project 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card project-card">
                        <img src="game-3d.jpg" class="card-img-top project-img" alt="Travel Blog">
                        <div class="card-body">
                            <h5 class="card-title">GAME 3D</h5>
                            <p class="card-text">A 3D adventure game that offers an open-world environment with interactive elements, smooth controls, and immersive gameplay designed to provide players with an exciting exploration experience.</p>
                            <a href="#" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Get In Touch</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form class="contact-form" data-aos="fade-up">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                        </div>
                    </form>
                    
                    <div class="social-icons">
                        <a href="#" class="social-icon">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p>&copy; 2025 Muhammad Abby. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-links">
                        <a href="#home">Home</a>
                        <a href="#about">About</a>
                        <a href="#experience">Experience</a>
                        <a href="#projects">Projects</a>
                        <a href="#contact">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Initialize AOS -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            }
        });
        
        // Smooth scrolling for navbar links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu after clicking
                    const navbarToggler = document.querySelector('.navbar-toggler');
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    
                    if (navbarCollapse.classList.contains('show')) {
                        navbarToggler.click();
                    }
                }
            });
        });
    </script>
</body>
</html>
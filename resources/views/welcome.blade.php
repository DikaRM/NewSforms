<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMKN 1 Ciomas & Sforms - Inovasi Pendidikan</title>
    <meta name="description" content="SMKN 1 Ciomas (SKANIC) dan Inovasi Sforms: Aplikasi Ujian Anti Contek berbasis Web & Mobile.">
    <meta name="keywords" content="SMKN 1 Ciomas, SKANIC, Sforms, Ujian Online, Anti Contek, SMK Bogor">
    
    <!-- Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1e293b;
            overflow-x: hidden;
        }

        :root {
            --gold-accent: #ffcc33;
            --blue-primary: #2f5597;
            --blue-dark: #1e3a6b;
            --blue-light: #eff6ff;
            --bg-light: #ffffff;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --tech-bg: #0f172a;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(47, 85, 151, 0.1);
            transition: all 0.3s ease;
        }

        nav.scrolled {
            padding: 15px 50px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .nav-logo img {
            height: 35px;
            object-fit: contain;
        }

        .nav-logo span {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--blue-primary);
            letter-spacing: 0.5px;
        }

        .nav-login-btn {
            padding: 12px 30px;
            background: var(--blue-primary);
            border: 2px solid var(--blue-primary);
            color: #ffffff;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-login-btn:hover {
            background: var(--blue-dark);
            border-color: var(--blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(47, 85, 151, 0.2);
        }

        /* HERO SECTION */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            padding: 0 20px;
            overflow: hidden;
            background: radial-gradient(circle at 50% 50%, var(--blue-light) 0%, #ffffff 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(255, 204, 51, 0.1);
            filter: blur(80px);
            border-radius: 50%;
            top: -200px;
            right: -100px;
            z-index: 0;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(47, 85, 151, 0.1);
            filter: blur(80px);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
            z-index: 0;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: var(--gold-accent);
            border: 1px solid var(--gold-accent);
            border-radius: 50px;
            color: #1e293b;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(255, 204, 51, 0.3);
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--blue-primary);
            position: relative;
            z-index: 2;
        }

        .hero h1 span {
            color: var(--gold-accent);
            display: inline-block;
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.3rem);
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 600px;
            line-height: 1.8;
            position: relative;
            z-index: 2;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: var(--blue-primary);
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.8;
            z-index: 2;
        }

        .scroll-indicator .arrow {
            width: 20px;
            height: 20px;
            border-right: 2px solid var(--blue-primary);
            border-bottom: 2px solid var(--blue-primary);
            transform: rotate(45deg);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: rotate(45deg) translateY(0); }
            40% { transform: rotate(45deg) translateY(-10px); }
            60% { transform: rotate(45deg) translateY(-5px); }
        }

        /* Stats Section */
        .stats {
            padding: 60px 50px;
            display: flex;
            justify-content: center;
            gap: 80px;
            background: #ffffff;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            position: relative;
            z-index: 10;
        }

        .stat-item { text-align: center; }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--blue-primary), #1e3a6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }

        /* Jurusan Section */
        .jurusan-section {
            padding: 100px 50px;
            position: relative;
            background: var(--bg-light);
        }

        .section-header {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-header h2 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 15px;
            color: var(--text-main);
        }

        .section-header p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .jurusan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .jurusan-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .jurusan-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(47, 85, 151, 0.05) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .jurusan-card:hover {
            transform: translateY(-8px);
            border-color: rgba(47, 85, 151, 0.3);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(47, 85, 151, 0.05);
        }

        .jurusan-card:hover::before { opacity: 1; }

        .card-number {
            position: absolute;
            top: 30px;
            right: 30px;
            font-size: 4rem;
            font-weight: 900;
            color: rgba(47, 85, 151, 0.04);
            line-height: 1;
            user-select: none;
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px rgba(47, 85, 151, 0.2);
        }

        .jurusan-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
            background: #1e293b;
        }

        .card-icon i, .card-icon img {
            font-size: 2.5rem;
            color: #ffffff;
            position: relative;
            z-index: 1;
            transition: color 0.4s ease;
        }
        
        .jurusan-card:hover .card-icon i, 
        .jurusan-card:hover .card-icon img {
            color: var(--gold-accent);
            filter: brightness(1.2);
        }

        .card-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(47, 85, 151, 0.1);
            border: 1px solid rgba(47, 85, 151, 0.2);
            border-radius: 50px;
            color: var(--blue-primary);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            color: var(--text-main);
        }

        .card-subtitle {
            font-size: 0.95rem;
            color: var(--gold-accent);
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }

        .card-desc {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        /* =========================================
           NEW SECTION: SFORMS SHOWCASE (TECH STYLE)
           ========================================= */
        .sforms-section {
            padding: 120px 50px;
            background: var(--tech-bg);
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        /* Background Tech Pattern */
        .sforms-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.1;
        }

        .sforms-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1400px;
            margin: 0 auto;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .sforms-content h2 {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.1;
        }

        .sforms-content h2 span {
            color: var(--gold-accent);
        }

        .sforms-content p {
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 40px;
            max-width: 550px;
        }

        .sforms-features {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 204, 51, 0.1);
            border: 1px solid var(--gold-accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--gold-accent);
            font-size: 1.2rem;
        }

        .feature-text h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #fff;
        }

        .feature-text p {
            font-size: 0.9rem;
            margin-bottom: 0;
            color: #64748b;
        }

        /* Sforms Visual Representation (CSS Mockups) */
        .sforms-visual {
            position: relative;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Laptop Mockup CSS */
        .laptop-mockup {
            width: 100%;
            max-width: 450px;
            background: #1e293b;
            border-radius: 16px;
            border: 2px solid #334155;
            padding: 15px 15px 30px 15px;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .screen {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 16/10;
        }

        .screen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }

        /* Screen Overlay Content */
        .screen-ui {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        .screen-ui i {
            font-size: 3rem;
            color: var(--gold-accent);
            margin-bottom: 10px;
        }

        .screen-ui h5 {
            color: #fff;
            font-size: 1.2rem;
        }
        
        .screen-ui span {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Mobile Mockup CSS */
        .mobile-mockup {
            width: 140px;
            background: #1e293b;
            border-radius: 24px;
            border: 2px solid #334155;
            padding: 10px;
            position: absolute;
            bottom: -20px;
            right: 20px;
            z-index: 3;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }

        .mobile-screen {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 9/19;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-screen i {
            font-size: 2rem;
            color: var(--blue-primary);
        }

        /* CTA Section */
        .cta-section {
            padding: 120px 50px;
            text-align: center;
            background: var(--blue-primary);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-section h2 {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            color: #ffffff;
        }

        .cta-section p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 1;
        }

        .cta-btn {
            padding: 18px 50px;
            background: var(--gold-accent);
            color: #1e293b;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 204, 51, 0.3);
            position: relative;
            z-index: 1;
            text-decoration: none;
            display: inline-block;
        }

        .cta-btn:hover {
            background: #ffffff;
            color: var(--blue-primary);
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.3);
        }

        /* Contact Section */
        .contact {
            background: #0f172a;
            color: white;
            padding: 80px 50px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .contact-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            gap: 40px;
            max-width: 1200px;
            margin: auto;
        }

        @media (min-width: 768px) {
            .contact-container {
                flex-direction: row;
                gap: 20px;
            }
        }

        .contact-left, .contact-center, .contact-right {
            flex: 1;
            width: 100%;
            text-align: left;
        }
        
        @media (max-width: 767px) {
            .contact-left, .contact-center, .contact-right {
                text-align: center;
            }
        }

        .contact-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }
        
        @media (min-width: 768px) {
            .contact-logo {
                justify-content: flex-start;
            }
        }

        .contact-logo img {
            width: 150px;
            height: auto;
            border-radius: 5px;
            
        }

        .contact h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: white;
        }

        .contact p {
            font-size: 0.9rem;
            color: #cbd5f5;
        }

        .contact-right p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 767px) {
            .contact-right p {
                justify-content: center;
            }
        }

        .social-icons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            font-size: 18px;
            text-decoration: none;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: #ffcc33;
            color: #1e293b;
            transform: translateY(-5px);
        }

        /* Footer */
        footer {
            padding: 30px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e293b;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 768px) {
            footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .jurusan-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 25px;
            }
            
            .sforms-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .sforms-visual {
                height: 400px;
            }

            .feature-item {
                align-items: center;
                justify-content: center;
                flex-direction: column;
                text-align: center;
            }
            
            .sforms-content p {
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (max-width: 768px) {
            nav { padding: 15px 25px; }
            .nav-login-btn { padding: 10px 20px; font-size: 0.5rem; }
            .stats { flex-direction: column; gap: 40px; padding: 40px 25px; }
            .stat-number { font-size: 2.5rem; }
            .jurusan-section { padding: 60px 25px; }
            .jurusan-grid { grid-template-columns: 1fr; gap: 20px; }
            .jurusan-card { padding: 35px 25px; }
            .card-number { font-size: 3rem; }
            .card-icon { width: 70px; height: 70px; }
            .card-icon i { font-size: 2rem; }
            .card-title { font-size: 1.75rem; }
            .cta-section { padding: 80px 25px; }
            .sforms-section { padding: 80px 25px; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="#" class="nav-logo">
            <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" alt="Logo SMKN 1 Ciomas">
            <span>SMKN 1 CIOMAS</span>
        </a>
        <a href="{{route('login')}}" class="nav-login-btn">LOGIN SFORMS</a>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge" data-aos="fade-down" data-aos-duration="800">Pusat Keunggulan</div>
        <h1 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            SMKN 1 <span>Ciomas</span>
        </h1>
        <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
            Membentuk generasi unggul yang berkarakter, berkompeten, dan inovatif di era digital dengan dukungan teknologi terintegrasi.
        </p>
        <div class="scroll-indicator" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
            <span>Scroll</span>
            <div class="arrow"></div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stat-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            <div class="stat-number" data-count="1200">0</div>
            <div class="stat-label">Siswa Aktif</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
            <div class="stat-number" data-count="5">0</div>
            <div class="stat-label">Jurusan Unggulan</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
            <div class="stat-number" data-count="95">0</div>
            <div class="stat-label">Tingkat Kelulusan</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
            <div class="stat-number" data-count="50">0</div>
            <div class="stat-label">Mitra Industri</div>
        </div>
    </section>

    <!-- Jurusan Section -->
    <section class="jurusan-section">
        <div class="section-header">
            <h2 data-aos="fade-up" data-aos-duration="800">Jurusan Unggulan</h2>
            <p data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                Pilih jurusan sesuai minat dan bakatmu untuk masa depan yang cerah
            </p>
        </div>

        <div class="jurusan-grid">
            <!-- PPLG -->
            <div class="jurusan-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <div class="card-number">01</div>
                <div class="card-icon">
                    <img src="pplg.png" height="70px" alt="PPLG">
                </div>
                <div class="card-badge">Software Engineering</div>
                <div class="card-title">PPLG</div>
                <div class="card-subtitle">Pemrograman Perangkat Lunak & Gim</div>
                <div class="card-desc">
                    Fokus pada Coding, Web Design, Mobile Apps, dan Game Development dengan standar industri global.
                </div>
            </div>

            <!-- BCF -->
            <div class="jurusan-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="card-number">02</div>
                <div class="card-icon">
                    <img src="bcf.png" height="70px" alt="BCF">
                </div>
                <div class="card-badge">Broadcasting & Film</div>
                <div class="card-title">BCF</div>
                <div class="card-subtitle">Broadcasting Dan Perfilman</div>
                <div class="card-desc">
                    Kemampuan untuk memproduksi konten kreatif serta industri hiburan dengan teknologi modern.
                </div>
            </div>

            <!-- TO -->
            <div class="jurusan-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <div class="card-number">03</div>
                <div class="card-icon">
                    <img src="tkr.png" height="70px" alt="TKR">
                </div>
                <div class="card-badge">Automotive</div>
                <div class="card-title">TO</div>
                <div class="card-subtitle">Teknik Otomotif</div>
                <div class="card-desc">
                    Ahli dalam bidang kendaraan dan mesin dengan teknologi terkini dan standar industri.
                </div>
            </div>

            <!-- ANIMASI -->
            <div class="jurusan-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                <div class="card-number">04</div>
                <div class="card-icon">
                    <img src="animasi.png" height="70px" alt="Animasi">
                </div>
                <div class="card-badge">Skank Notion</div>
                <div class="card-title">ANIMASI</div>
                <div class="card-subtitle">Animasi 2D & 3D</div>
                <div class="card-desc">
                    Menghidupkan imajinasi melalui teknik animasi 2D & 3D, storyboarding, dan karakter modeling.
                </div>
            </div>

            <!-- TPFL -->
            <div class="jurusan-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="500">
                <div class="card-number">05</div>
                <div class="card-icon">
                    <img src="tpfl.png" height="70px" alt="TPFL">
                </div>
                <div class="card-badge">Manufacturing</div>
                <div class="card-title">TPFL</div>
                <div class="card-subtitle">Teknik Pengelasan dan Fabrikasi Logam</div>
                <div class="card-desc">
                    Ahli dalam pengelasan, fabrikasi logam, dan manufaktur presisi dengan standar industri.
                </div>
            </div>
        </div>
    </section>

    <!-- NEW SECTION: SFORMS SHOWCASE -->
    <section class="sforms-section">
        <div class="sforms-container">
            <div class="sforms-content">
                <div class="hero-badge" data-aos="fade-down">Official School App</div>
                <h2 data-aos="fade-up" data-aos-duration="1000">
                    Perkenalkan <span>SFORMS</span>
                </h2>
                <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Skanic Forms adalah Aplikasi Ujian Anti Contek buatan anak bangsa. Tersedia di Web dan Mobile dengan sistem terstruktur, kompleks, dan efisien untuk mendukung ujian formal.
                </p>

                <div class="sforms-features" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Anti-Cheating System</h4>
                            <p>Keamanan tingkat tinggi untuk memastikan integritas ujian siswa.</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Data Cepat & Efisien</h4>
                            <p>Pemrosesan hasil ujian real-time yang terstruktur dan akurat.</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-screen-button"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Multi Platform</h4>
                            <p>Akses mudah melalui Web Browser maupun Aplikasi Mobile.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sforms-visual" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                <!-- CSS Mockup Laptop -->
                <div class="laptop-mockup gsap-laptop">
                    <div class="screen">
                        <div class="screen-ui">
                            <i class="fas fa-file-signature"></i>
                            <h5>SFORMS WEB</h5>
                            <span>Ujian Berbasis Komputer</span>
                        </div>
                    </div>
                </div>
                
                <!-- CSS Mockup Mobile -->
                <div class="mobile-mockup gsap-mobile">
                    <div class="mobile-screen">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2 data-aos="fade-up" data-aos-duration="800">Bergabung Bersama Kami</h2>
        <p data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
            SMKN 1 Ciomas — Berkarakter, Berkompeten, Berdaya Saing
        </p>
        <a href="{{route('login')}}" class="cta-btn" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
            Let's Do
        </a>
    </section>

    <!-- Contact Section -->
    <section class="contact">
        <div class="contact-container">
            <div class="contact-left">
                <div class="contact-logo">
                    <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" alt="Logo SMKN 1 Ciomas">
                    <div>
                        <h3>SMK Negeri 1 Ciomas</h3>
                        <p>Jalan Ciomas, Jawa Barat</p>
                    </div>
                </div>
            </div>

            <div class="contact-center">
                <!-- Spacer -->
            </div>

            <div class="contact-right">
                <h4>Kontak Kami</h4>
                <p><i class="fas fa-location-dot"></i> Jawa Barat</p>
                <p><i class="fas fa-phone"></i> 08xxxxxxxx</p>
                <p><i class="fas fa-envelope"></i> info@skanic.sch.id</p>
            </div>
        </div>
        <div class="social-icons">
            <a href="#"><i class="fas fa-phone"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fas fa-envelope"></i></a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <span>© 2025 SMKN 1 Ciomas</span>
        <span>SmartSchool SKANIC</span>
    </footer>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });

        // Register GSAP ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        document.addEventListener('DOMContentLoaded', () => {
            // Navbar scroll effect
            window.addEventListener('scroll', () => {
                const nav = document.querySelector('nav');
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });

            // Stats counter animation
            const statNumbers = document.querySelectorAll('.stat-number');
            
            statNumbers.forEach(number => {
                const targetValue = parseInt(number.getAttribute('data-count'));
                let currentValue = { value: 0 };
                
                ScrollTrigger.create({
                    trigger: '.stats',
                    start: 'top 85%',
                    onEnter: () => {
                        gsap.to(currentValue, {
                            value: targetValue,
                            duration: 2,
                            ease: 'power2.out',
                            onUpdate: function() {
                                number.innerHTML = Math.round(currentValue.value);
                            }
                        });
                    },
                    once: true
                });
            });

            // Cards hover animation with GSAP
            document.querySelectorAll('.jurusan-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card.querySelector('.card-icon'), {
                        scale: 1.15,
                        rotate: 5,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });

                card.addEventListener('mouseleave', () => {
                    gsap.to(card.querySelector('.card-icon'), {
                        scale: 1,
                        rotate: 0,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });
            });

            // Sforms Mockup Floating Animation (Continuous subtle movement)
            gsap.to(".gsap-laptop", {
                y: "-15px",
                duration: 2.5,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });

            gsap.to(".gsap-mobile", {
                y: "15px",
                duration: 3,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });

            // Contact animations
            gsap.from(".contact-left", {
                scrollTrigger: { trigger: ".contact", start: "top 80%" },
                x: -80, opacity: 0, duration: 1
            });

            gsap.from(".contact-center", {
                scrollTrigger: { trigger: ".contact", start: "top 80%" },
                y: 80, opacity: 0, duration: 1, delay: 0.2
            });

            gsap.from(".contact-right", {
                scrollTrigger: { trigger: ".contact", start: "top 80%" },
                x: 80, opacity: 0, duration: 1, delay: 0.4
            });

            gsap.from(".social-icons", {
                scrollTrigger: { trigger: ".contact", start: "top 80%" },
                y: 40, opacity: 0, duration: 1, delay: 0.6
            });

            // Smooth scroll for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });
    </script>
</body>
</html>
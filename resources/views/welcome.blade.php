<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SmartSchool | Immersive 3D Exam Simulation</title>
  <!-- Bulma CSS for clean base styling -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* ----- RESET & GLOBAL ----- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Oxygen', sans-serif;
      overflow-x: hidden;
      background-color: #05070a;
    }

    /* ----- 3D SPLINE BACKGROUND (Hero + Fixed immersive) ----- */
    .spline-hero {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none; /* Agar tombol di atas tetap bisa diklik */
    }

    .spline-hero iframe {
      width: 100%;
      height: 100%;
      border: none;
      display: block;
      pointer-events: none;
    }

    /* Overlay subtle gradasi untuk memastikan teks terbaca di atas 3D */
    .gradient-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 20% 30%, rgba(0,0,0,0.4) 0%, rgba(5,10,25,0.65) 100%);
      pointer-events: none;
      z-index: 1;
    }

    /* MAIN CONTENT LAYER di atas 3D */
    .content-layer {
      position: relative;
      z-index: 15;
      min-height: 200vh;
    }

    /* ----- NAVBAR Futuristik ----- */
    .navbar-futuristic {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 30;
      background: rgba(5, 15, 25, 0.55);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.18);
      padding: 0.5rem 2rem;
      transition: all 0.3s;
    }

    .navbar-futuristic .navbar-brand a,
    .navbar-futuristic .navbar-item {
      color: white !important;
      font-weight: 500;
      letter-spacing: 0.3px;
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: 700;
      background: linear-gradient(125deg, #FFFFFF, #7aa9ff);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent !important;
    }

    .login-glow {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 60px;
      backdrop-filter: blur(4px);
      transition: all 0.2s;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .login-glow:hover {
      background: rgba(59, 130, 246, 0.7);
      transform: scale(1.02);
    }

    /* ----- HERO SECTION: terintegrasi dengan 3D (full viewport) ----- */
    .hero-section-3d {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding: 2rem 4rem;
      position: relative;
      background: transparent;
    }

    .hero-card-glass {
      max-width: 620px;
      background: rgba(8, 18, 30, 0.55);
      backdrop-filter: blur(14px);
      border-radius: 2.5rem;
      padding: 2.8rem;
      border: 1px solid rgba(255, 255, 255, 0.25);
      box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255,255,255,0.05);
      transition: transform 0.35s ease, box-shadow 0.3s;
    }

    .hero-card-glass:hover {
      transform: translateY(-5px);
      box-shadow: 0 35px 55px rgba(0, 0, 0, 0.5);
    }

    .badge-3d {
      display: inline-block;
      background: rgba(59, 130, 246, 0.3);
      backdrop-filter: blur(8px);
      padding: 0.25rem 1rem;
      border-radius: 40px;
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 1px;
      color: #c7e2ff;
      border: 0.5px solid rgba(255,255,255,0.2);
      margin-bottom: 1rem;
    }

    .hero-title {
      font-size: 4rem;
      font-weight: 800;
      background: linear-gradient(135deg, #FFFFFF, #80b3ff, #c2a0ff);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      line-height: 1.2;
      margin-bottom: 0.5rem;
    }

    .hero-sub {
      font-size: 1.4rem;
      color: #EFF3FF;
      font-weight: 500;
      margin-bottom: 1.5rem;
    }

    .btn-3d-primary {
      background: linear-gradient(95deg, #2563eb, #1e40af);
      border: none;
      font-weight: 700;
      padding: 0.9rem 2.2rem;
      font-size: 1.1rem;
      border-radius: 3rem;
      box-shadow: 0 12px 28px rgba(37, 99, 235, 0.4);
      transition: all 0.25s ease;
      color: white;
    }

    .btn-3d-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 35px rgba(37, 99, 235, 0.6);
      background: linear-gradient(95deg, #3b82f6, #2563eb);
    }

    /* ----- SCROLL-REVEAL DYNAMIC SECTION (muncul setelah 100vh) ----- */
    .dynamic-panel {
      min-height: 100vh;
      background: rgba(6, 12, 24, 0.7);
      backdrop-filter: blur(20px);
      padding: 5rem 2rem;
      border-radius: 3rem 3rem 0 0;
      margin-top: 0;
      transition: opacity 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1), transform 0.6s ease;
      opacity: 0;
      transform: translateY(60px);
      pointer-events: none;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .dynamic-panel.reveal {
      opacity: 1;
      transform: translateY(0);
      pointer-events: auto;
    }

    .section-title-glow {
      font-size: 2.8rem;
      font-weight: 700;
      text-align: center;
      background: linear-gradient(120deg, #FFFFFF, #9bbdff);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 1rem;
    }

    .section-desc-modern {
      text-align: center;
      color: #cbdffa;
      max-width: 720px;
      margin: 0 auto 3rem auto;
      font-size: 1.2rem;
      font-weight: 400;
    }

    /* Feature Cards Futuristik */
    .feature-grid-3d {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
    }

    .feature-card-3d {
      background: rgba(15, 25, 45, 0.55);
      backdrop-filter: blur(12px);
      border-radius: 2rem;
      padding: 2rem 1.5rem;
      width: 270px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s;
      box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
    }

    .feature-card-3d:hover {
      transform: translateY(-8px);
      background: rgba(25, 45, 70, 0.7);
      border-color: rgba(59, 130, 246, 0.6);
    }

    .feature-card-3d i {
      font-size: 2.8rem;
      background: linear-gradient(135deg, #90caf9, #3b82f6);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 1rem;
    }

    .feature-card-3d h3 {
      color: white;
      font-size: 1.5rem;
      font-weight: 600;
      margin: 0.8rem 0;
    }

    .feature-card-3d p {
      color: #cbd5f0;
      font-size: 0.95rem;
    }

    /* Callout CTA */
    .cta-glow-panel {
      background: linear-gradient(115deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1));
      border-radius: 2.5rem;
      padding: 2.5rem;
      text-align: center;
      margin-top: 4rem;
      border: 1px solid rgba(59, 130, 246, 0.5);
      backdrop-filter: blur(8px);
    }

    .cta-glow-panel h4 {
      font-size: 2rem;
      color: white;
      font-weight: 700;
    }

    .btn-outline-light {
      background: transparent;
      border: 1.5px solid white;
      border-radius: 60px;
      padding: 0.8rem 2rem;
      font-weight: 600;
      color: white;
      transition: all 0.2s;
    }

    .btn-outline-light:hover {
      background: white;
      color: #0a2b4e;
      transform: scale(1.02);
    }

    .footer-modern {
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(12px);
      padding: 2rem;
      text-align: center;
      color: #a2bbdd;
      font-size: 0.85rem;
      margin-top: 4rem;
      border-radius: 1.5rem 1.5rem 0 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero-section-3d {
        padding: 1rem;
        align-items: center;
        justify-content: center;
        text-align: center;
      }
      .hero-title {
        font-size: 2.6rem;
      }
      .hero-card-glass {
        padding: 1.8rem;
        max-width: 90%;
      }
      .hero-sub {
        font-size: 1.1rem;
      }
      .navbar-futuristic {
        padding: 0.5rem 1rem;
      }
      .section-title-glow {
        font-size: 2rem;
      }
      .feature-grid-3d {
        gap: 1.2rem;
      }
    }
  </style>
</head>
<body>

  <!-- FIXED 3D SPLINE BACKGROUND: Hero Visual utama -->
  <div class="spline-hero">
    <iframe 
      src='https://my.spline.design/googlyeyes-ONxS94pMvCQO81yOL95ooRCy-eVe/' 
      frameborder='0' 
      title="SmartSchool 3D Immersive Scene"
      loading="eager"
      aria-hidden="true">
    </iframe>
  </div>
  <div class="gradient-overlay"></div> <!-- bantu kontras teks -->

  <!-- MAIN LAYER -->
  <div class="content-layer">
    <!-- Navigation Bar Futuristik -->
    <nav class="navbar navbar-futuristic" role="navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="#" style="gap: 8px;">
          <i class="fas fa-cube" style="color:#3b82f6;"></i>
          <span class="logo-text">SmartSchool<span style="font-weight:300;">|3D</span></span>
        </a>
        <a class="navbar-item login-glow" href="#" id="loginMockBtn" style="border-radius: 40px; margin-left: auto;">
          <i class="fas fa-fingerprint"></i> &nbsp; Akses Area
        </a>
      </div>
    </nav>

    <!-- HERO SECTION yang selaras dengan model 3D (100vh) -->
    <section class="hero-section-3d" id="heroSection3d">
      <div class="hero-card-glass">
        <div class="badge-3d">
          <i class="fas fa-vr-cardboard"></i> Immersive 3D Experience
        </div>
        <h1 class="hero-title">Exam <br>Reimagined.</h1>
        <p class="hero-sub">Simulasi Ujian Berbasis AI & Visual Interaktif</p>
        <button class="button btn-3d-primary" id="joinNowBtn">
          <i class="fas fa-rocket"></i> &nbsp; Mulai Petualangan
        </button>
        <div style="margin-top: 2rem;">
          <span style="color:#c0d4ff; font-size:0.8rem; letter-spacing: 1px;">
            <i class="fas fa-mouse"></i> GULIR KE BAWAH — 3D Tetap Hidup
          </span>
        </div>
      </div>
    </section>

    <!-- DYNAMIC SECTION: Muncul setelah scroll > 100vh, desain futuristik selaras dengan hero -->
    <div class="dynamic-panel" id="dynamicReveal">
      <div class="container">
        <div class="section-title-glow">
          <i class="fas fa-microchip"></i> Smart Ecosystem
        </div>
        <div class="section-desc-modern">
          Teknologi simulasi ujian adaptif + analitik prediktif. Terintegrasi dengan 3D environment untuk fokus maksimal.
        </div>

        <div class="feature-grid-3d">
          <div class="feature-card-3d">
            <i class="fas fa-brain"></i>
            <h3>Adaptive AI</h3>
            <p>Soal menyesuaikan levelmu, fokus pada area pengembangan.</p>
          </div>
          <div class="feature-card-3d">
            <i class="fas fa-chart-line"></i>
            <h3>Live Analytics</h3>
            <p>Statistik real-time, heatmap kelemahan, rekomendasi personal.</p>
          </div>
          <div class="feature-card-3d">
            <i class="fas fa-hourglass-half"></i>
            <h3>Exam Simulator</h3>
            <p>Timer, suasana ujian asli, & pembahasan mendetail.</p>
          </div>
          <div class="feature-card-3d">
            <i class="fas fa-trophy"></i>
            <h3>Leaderboard 3D</h3>
            <p>Ranking nasional & unlock achievement badge.</p>
          </div>
        </div>

        <div class="cta-glow-panel">
          <h4><i class="fas fa-gem"></i> Gabung 20.000+ Pejuang Ujian</h4>
          <p style="color:#eef4ff; margin: 1rem auto; max-width: 500px;">Akses bank soal 10.000+, tryout premium, dan kelas intensif dari mentor ternama.</p>
          <button class="button btn-outline-light" id="secondJoinBtn">
            <i class="fas fa-user-astronaut"></i> Daftar Sekarang
          </button>
        </div>
        
        <!-- subtle decorative -->
        <div style="text-align: center; margin-top: 3rem; color: #7f9fcf;">
          <i class="fas fa-arrows-alt"></i> Scroll terus — 3D background tetap memukau
        </div>
      </div>
      <footer class="footer-modern">
        <p>© 2025 SmartSchool — Immersive 3D Examination Platform</p>
        <p style="margin-top: 0.3rem;"><i class="fas fa-cube"></i> Powered by Spline + AI Adaptive Engine</p>
      </footer>
    </div>
  </div>

  <script>
    (function() {
      // ELEMENTS
      const dynamicPanel = document.getElementById('dynamicReveal');
      const heroSection = document.getElementById('heroSection3d');

      // Scroll logic: tampilkan dynamic panel hanya jika scrollY >= innerHeight (lewat 100vh)
      // agar pengalaman seamless: ketika scroll melewati hero penuh, konten baru muncul dan 3D tetap sebagai background
      function updateRevealOnScroll() {
        const vh = window.innerHeight;
        const scrollPos = window.scrollY;
        // Jika scroll sudah melebihi 95% dari viewport height (biar smooth)
        const shouldReveal = scrollPos >= (vh - 15);
        
        if (shouldReveal) {
          if (!dynamicPanel.classList.contains('reveal')) {
            dynamicPanel.classList.add('reveal');
            // efek tambahan: sedikit vibes
            console.log('✨ Dynamic content muncul — integrasi 3D sempurna');
          }
        } else {
          // jika scroll kembali ke atas (di bawah threshold), panel tersembunyi agar pengalaman "hero penuh" lagi
          if (dynamicPanel.classList.contains('reveal')) {
            dynamicPanel.classList.remove('reveal');
            console.log('🌊 Kembali ke area hero — dynamic panel tersembunyi');
          }
        }
      }

      // Performance handler with requestAnimationFrame
      let ticking = false;
      function scrollHandler() {
        if (!ticking) {
          requestAnimationFrame(() => {
            updateRevealOnScroll();
            ticking = false;
          });
          ticking = true;
        }
      }

      window.addEventListener('scroll', scrollHandler);
      window.addEventListener('resize', () => {
        updateRevealOnScroll();
      });
      
      // Initial call: pastikan state awal (biasanya panel tersembunyi jika page load di top)
      setTimeout(() => {
        updateRevealOnScroll();
      }, 100);
      
      // Pastikan ketika load, jika ada anchor scroll (tapi umumnya tidak)
      window.addEventListener('load', () => {
        updateRevealOnScroll();
        // sedikit tweak agar background 3D tidak mengganggu klik
      });

      // ----- INTERAKSI TOMBOL (semua tombol responsif dengan alert/demo) -----
      const joinMain = document.getElementById('joinNowBtn');
      if (joinMain) {
        joinMain.addEventListener('click', (e) => {
          e.preventDefault();
          alert("🚀 Selamat datang di SmartSchool! Simulasi ujian dengan 3D environment siap mengasah kemampuanmu.");
          // Opsional: smooth scroll ke dynamic section jika belum terlihat? tapi user bisa scroll manual, biarkan natural.
        });
      }

      const secondJoin = document.getElementById('secondJoinBtn');
      if (secondJoin) {
        secondJoin.addEventListener('click', () => {
          alert("📋 Form pendaftaran akses tryout & materi eksklusif. Raih prestasi tertinggi bersama SmartSchool 3D.");
        });
      }

      const loginBtn = document.getElementById('loginMockBtn');
      if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
          e.preventDefault();
          alert("🔐 Portal Login: Masuk ke dashboard simulasi ujian & lihat progress belajarmu dalam tampilan 3D.");
        });
      }

      // Minor: pastikan bahwa elemen dynamic panel tidak menghalangi interaksi di hero (pointer-events auto saat reveal)
      // tapi karena berada di bawah hero saat tersembunyi, tidak masalah.
      // tambahan efek hover pada card - elegan
      
      // Fitur tambahan: menyelaraskan vibe 3D dengan perubahan kecil pada scroll untuk menonjolkan glassmorphism
      // agar transisi makin mulus, kita juga bisa atur sedikit parallax? tapi biarkan sederhana.
      
      // Karena iframe 3D memiliki pointer-events: none, seluruh tombol & navigasi bisa diakses dengan mudah.
      // Memastikan background gradient overlay juga tidak mengganggu.
      
      // Optional: menambah CSS variable untuk smooth scroll behavior
      document.documentElement.style.scrollBehavior = "smooth";
      
      // Animasi tambahan pada navbar saat scroll (opsional: efek shrink)
      const navbar = document.querySelector('.navbar-futuristic');
      let lastScroll = 0;
      window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;
        if (currentScroll > 60) {
          navbar.style.background = "rgba(2, 8, 18, 0.8)";
          navbar.style.backdropFilter = "blur(20px)";
        } else {
          navbar.style.background = "rgba(5, 15, 25, 0.55)";
          navbar.style.backdropFilter = "blur(16px)";
        }
        lastScroll = currentScroll;
      });
      
      // memberikan sentuhan dinamis pada hero card: efek mengikuti gerakan mouse? opsional ringan
      const heroCard = document.querySelector('.hero-card-glass');
      if (heroCard) {
        document.addEventListener('mousemove', (e) => {
          const mouseX = e.clientX / window.innerWidth;
          const mouseY = e.clientY / window.innerHeight;
          const moveX = (mouseX - 0.5) * 8;
          const moveY = (mouseY - 0.5) * 8;
          heroCard.style.transform = `translate(${moveX * 0.5}px, ${moveY * 0.5}px)`;
        });
        // reset on mouse leave hero area? tidak perlu berlebihan
      }
      
      // Menambahkan teks info konsol bahwa integrasi spline berjalan seamless
      console.log("SmartSchool 3D — Desain menyatu dengan model Spline. Scroll untuk konten dinamis.");
    })();
  </script>
  <!-- tambahan interaksi untuk smoothness jika user menggunakan trackpad -->
  <style>
    /* tambahan gaya agar scroll terasa modern */
    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #0a0f1a;
    }
    ::-webkit-scrollbar-thumb {
      background: #3b82f6;
      border-radius: 10px;
    }
    .dynamic-panel {
      transition: opacity 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.2), transform 0.7s cubic-bezier(0.2, 0.8, 0.3, 1);
    }
    .btn-3d-primary, .btn-outline-light {
      cursor: pointer;
    }
    .navbar-item, .button {
      cursor: pointer;
    }
    body {
      overflow-x: hidden;
    }
  </style>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartSchool</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      color: white;
      overflow-x: hidden;
    }

    /* ===== SPLINE BACKGROUND ===== */
    .spline-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .spline-bg iframe {
      width: 100%;
      height: 100%;
      border: none;
      pointer-events: auto; /* BIAR BISA INTERACT */
    }

    /* Overlay (biar teks kebaca, tapi gak ganggu klik) */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1;
      pointer-events: none; /* PENTING */
    }

    /* ===== NAVBAR ===== */
    header {
      position: relative;
      z-index: 2;
      display: flex;
      justify-content: space-between;
      padding: 20px 50px;
    }

    nav a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
    }

    /* ===== HERO ===== */
    .hero {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
      z-index: 2;
      pointer-events: none; /* BIAR BACKGROUND MASIH BISA DIINTERACT */
    }

    .hero-content {
      background: rgba(0,0,0,0.6);
      padding: 30px;
      border-radius: 15px;
      pointer-events: auto; /* tombol tetap bisa diklik */
    }

    .hero h1 {
      font-size: 45px;
    }

    .hero p {
      margin: 15px 0;
    }

    .hero button {
      padding: 10px 20px;
      border: none;
      background: #3498db;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }

    /* ===== SECTION ===== */
    .section {
      padding: 60px 20px;
      text-align: center;
      position: relative;
      z-index: 2;
      background: rgba(0,0,0,0.7);
    }

    /* ===== FOOTER ===== */
    footer {
      text-align: center;
      padding: 20px;
      background: rgba(0,0,0,0.9);
      position: relative;
      z-index: 2;
    }

  </style>
</head>
<body>

  <!-- SPLINE BACKGROUND -->
  <div class="spline-bg">
    <iframe src="https://my.spline.design/draganddropbookpencilschoolcopy-2oyBmqYoZQJF4pK46vZCTquJ/"></iframe>
  </div>

 

  <!-- NAVBAR -->
  <header>
    <h2>SmartSchool</h2>
    <nav>
      <a href="#">Home</a>
      <a href="#">Tentang</a>
      <a href="#">Kontak</a>
    </nav>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Selamat Datang</h1>
      <p>Sekolah Digital Interaktif Masa Depan</p>
      <button onclick="alert('Menuju halaman selanjutnya')">Mulai</button>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section">
    <h2>Tentang Kami</h2>
    <p>
      SmartSchool adalah platform sekolah modern yang menggabungkan teknologi
      interaktif untuk meningkatkan pengalaman belajar siswa.
    </p>
  </section>

  <!-- FOOTER -->
  <footer>
    <p>© 2026 SmartSchool</p>
  </footer>

</body>
</html>
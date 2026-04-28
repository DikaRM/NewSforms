<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SmartSchool Login</title>

<!-- GSAP -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", sans-serif;
  
}

body {
  height: 100vh;
  display: flex;
  overflow: hidden;
  background: linear-gradient(135deg, #1a3a6b 0%, #2f5597 50%, #1a3a6b 100%)
}

/* ==================== LEFT SIDE ==================== */
.left {
  width: 55%;
  background: linear-gradient(135deg, #1a3a6b 0%, #2f5597 50%, #1a3a6b 100%);
  color: white;
  padding: 40px;
  position: relative;
  text-align: center;
  overflow: visible;
  z-index: 9999;
}

.logo {
  display: flex;
  align-items: center;
  margin-bottom: 40px;
  position: relative;
  z-index: 10;
}
.logo img {
  width: 85px;
  height: 70px;
  margin-right: 15px;
  filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
}
.logo span {
  font-weight: 600;
  font-size: 30px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.left h1 {
  font-size: 36px;
  line-height: 1.5;
  margin: 80px auto;
  position: relative;
  z-index: 10;
  text-shadow: 0 4px 20px rgba(0,0,0,0.4);
}
.left h1 span {
  color: #ffcc33;
  position: relative;
}

/* Animated underline on span */
.left h1 span::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 3px;
  background: #ffcc33;
  border-radius: 2px;
}

/* ---- Decorative Shapes ---- */
.green {
  position: absolute;
  top: 62%;
  left: 9%;
  width: 180px;
  height: 250px;
  background: #4CBC9A;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
}

.green-kecil {
  position: absolute;
  top: 70%;
  left: 40%;
  width: 60px;
  height: 105px;
  background: #4CBC9A;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
}

.blue {
  position: absolute;
  top: 150px;
  left: -90px;
  width: 120px;
  height: 160px;
  background: #0a446a;
  border-radius: 5px;
  transform: rotate(100deg) skewY(-20deg);
}

.yellow {
  position: absolute;
  top: 20%;
  left: 85%;
  width: 160px;
  height: 250px;
  background: #ffcc33;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
}

.yellow-kecil {
  position: absolute;
  top: 58%;
  left: 85%;
  width: 60px;
  height: 105px;
  background: #ffcc33;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
}

.wisuda {
  position: absolute;
  top: 19%;
  left: 84%;
  width: 160px;
  height: 250px;
  background: #ffcc33;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
  z-index: 5;
  object-fit: cover;
}

.laptop {
  position: absolute;
  top: 64%;
  left: 9%;
  width: 160px;
  height: 250px;
  background: #4CBC9A;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
  z-index: 99999;
  object-fit: cover;
}

.shape {
  position: absolute;
  bottom: 50px;
  left: 40px;
  display: flex;
  gap: 20px;
  z-index: 10;
}

/* Floating particles */
.particle {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
  opacity: 0;
}

/* ==================== RIGHT SIDE ==================== */
.right {
  width: 45%;
  background: #f3f5f9;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50px 0 0 50px;
  position: relative;
  overflow: hidden;
}

/* Subtle animated bg pattern di right */
.right::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(47,85,151,0.06) 0%, transparent 70%);
  animation: bgFloat 8s ease-in-out infinite alternate;
}
.right::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -30%;
  width: 80%;
  height: 80%;
  background: radial-gradient(circle, rgba(255,204,51,0.05) 0%, transparent 70%);
  animation: bgFloat 10s ease-in-out infinite alternate-reverse;
}
@keyframes bgFloat {
  0% { transform: translate(0, 0) scale(1); }
  100% { transform: translate(30px, -20px) scale(1.1); }
}

.login-box {
  width: 80%;
  padding: 10px;
  position: relative;
  z-index: 1;
}

.login-box h2 {
  text-align: center;
  margin-bottom: 8px;
  color: #2f5597;
  font-size: 30px;
  font-weight: 700;
}

.login-subtitle {
  text-align: center;
  color: #8896ab;
  font-size: 0.85rem;
  margin-bottom: 30px;
}

.input-group {
  margin-bottom: 20px;
  position: relative;
}

.input-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #3a4a5c;
  font-size: 0.85rem;
  letter-spacing: 0.3px;
}

.input-wrapper {
  position: relative;
}

.input-wrapper .icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #a0aec0;
  font-size: 1rem;
  pointer-events: none;
  transition: color 0.3s;
}

.input-group input {
  width: 100%;
  padding: 14px 14px 14px 42px;
  border-radius: 12px;
  border: 2px solid #dde3ed;
  background: #fff;
  font-size: 0.95rem;
  color: #2d3748;
  transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
  outline: none;
}

.input-group input::placeholder {
  color: #b0bec5;
  font-size: 0.85rem;
}

.input-group input:focus {
  border-color: #2f5597;
  box-shadow: 0 0 0 4px rgba(47,85,151,0.1);
  transform: translateY(-1px);
}

.input-group input:focus + .icon,
.input-group input:focus ~ .icon {
  color: #2f5597;
}

/* Reverse icon selector (karena icon sebelum input) */
.input-wrapper:focus-within .icon {
  color: #2f5597;
}

.login-btn {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 12px;
  background: linear-gradient(135deg, #2f5597 0%, #4e6fae 100%);
  color: white;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 30px;
  position: relative;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.3s;
  letter-spacing: 0.5px;
}

.login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(47,85,151,0.35);
}

.login-btn:active {
  transform: translateY(0) scale(0.98);
}

/* Shine effect pada button */
.login-btn::after {
  content: '';
  position: absolute;
  top: -50%;
  left: -60%;
  width: 40%;
  height: 200%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transform: skewX(-20deg);
  transition: left 0.6s;
}
.login-btn:hover::after {
  left: 120%;
}

/* Footer text */
.login-footer {
  text-align: center;
  margin-top: 24px;
  font-size: 0.75rem;
  color: #a0aec0;
}

/* ==================== RESPONSIVE HP ==================== */
@media (max-width: 768px) {
  body {
    flex-direction: column;
    height: auto;
    min-height: 100vh;
    overflow-y: auto;
  }

  .left {
    width: 100%;
    padding: 30px 20px;
    min-height: auto;
  }

  .logo {
    justify-content: center;
    margin-bottom: 20px;
  }
  .logo img { width: 55px; height: 45px; }
  .logo span { font-size: 18px; }

  .left h1 {
    font-size: 24px;
    margin: 40px auto;
  }

  .green, .green-kecil, .blue, .yellow, .yellow-kecil,
  .wisuda, .laptop, .shape, .particle {
    display: none;
  }

  .right {
    width: 100%;
    border-radius: 30px 30px 0 0;
    margin-top: -20px;
    padding: 30px 20px;
    min-height: 70vh;
  }

  .login-box { width: 100%; }
  .login-box h2 { font-size: 24px; }

  .input-group input {
    padding: 14px 14px 14px 42px;
    font-size: 16px;
  }

  .login-btn {
    padding: 14px;
    margin-top: 30px;
  }
}

@media (max-width: 480px) {
  .left h1 { font-size: 20px; margin: 30px auto; }
  .logo span { font-size: 14px; }
  .logo img { width: 45px; height: 35px; }
  .login-box h2 { font-size: 22px; }
}
</style>
</head>

<body>

<!-- ==================== LEFT SIDE ==================== -->
<div class="left">
  <div class="logo">
    <img src="WhatsApp Image 2026-04-10 at 08.00.25.png" />
    <span>SMK NEGRI 1 CIOMAS</span>
  </div>

  <h1>
    Selamat Datang <br>
    di <span>SmartSchool Exam</span><br>
    SKANIC
  </h1>

  <!-- Asset gambar asli kamu -->
  <img src="siswi-skanic.webp" class="wisuda">
  <div class="blue"></div>
  <div class="yellow"></div>
  <div class="yellow-kecil"></div>
  <div class="shape"></div>
  <div class="green"></div>
  <div class="green-kecil"></div>
  <img src="ilustrasi-laptop-anak-sma.jpg" class="laptop">

  <!-- Particles akan di-generate oleh JS -->
</div>

<!-- ==================== RIGHT SIDE ==================== -->
<div class="right">
  <div class="login-box">

    <h2>Login</h2>
    <p class="login-subtitle">Masuk ke portal ujian SmartSchool</p>

    <!-- FORM ASLI TIDAK DIUBAH — CSRF & ROUTE TERJAGA -->
    <form method="POST" action="{{route('users.store')}}">
      @csrf

      <div class="input-group">
        <label>Pengguna</label>
        <div class="input-wrapper">
          <span class="icon"><i class="bi bi-person-circle"></i></span>
          <input type="text" placeholder="Nama Lengkap / Username" name="login">
        </div>
      </div>

      <div class="input-group">
        <label>Password</label>
        <div class="input-wrapper">
          <span class="icon"><i class="bi bi-lock"></i></span>
          <input type="password" placeholder="Password" name="password">
        </div>
      </div>

      <button class="login-btn" type="submit">Masuk</button>
    </form>

    <p class="login-footer">© 2025 SMKN 1 Ciomas — SmartSchool Exam</p>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    
<script>
// =============================================
// GSAP ANIMATIONS — tidak menyentuh form/logic
// =============================================

// --- 1. Floating Particles di panel kiri ---
const leftPanel = document.querySelector('.left');
for (let i = 0; i < 15; i++) {
  const p = document.createElement('div');
  p.classList.add('particle');
  const size = Math.random() * 6 + 2;
  const colors = ['#ffcc33', '#4CBC9A', '#ffffff'];
  p.style.width = size + 'px';
  p.style.height = size + 'px';
  p.style.background = colors[Math.floor(Math.random() * colors.length)];
  p.style.left = Math.random() * 100 + '%';
  p.style.top = Math.random() * 100 + '%';
  leftPanel.appendChild(p);

  // Animasi masing-masing particle: naik-turun + fade
  gsap.to(p, {
    y: -(Math.random() * 80 + 40),
    opacity: 0.6,
    duration: Math.random() * 3 + 2,
    repeat: -1,
    yoyo: true,
    ease: "sine.inOut",
    delay: Math.random() * 2
  });
}

// --- 2. Animasi masuk keseluruhan ---
const tl = gsap.timeline({ defaults: { ease: "power3.out" } });

// Panel kiri slide dari kiri
tl.from('.left', {
  xPercent: -100,
  duration: 0.8,
  ease: "power4.out"
});

// Panel kanan slide dari kanan
tl.from('.right', {
  xPercent: 100,
  duration: 0.8,
  ease: "power4.out"
}, "-=0.6");

// Logo muncul
tl.from('.logo', {
  y: -30,
  opacity: 0,
  duration: 0.6
}, "-=0.4");

// Judul h1 per baris
tl.from('.left h1', {
  y: 40,
  opacity: 0,
  duration: 0.7
}, "-=0.3");

// Underline pada "SmartSchool Exam" mengembang
tl.to('.left h1 span::after', {
  width: '100%',
  duration: 0.8,
  ease: "power2.inOut"
}, "-=0.3");

// --- 3. Shape dekorasi masuk satu per satu ---
const shapes = ['.green', '.green-kecil', '.blue', '.yellow', '.yellow-kecil', '.wisuda', '.laptop'];
shapes.forEach((sel, i) => {
  const el = document.querySelector(sel);
  if (!el) return;
  tl.from(el, {
    y: 60,
    opacity: 0,
    rotation: (i % 2 === 0 ? -8 : 8),
    duration: 0.7,
    
    ease: "back.out(1.4)"
  }, `-=${i < 3 ? 0.5 : 0.3}`);
});

// --- 4. Form elements masuk dari bawah ---
tl.from('.login-box h2', {
  y: 25,
  opacity: 0,
  duration: 0.5
}, "-=0.3");

tl.from('.login-subtitle', {
  y: 15,
  opacity: 0,
  duration: 0.4
}, "-=0.2");

// Setiap input group
gsap.utils.toArray('.input-group').forEach((group, i) => {
  tl.from(group, {
    x: 30,
    opacity: 0,
    duration: 0.5
  }, `-=${i === 0 ? 0.2 : 0.15}`);
});

// Tombol login
tl.from('.login-btn', {
  y: 20,
  opacity: 0,
  scale: 0.95,
  duration: 0.5
}, "-=0.2");

// Footer
tl.from('.login-footer', {
  opacity: 0,
  duration: 0.4
}, "-=0.1");

// --- 5. Hover micro-animation pada input ---
gsap.utils.toArray('.input-group input').forEach(input => {
  input.addEventListener('focus', () => {
    gsap.to(input, { scale: 1.01, duration: 0.2, ease: "power2.out" });
  });
  input.addEventListener('blur', () => {
    gsap.to(input, { scale: 1, duration: 0.2, ease: "power2.out" });
  });
});

// --- 6. Shapes idle floating ---
gsap.to('.green', { y: -12, duration: 3, repeat: -1, yoyo: true, ease: "sine.inOut" });
gsap.to('.green-kecil', { y: -8, duration: 2.5, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 0.5 });
gsap.to('.yellow', { y: -10, duration: 3.5, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 0.3 });
gsap.to('.yellow-kecil', { y: -6, duration: 2.8, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 0.8 });
gsap.to('.wisuda', { y: -8, duration: 4, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 0.2 });
gsap.to('.laptop', { y: -10, duration: 3.2, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 0.6 });
gsap.to('.blue', { y: 8, rotation: "+=3", duration: 3.8, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 1 });

// --- 7. Tombol pulse halus saat idle ---
gsap.to('.login-btn', {
  boxShadow: "0 8px 25px rgba(47,85,151,0.25)",
  duration: 1.5,
  repeat: -1,
  yoyo: true,
  ease: "sine.inOut"
});
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SmartSchool Login</title>

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
}

/* LEFT SIDE */
.left {
  width: 55%;
  background: #2f5597;
  color: white;
  padding: 40px;
  position: relative;
  text-align: center;
}

.logo {
  display: flex;
  align-items: center;
  margin-bottom: 40px;
}
.logo img {
  width: 85px;
  height: 70px;
  margin-right: 15px;
}

.logo span {
  font-weight: 600;
  font-size: 30px;
}

.left h1 {
  font-size: 36px;
  line-height: 1.4;
  margin:150px auto;
}

.left h1 span {
  color: #ffcc33;
}

/* decorative shapes */
.shape {
  position: absolute;
  bottom: 50px;
  left: 40px;
  display: flex;
  gap: 20px;
}

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

.wisuda{
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
}

.laptop{
  position: absolute;
  top: 64%;
  left: 9%;
  width: 160px;
  height: 250px;
  background: #4CBC9A;
  border-radius: 10px;
  transform: rotate(0deg) skewY(-20deg) scaleY(1) perspective(600px);
  box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
  z-index: 5;
}

/* RIGHT SIDE */
.right {
  width: 45%;
  background: #f3f5f9;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50px;
}

.login-box {
  width: 80%;
  padding: 10px;
}

.login-box h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #2f5597;
  font-size: 30px;
}

.role {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-bottom: 20px;
}

.role button {
  padding: 8px 12px;
  border: none;
  border-radius: 10px;
  background: #e0e6f2;
  cursor: pointer;
}

.input-group {
  margin-bottom: 15px;
}

.input-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
}

.input-group input {
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid #ccc;
}

.login-btn {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 10px;
  background: #4e6fae;
  color: white;
  font-size: 16px;
  cursor: pointer;
  margin-top: 50px;
}

/* ========== RESPONSIVE UNTUK HP ========== */
@media (max-width: 768px) {
  /* Ubah layout dari samping jadi ke bawah */
  body {
    flex-direction: column;
    height: auto;
    min-height: 100vh;
  }

  /* LEFT SECTION - Full width */
  .left {
    width: 100%;
    padding: 30px 20px;
    min-height: auto;
  }

  /* Perkecil logo */
  .logo {
    justify-content: center;
    margin-bottom: 20px;
  }

  .logo img {
    width: 55px;
    height: 45px;
  }

  .logo span {
    font-size: 18px;
  }

  /* Perkecil judul */
  .left h1 {
    font-size: 24px;
    margin: 40px auto;
  }

  /* SEMUA shape & gambar dekoratif disembunyikan di HP */
  .green,
  .green-kecil,
  .blue,
  .yellow,
  .yellow-kecil,
  .wisuda,
  .laptop,
  .shape {
    display: none;
  }

  /* RIGHT SECTION - Full width, menempel di bawah */
  .right {
    width: 100%;
    border-radius: 30px 30px 0 0;
    margin-top: -20px;
    padding: 30px 20px;
  }

  /* Form login lebih lebar */
  .login-box {
    width: 100%;
  }

  .login-box h2 {
    font-size: 24px;
  }

  /* Input lebih nyaman disentuh */
  .input-group input {
    padding: 14px;
    font-size: 16px;
  }

  .login-btn {
    padding: 14px;
    margin-top: 30px;
  }
}

/* HP sangat kecil (max 480px) */
@media (max-width: 480px) {
  .left h1 {
    font-size: 20px;
    margin: 30px auto;
  }

  .logo span {
    font-size: 14px;
  }

  .logo img {
    width: 45px;
    height: 35px;
  }

  .login-box h2 {
    font-size: 22px;
  }
}
</style>
</head>

<body>

<!-- LEFT -->
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
  <img src="siswi-skanic.webp" class="wisuda">
  <div class="blue"></div>
  <div class="yellow"></div>
  <div class="yellow-kecil"></div>
  <div class="shape"></div>
  <div class="green"></div>
  <div class="green-kecil"></div>
  <img src="ilustrasi-laptop-anak-sma.jpg" class="laptop">
</div>

<!-- RIGHT -->
<div class="right">
  <div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="{{route('users.store')}}">
      @csrf
      <div class="input-group">
        <label>Pengguna</label>
        <input type="text" placeholder="Nama Lengkap / Username" name="login">
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" placeholder="Password" name="password">
      </div>

      <button class="login-btn" type="submit">Masuk</button>
    </form>
  </div>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SmartSchool Exam - Login (mobile fix)</title>

<style>
*{
  box-sizing:border-box;
  font-family:Arial, sans-serif;
}

body{
  margin:0;
  background:#f3f6fb;
}

/* ===== LAYOUT FLEX ===== */
.login-page{
  display:flex;
  flex-direction: row;           /* desktop: kiri + kanan sejajar */
  min-height:100vh;
  width:100%;
}

/* ===== BANNER KIRI ===== */
.login-banner{
  width:55%;
  background:#1f4f8c;
  color:white;
  padding:50px;
  position:relative;
  overflow:hidden;
}

.brand{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:40px;
}

.logo{
  width:42px;
  height:42px;
  border-radius:50%;
  background:white;
}

.login-banner h1{
  font-size:36px;
  line-height:1.3;
  margin-bottom:40px;
}

.login-banner span{
  color:#ffd34f;
}

/* ===== FOTO + FRAME MIRING ===== */
.photos{
  display:flex;
  gap:25px;
}

/* frame */
.photo-frame{
  padding:10px;
  border-radius:18px;
  transform:skewY(-6deg) rotate(-3deg);
  box-shadow:0 15px 30px rgba(0,0,0,.25);
}

/* warna frame */
.photo-frame.green{
  background:#2dd4a4;
}

.photo-frame.yellow{
  background:#f6c343;
}

/* foto di dalam frame (dibalik transform agar lurus) */
.photo-frame img{
  display:block;
  width:170px;
  height:115px;
  object-fit:cover;
  border-radius:14px;
  transform:skewY(6deg) rotate(3deg);
}

/* ===== FORM KANAN ===== */
.login-form{
  width:45%;
  background:#f9fbff;
  padding:50px;
  display:flex;
  flex-direction:column;
  justify-content:center;
}

.login-form h2{
  margin-bottom:20px;
}

.roles{
  display:flex;
  gap:10px;
  margin-bottom:25px;
  flex-wrap:wrap;
}

.roles button{
  border:none;
  background:#e7eefb;
  padding:8px 14px;
  border-radius:8px;
  cursor:pointer;
  font-size:13px;
  transition: background 0.2s;
}

.roles button:hover {
  background:#d0ddeb;
}

input{
  padding:12px 14px;
  margin-bottom:14px;
  border-radius:8px;
  border:1px solid #ccc;
  font-size:14px;
}

input:focus{
  outline:none;
  border-color:#4c6fa7;
}

.btn{
  padding:12px;
  background:#4c6fa7;
  color:white;
  border:none;
  border-radius:8px;
  cursor:pointer;
  font-size:15px;
}

.btn:hover{
  opacity:.9;
}

/* ===== RESPONSIVE FIX ===== */
/* saat layar lebarnya 768px atau lebih kecil: tumpuk vertikal */
@media(max-width:768px){
  .login-page{
    flex-direction: column;       /* mobile: banner di atas, form di bawah */
  }
  .login-banner,
  .login-form{
    width:100%;                   /* masing-masing lebar penuh */
  }

  /* sedikit penyesuaian padding agar tidak terlalu rapat di hp */
  .login-banner {
    padding: 30px 20px;
  }
  .login-form {
    padding: 40px 25px;
  }

  /* gambar mungkin terlalu besar, perkecil sedikit */
  .photo-frame img{
    width: 130px;
    height: 90px;
  }
  .photos {
    gap: 15px;
  }
  .login-banner h1 {
    font-size: 28px;
  }
}

/* untuk layar sangat kecil (opsional) */
@media(max-width:480px){
  .photo-frame img{
    width: 110px;
    height: 75px;
  }
  .roles button {
    padding: 6px 10px;
    font-size: 12px;
  }
}
</style>
</head>
<body>

<div class="login-page">

  <!-- ================= KIRI / BANNER ================= -->
  <div class="login-banner">

    <div class="brand">
      <div class="logo"></div>
      <strong>SMK NEGRI 1 CIOMAS</strong>
    </div>

    <h1>
      Selamat Datang<br>
      di <span>SmartSchool Exam</span><br>
      SKANIC
    </h1>

    <div class="photos">

      <!-- frame hijau -->
      <div class="photo-frame green">
        <!-- menggunakan gambar dari picsum (tetap) -->
        <img src="https://picsum.photos/400/300?random=11" alt="student activity">
      </div>

      <!-- frame kuning -->
      <div class="photo-frame yellow">
        <img src="https://picsum.photos/400/300?random=12" alt="classroom">
      </div>

    </div>

  </div>

  <!-- ================= KANAN / FORM ================= -->
  <div class="login-form">

    <h2>Login</h2>

    <div class="roles">
      <button type="button">Guru</button>
      <button type="button">Siswa</button>
      <button type="button">Pengawas</button>
      <button type="button">Admin OP</button>
      <button type="button">Admin S</button>
    </div>
    <form action="{{route('users.store')}}" method="post">
      @csrf
      <input type="text" placeholder="Pengguna" name="nama">
    <input type="password" placeholder="Password" name="password">

    <button class="btn" style="display:block;" type="submit">Masuk</button>
    </form>
    

  </div>

</div>

</body>
</html>
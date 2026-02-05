<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="bulma.min.css">
  <title>Guru Teacher</title>
</head>
<body>
  @if($ire)
   <div class="level is-mobile">
    <h5 class="level-left">
      Halo {{$ire->nama}}
    </h5>
    <div class="level-right">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
    <button class="button is-danger" type="submit">Logout</button>
      </form>
    </div>
  </div>
  @else
  <div class="level is-mobile">
    <h5 class="level-left">
      Halo Dika
    </h5>
    <div class="level-right">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
    <button class="button" type="submit">Logout</button>
      </form>
    </div>
  </div>
  @endif
  <div class="level is-mobile">
    <div class="level-left">
      <h5 class="subtitle has-text-weight-bold">Ujian </h5>
    </div>
    <div class="level-right">
      
  <button class="button is-info my-2" onclick="document.getElementById('cret').classList.add('is-active')">
    Create Ujian
  </button>
    </div>
  </div>
  <div class="modal" id="cret">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <h5 class="title">Create Ujian</h5>
      </header>
      <section class="modal-card-body">
        <form action="{{route('guru.store')}}" method="post">
          @csrf
         <div class="field">
           <label for="" class="label">Waktu Mulai</label>
           <div class="control">
             <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="input" placeholder="Waktu Mulai">
            
           </div>
         </div>
         <div class="field">
           <label for="" class="label">Waktu Selesai</label>
           <div class="control">
             <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="input" placeholder="Waktu Selesai">
            
           </div>
         </div>
         <div class="level is-mobile">
           <div class="level-left">
             <div class="field">
           <div class="select">
             <select name="mapel_id" id="mapel_id">
               <option value="">Pilih Mapel Anda</option>
             </select>
           </div>
         </div>
           </div>
           <div class="level-right">
             <div class="field">
           <div class="select">
             <select name="kelas_id" id="kelas_id">
               <option value="">Kelas</option>
             </select>
           </div>
         </div>
           </div>
         </div>
         
         
         <input type="text" class="input" value="{{$ire->nama}}" readonly>
         <label for="" class="label">Nama Ujian</label>
         <div class="field">
           <div class="control">
         <input type="text" class="input" name="nama_ujian" placeholder="Nama Ujian ">
             
           </div>
         </div>
         <div class="field">
           <div class="control">
             <input type="number" name="durasi" id="durasi" class="input" placeholder="durasi">/menit
           </div>
         </div>
         
      </section>
      <footer class="modal-card-foot">
        <div class="buttons">
          <button type="reset" class="button is-danger-dark" onclick="document.getElementById('cret').classList.remove('is-active')">Cancel</button>
          <button type="submit" class="button is-info">submit</button>
        </div>
        </form>
      </footer>
    </div>
  </div>
  @foreach($uji as $uj)
  <div class="message is-primary">
    <div class="message-header">
      <h5>{{$uj->nama_ujian}}</h5>
      
c c    </div>
    <div class="message-body">
      {{$uj->durasi}} <br>
      {{$uj->waktu_mulai}} - {{$uj->waktu->selesai}} <br>
      
    </div>
  </div>
  @endforeach
</body>
</html>
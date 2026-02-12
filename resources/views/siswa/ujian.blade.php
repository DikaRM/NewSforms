<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Ujian{{$uji->nama_ujian}} </title>
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
</head>
<body>
  <div class="card">
    <div class="card-header">
      <h5 class="title">Ujian {{$uji->nama_ujian}}</h5>
      <p class="subtitle" id="display"></p>
      
    </div>
    <div class="card-container">
      <h5 class="subtitle">Peserta Ujian {{$ire->nama}}</h5>
      <p class="subtitle">Kelas : {{$sis->kelas->nama_kelas}} </p>
      <p class="subtitle">Mapel : {{$uji->mapels->nama_mapel}} </p>
      
    </div>
  </div>
  <h5 class="title">Kerjakan Soal Berikut ini!</h5>
  <form action="{{route('siswa.save')}}" method="post">
    @csrf
    <input type="hidden" name="ujian_id" value="{{$uji->id}}">
    <input type="hidden" name="siswa_id" value="{{$sis->id_siswa}}">
    
@foreach($ujians as $ops)
  @foreach($soal as $so)
    @if($ops->bank_id == $so->id)
      <h5 class="subtitle">{{$loop->iteration}}.{{$so->soal}}</h5>
      <input type="hidden" name="soal_id[]" value="{{$so ->id}}">
      @if($so->opsi_a != null)
      <div class="control">
        <label for="" class="radio">
          
        <input type="radio" name="jawaban[{{$so->id}}]" value="a">
        {{$so->opsi_a}}
        </label>
        <label for="" class="radio">
          
        <input type="radio" name="jawaban[{{$so->id}}]" value="b">
        {{$so->opsi_b}}
        </label>
        <label for="" class="radio">
          
        <input type="radio" name="jawaban[{{$so->id}}]" value="c">
        {{$so->opsi_c}}
        </label>
        <label for="" class="radio">
          
        <input type="radio" name="jawaban[{{$so->id}}]" value="d">
        {{$so->opsi_d}}
        </label>
      </div>
      
      @else
        <input type="text" class="input" name="jawaban[{{$so->id}}]" placeholder="Jawaban Kamu">
      @endif
    @endif
  @endforeach
@endforeach
  <button type="submit" class="button is-centered is-info is-focused">Submit</button>
  </form>
  <script>
    let warn = 0;
    let maxwarn = 3;
    function startTimer(durasi,display){
      let timer = durasi
      const inter = setInterval(function(){
        
      let minit = parseInt(timer / 60 ,10);
      let detik = parseInt(timer % 60 ,10);
       minit = minit < 10 ? "0" + minit : minit;
     detik = detik < 10 ? "0" + detik : detik;
      display.innerText = minit +":"+ detik
      timer--;
      if(timer < 0){
        clearInterval(inter)
        alert("Sudah Habis")
      }
      },1000)
      return inter;
    }
    window.onload = function () {
      const display = document.getElementById("display");
      if(display){
      const durasi ={{$uji->durasi * 60}};
        startTimer(durasi,display)
      }
      document.addEventListener("visibilitychange",function(){
      if(document.visibilityState === "hidden"){
        alert('udh Keluar Tab nih?')
        warn++;
      }
    })
    document.addEventListener("copy",function(e){
      e.preventDefault();
      alert("Ga Boleh Di Copy!")
    })
    document.addEventListener("paste",function(e){
      e.preventDefault();
      alert("Ga Boleh Di Paste Payah!")
    })
    
    document.addEventListener("contextmenu",function(e){
      e.preventDefault();
      alert("Ga Boleh Di Klik Kanan!")
    });
    async function forceFull(){
      try{
        await document.documentElement.requestFullscreen()
      }
      catch(err){
        alert("Why Ari kamu t3h?")
      }
    }
    document.addEventListener("fullscreenchange",function(){
      if(!document.fullscreenElement){
        forceFull();
      }
      
    })
    
    
    }
    
  </script>
</body>
</html>
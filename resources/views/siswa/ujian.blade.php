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
      <p class="subtitle">Waktu {{$uji->durasi}} Menit</p>
      
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
    <input type="hidden" name="siswa_id" value="{{$sis->id}}">
    
@foreach($ujians as $ops)
  @foreach($soal as $so)
    @if($ops->bank_id == $so->id)
      <h5 class="subtitle">{{$loop->iteration}}.{{$so->soal}}</h5>
      <input type="hidden" name="soal_id[]" value="{{$so ->id}}">
      @if($so->opsi_a != null)
        <input type="radio" name="jawaban_{{$so->id}}" value="a">
        {{$so->opsi_a}}
      @else
        <input type="text" class="input" name="jawaban[{{$so->id}}]" placeholder="Jawaban Kamu">
      @endif
    @endif
  @endforeach
@endforeach
  <button type="submit" class="button is-centered is-info is-focused">Submit</button>
  </form>
</body>
</html>
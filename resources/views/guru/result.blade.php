<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
</head>
<body>
  @foreach($data as $dt)
  <h5 class="title">
    
  Ujian : {{$dt->nama_ujian}}
  </h5>
  <div class="card mt-2">
    <div class="card-header">
      @foreach($dt->kelas as $kelas)
      <h5 class="title">Kelas {{$kelas->nama_kelas}}</h5>
    </div>
    <div class="card-content">
      <p class="subtitle">Total Siswa : {{$kelas->siswa->count()}}</p>
      @endforeach
      <table class="table is-stripped is-fullwidth">
        <tr>
          <td>ID</td>
          <td>Nama siswa</td>
          <td>Nisn</td>
          <td>Nilai</td>
        </tr>
        @foreach($val as $vl)
        <tr>
          <td>{{$vl->siswa_id}}</td>
          <td>{{$vl->siswa->nama}}</td>
          <td>{{$vl->siswa->nisn}}</td>
          <td>{{$vl->nilai}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
  @endforeach
  <p class="subtitle"></p>
</body>
</html>
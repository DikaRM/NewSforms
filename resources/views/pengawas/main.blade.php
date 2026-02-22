<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
  <title>Let see</title>
</head>
<body>
  <h5 class="title">Kelas : {{$jadk->kelas->nama_kelas}}</h5>
  <p class="subtitle">{{$jadk->ujian->nama_ujian}}</p>
  <table class="table is-stripped is-fullwidth">
    <tr>
      <td>Id</td>
      <td>Nama</td>
      <td>Nisn</td>
      <td>Pelanggaran</td>
    </tr>
    @foreach($data as $dt)
    <tr>
      <td>{{$dt->id_siswa}}</td>
      <td>{{$dt->nama}}</td>
      <td>{{$dt->nisn}}</td>
      @foreach($pelan as $pl)
        @if($pl->siswa_id == $dt->id_siswa)
          <td>{{$pl->jenis_pelanggaran}}</td>
        @else
          <td>Aman</td>
        @endif
      @endforeach
    </tr>
    @endforeach
  </table>
  <form action="{{route('pengawas.store')}}" method="post">
    @csrf
    <div class="level is-mobile field ">
      <div class="control level-left">
        <input type="text" class="input" name="ujian_id" value="{{$jadk->ujian_id}}">
      </div>
      <div class="level-right select">
        <select name="siswa_id" >
          <option value="">Pilih Siswa</option>
          @foreach($data as $dt)
          <option value="{{$dt->id_siswa}}">{{$dt->nama}}</option>
          @endforeach
        </select>
        </div>
    </div>
    <div class="field">
      <textarea name="catatan"  cols="30" rows="2" class="textarea">Pelanggaran Yang Dilakukan</textarea>
    </div>
    <div class="buttons is-right">
       <button class="button is-danger is-medium" type="submit">Report</button>
    </div>
   
  </form>
</body>
</html>
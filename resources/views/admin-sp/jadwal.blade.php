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
   <label for="" class="label">
     Mulai Tanggal
   </label>
   <input type="datetime-local" name="set" id="start" class="input">{{\Carbon\Carbon::now()}}
  <h5 class="title">Kelas : {{$klas->nama_kelas}}</h5>
      <p>Ujian -- {{$uji->count()}}</p>
      
      <table class="table is-fullwidth is-hoverable is-stripped">
        <tr>
          <td>Jam</td>
          <td>Waktu Mulai</td>
          <td>Waktu Berakhir</td>
          <td>Mata Ujian</td>
          
        </tr>
        
        @foreach($jad as $jd)
        <tr>
          <td>{{$jd->jam_mapel}}</td>
          <td>{{\Carbon\Carbon::parse($jd->tanggal)->isoFormat("dddd")}} <br>
          {{\Carbon\Carbon::parse($jd->tanggal)->format("H:i")}}
          </td>
          <td>
            {{\Carbon\Carbon::parse($jd->ujian->waktu_selesai)->isoFormat("dddd")}}
            <br>
            {{\Carbon\Carbon::parse($jd->ujian->waktu_selesai)->format("H:i")}}
          </td>
          <td>{{$jd->ujian->nama_ujian}}</td>
          @foreach($penh as $pe)
          <td>
            {{$pe->guru->nama}}
          </td>
         @endforeach

        </tr>
        @endforeach
        <form action="{{route('admin-ops.sav')}}" method="post">
          @csrf
           <tr>
          <td><input type="number" class="input" name="jam_mapel" min="1"  value=""></td>
          <td colspan="2">
            <input type="datetime-local" name="tanggal" id="" class="input">

          </td>
          <td>
            <div class="select">
              <select name="ujian_id" id="ujian">
                <option value="">Pilih Ujian</option>
                @foreach($uji as $uj)
                <option value="{{$uj->id}}">{{$uj->nama_ujian}}
                <br>
                {{$uj->durasi}} Menit
                </option>
                @endforeach
              </select>
            </div>
          </td>
          <td>
            <div class="select"><select name="guru_id">
                <option value="">Pilih Pengawas </option>
                @foreach($gur as $gu)
                <option value="{{$gu->id}}">
                  {{$gu->nama}}
                </option>
                @endforeach
              </select></div>
          </td>
          <td>
            <button type="submit" class="button">Select</button>
          </td>
        </tr>
        <input type="hidden" name="kelas_id" value="{{$klas->id}}">
        
        
        </form>
       
      </table>
      
      
</body>
</html>
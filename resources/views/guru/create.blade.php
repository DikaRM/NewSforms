<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Manage Ujian </title>
   <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
</head>
<body>
  <div class="level is-mobile">
    <div class="level-left">
        <h5 class="title">
    {{$uji->nama_ujian}}
  </h5>
    </div>
    <div class="level-right">
      <span class="tag is-info ">
      Guru {{$gurus->nama}}
        
      </span>
    </div>
  </div>

  <h5 class="subtitle">
    Durasi:{{$uji->durasi}}
  </h5>
  <div class="card">
    <div class="card-head">
      <h5>{{\Carbon\Carbon::parse($uji->waktu_mulai)->format('d F Y H:i')}} S.d {{\Carbon\Carbon::parse($uji->waktu_selesai)->format('D F Y H:i')}}</h5> 
    </div>
    <div class="card-content">
      <h5 class="title"></h5>
    </div>
  </div>
   <table class="table is-stripped is-fullwidth">
     <thead>
       <tr>
         <td>No</td>
         <td>Pertanyaan</td>
         <td>Opsi A</td>
         <td>Opsi B</td>
         <td>Opsi C</td>
         <td>Opsi D</td>
         <td>Jawaban</td>
         <td>Aksi</td> 
       </tr>
     </thead>
     <tbody>
       @foreach($fu as $f)
       
         @foreach($bak as $b)
           @if($f->bank_id == $b->id)
             <tr>
             <td>{{$b->id}}</td>
             <td>{{$b->soal}}</td>
             <td>{{$b->opsi_a}}</td>
             <td>{{$b->opsi_b}}</td>
             <td>{{$b->opsi_c}}</td>
             <td>{{$b->opsi_d}}</td>
             <td>{{$b->jawaban_benar}}</td>
             <td>
             <button class="button is-warning is-dark" >Edit </button>
             <form action="{{route('soal.destroy',['id' => $b->id])}}" method="post">
             @csrf
             @method("DELETE")
             <button type="submit" class="button is-danger is-dark">Hapus</button>
             </form>
             </td>
             </tr>
          @endif
        @endforeach
      @endforeach
       <tr>
         <td colspan="6">Tambahkan Soal</td>
         <td><button class="button is-info" onclick="document.getElementById('modals').classList.add('is-active')">
           Create Soal
         </button></td>
       </tr>
       <div class="modal" id="modals">
         <div class="modal-background">
         </div>
           <div class="modal-card">
             <header class="modal-card-head">
               <h5 class="title">Tambah Soal</h5>
             </header>
             <section class="modal-card-body">
               <form action="{{route('soal.save')}}" method="post">
                 @csrf
                 
                 <input type="hidden" class="input" name="guru_id" value="{{$uji->guru_id}}" readonly placeholder="{{$uji->nama_ujian}}">
                 
                 <input type="hidden" class="input" name="mapel_id" value="{{$uji->mapel}}" placeholder="" readonly>
                 <input type="hidden" class="input" name="ujian_id" value="{{$uji->id}}"  readonly>
                 
                 <div class="field">
                   <div class="control"><input type="text" class="input" name="soal" placeholder="Pertanyaan ">
                   </div>
                 </div>
               <label for="" class="checkbox">
                 <input type="checkbox"  id="chek" > Gunakan Pilihan Ganda
               </label>
                 
                 <div class="field">
                   
                   <div class="control" id="inp">
                <input type="text" class="input" name="jawaban_benar" placeholder="Jawaban Benarnya ">
                   </div>
                 </div>
                 
                 <div id="opsi">
                <input type="text" class="input" name="opsi_a" placeholder="Opsi A ">
                
                <input type="text" class="input" name="opsi_b" placeholder="Opsi B">
                <input type="text" class="input" name="opsi_c" placeholder="Opsi C">
                <input type="text" class="input" name="opsi_d" placeholder="Opsi  D">

                   </div>
                 </div>
                 
                 
                 
                 
             </section>
             <footer class="modal-card-foot">
               <button type="submit" class="button is-primary">Submit</button>
               </form>
             </footer>
           </div>
       </div>
     </tbody>
     
   </table>  
   <script>
       const opsi = document.getElementById("opsi");
       const chek = document.getElementById("chek");
       const inp = document.getElementById("inp");
       chek.onchange = () => {
         opsi.style.display="block"
         
         
       }
       inp.style.display="block"
       opsi.style.display="none"
       
     </script>
    
     <form action="{{route('ujian.sold',$uji->id)}}" method="post">
       @csrf
       @method("PUT")
       <button class="button is-info is-fullwidth is-shadow" onclick="confirm('Yakin di Publish')" type="submit">Publish</button>
     </form>
     
     
</body>
</html>
@extends("layouts.blank")
@section("content")
<button class="button is-info" onclick="document.getElementById('mod').classList.add('is-active')">Tambah Kelaz</button>
<div class="modal" id="mod">
  <div class="modal-background">
    
  </div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title">
        Tambah Kelas
      </h5>
    </header>
    <section class="modal-card-body">
    <section class="modal-card-body">
      <form action="{{route('admin.tambah')}}" method="post">
        @csrf
        <div class="field">
          <div class="control">
            <input type="text" class="input" name="nama_kelas" placeholder="Contoh : XI PPLG 1">
          </div>
        </div>
    </section>
    <footer class="modal-card-foot">
      <div class="buttons">
        <button type="reset" class="button is-danger" onclick="document.getElementById('mod').classList.remove('is-active')">
          Cancel
        </button>
        <button type="submit" class="button is-info">
          Submit
        </button>
      </div>
      </form>
    </footer>
  </div>
</div>
<table class="table is-stripped is-fullwidth">
  <thead>
    
  <tr>
    <td>ID</td>
    <td>Nama Kelas</td>
    <td>More</td>
  </tr>
  </thead>
  <tbody>
    @foreach($dat as $d)
   <tr>
     <td>{{$d->id}}</td>
     <td>{{$d->nama_kelas}}</td>
     <td>
       <div class="buttons">
         <button class="button is-warning">Edit</button>
         <button class="button is-link is-dark" onclick="document.getElementById('mods{{$d->id}}').classList.add('is-active')">Add</button>
         <button class="button is-danger">Hapus</button>
         
       </div>
     </td>
   </tr>
   <div class="modal" id="mods{{$d->id}}">
     <div class="modal-background"></div>
     <div class="modal-card">
       <header class="modal-card-head">
         <h5 class="title">Tambah Siswa</h5>
       </header>
       <section class="modal-card-body">
         <form action="{{route('admin.ade',$d->id)}}" method="post">
           @csrf
           <div class="select">
           <select name="ids" id=""><option value="">Pilih Siswa</option>
             @foreach($siswa as $sis)
             <option value="{{$sis->id}}">{{$sis->nama}}</option>
           @endforeach
           </select>
           </div>
       </section>
       <footer class="modal-card-foot">
         <div class="buttons">
           <button type="submit" class="button">Submit</button>
         </div>
         </form>
       </footer>
     </div>
   </div>
   @endforeach
  </tbody>
</table>
@endsection
@extends("layouts.blank")
@section("content")
<button class="button is-info is-outline" onclick="document.getElementById('mod').classList.add('is-active')">
  Tambah Data Siswa
</button>
<div class="modal" id="mod">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title">Popup Add Siswa</h5>
    </header>
    <section class="modal-card-body">
      <form action="{{route('admin-siswa.store')}}" method="post">
        @csrf
        <div class="field">
          <div class="control">
            <input type="text" class="input" placeholder="Nama Lengkap" name="nama">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="password" class="input" placeholder="Password Lengkap" name="password">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="number" class="input" placeholder="NISN : 16 Digit" minlength="16" maxlength="16" name="nisn">
          </div>
        </div>
        
        
        
    </section>
    <footer class="modal-card-foot">
      <div class="buttons">
        <button type="reset" class="button is-danger" onclick="document.getElementById('mod').classList.remove('is-active')">Cancel</button>
        <button type="submit" class="button is-info is-inverted">Create
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
      <td>Nama</td>
      <td>NISN</td>
      <td>Aksi</td>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $d)
  <tr>
    <td>{{$d->id}}</td>
    <td>{{$d->nama}}</td>
    <td>{{$d->nisn}}</td>
    <td>
      <div class="buttons">
        <button class="button is-warning">Edit</button>
        <form action="{{route('admin-siswa.destroy',$d->id)}}" method="post">
          @csrf
          @method("DELETE")
        <button class="button is-danger" type="submit">Hapus</button>
        </form>
      </div>
    </td>
  </tr>
  @endforeach
  </tbody>
</table>
@endsection
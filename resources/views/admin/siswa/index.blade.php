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
        <div class="select">
          <select name="kelas_id">
            <option value="">Pilih Kelas </option>
            @foreach($kelas as $kl)
            <option value="{{$kl->id}}">{{$kl->nama_kelas}}</option>
            @endforeach
          </select>
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
      <td>Kelas</td>
      <td>Aksi</td>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $d)
  <tr>
    <td>{{$d->id}}</td>
    <td>{{$d->nama}}</td>
    <td>{{$d->nisn}}</td>
    <td>{{$d->kelas->nama_kelas}}</td>
    <td>
      <div class="buttons">
        <button class="button is-warning" onclick="document.getElementById('mod{{$d->id}}').classList.add('is-active')">Edit</button>
        <form action="{{route('admin-siswa.destroy',$d->id_siswa)}}" method="post">
          @csrf
          @method("DELETE")
        <button class="button is-danger" type="submit">Hapus</button>
        </form>
      </div>
    </td>
  </tr>
  <div class="modal" id="mod{{$d->id}}">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title">Popup Add Siswa</h5>
    </header>
    <section class="modal-card-body">
      <form action="{{route('admin-siswa.update',$d->id_siswa)}}" method="post">
        @csrf
        @method("PUT")
        <div class="field">
          <div class="control">
            <input type="text" class="input" placeholder="Nama Lengkap" name="nama" value="{{$d->nama}}">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="text" class="input" placeholder="Password Lengkap" name="password" >
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="number" class="input" placeholder="NISN : 16 Digit" minlength="16" maxlength="16" name="nisn" value="{{$d->nisn}}">
          </div>
          </div>
          </section>
          <footer class="modal-card-foot">
            <button type="submit" class="button is-info">Submit</button>
            </form>
          </footer>
          </div>
        </div>
  @endforeach
  </tbody>
</table>
@endsection
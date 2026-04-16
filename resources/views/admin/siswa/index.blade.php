@extends("layouts.blank")
@section("content")
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33'
    });
</script>
@endif

<button class="button is-info is-outline" onclick="document.getElementById('mod').classList.add('is-active')">
  Tambah Data Siswa
</button>
<div class="modal" id="mod">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title">Add Siswa</h5>
    </header>
    <section class="modal-card-body">
      <form action="{{route('admin.siswa.store')}}" method="post">
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
      <td>No</td>
      <td>Username</td>
      <td>Nama Lengkap</td>
      <td>NISN</td>
      <td>Kelas</td>
      <td>Aksi</td>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $d)
  <tr>
    <td>{{$d->id_siswa}}</td>
    <td>{{$d->username}}</td>
    
    <td>{{$d->nama}}</td>
    <td>{{$d->nisn}}</td>
    <td>{{$d->kelas->nama_kelas}}</td>
    <td>
      <div class="buttons">
        <button class="button is-warning" onclick="document.getElementById('mod{{$d->id_siswa}}').classList.add('is-active')">Edit</button>
        <form action="{{route('admin.siswa.destroy',$d->id_siswa)}}" method="post">
          @csrf
          @method("DELETE")
        <button class="button is-danger" type="submit">Hapus</button>
        </form>
      </div>
    </td>
  </tr>
  <div class="modal" id="mod{{$d->id_siswa}}">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title"> Edit Siswa</h5>
    </header>
    <section class="modal-card-body">
      <form action="{{route('admin.siswa.update',$d->id_siswa)}}" method="post">
        @csrf
        @method("PUT")
        <div class="field">
          <div class="control">
            <input type="text" class="input"  name="nama" placeholder="Hayo"value="{{$d->nama}}">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="text" class="input" placeholder="Password Lengkap" name="password" value="{{$d->password}}">
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
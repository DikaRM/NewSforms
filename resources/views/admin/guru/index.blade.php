@extends("layouts.blank")
@section("content")
<button class="button is-info"  onclick="document.getElementById('modals').classList.add('is-active')">Tambah Guru</button>
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

<div class="modal" id="modals">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title">Forms Create</h5>
    </header>
    <section class="modal-card-body">
      <form action="{{route('admin.guru.store')}}" method="post">
        @csrf
        <div class="field">
          <div class="control">
            <input type="text" class="input" name="username" placeholder="Nama Lengkap">
            
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="password" class="input" name="password" placeholder="Password Guru">
            
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="text" class="input" name="nip" placeholder="NIP">
            
          </div>
        </div>
    </section>
    <footer class="modal-card-foot">
      <div class="buttons">
        <button class="button is-danger" onclick="document.getElementById('modals').classList.remove('is-active')">Cancel</button>
        <button class="button is-info" type="submit">Submit</button>
      </div>
      </form>
    </footer>
  </div>
</div>
<table class="table is-fullwidth is-hoverable is-stripped">
  <thead>
    <tr>
      <td>ID</td>
      <td>Nama</td>
      <td>NIP</td>
      <td>More</td>
    </tr>
  </thead>
  <tbody>
   @foreach($data as $s)
<tr>
  <td>{{$s->id}}</td>
  <td>{{$s->nama}}</td>
  <td>{{$s->nip}}</td>
  <td>
    <button class="button is-warning" onclick="document.getElementById('modd{{$s->id}}').classList.add('is-active')">Edit</button>
    <form action="{{route('admin.guru.destroy',$s->id)}}" method="post">
      @csrf
      @method("DELETE")
      <button class="button is-danger" onclick="confirm('Yakin Hapus Ini?')">Delete</button>
    </form>
  </td>
</tr>

<!-- Modal Edit - Gunakan ID yang konsisten -->
<div class="modal" id="modd{{$s->id}}">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <div class="level is-mobile">
        <h5 class="level-left title">
          Update Data
        </h5>
        <button class="delete level-right" onclick="document.getElementById('modd{{$s->id}}').classList.remove('is-active')"></button>
      </div>
    </header>
    <section class="modal-card-body">
      <form action="{{route('admin.guru.update', $s->id)}}" method="post">
        @csrf
        @method("PUT")
        <div class="field">
          <div class="control">
            <input type="text" class="input" name="username" placeholder="Nama Lengkap" value="{{$s->nama}}">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="password" class="input" name="password" placeholder="Password Baru (kosongkan jika tidak diubah)">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="text" class="input" name="nip" placeholder="NIP" value="{{$s->nip}}">
          </div>
        </div>
    </section>
    <footer class="modal-card-foot">
      <button type="submit" class="button is-warning is-fullwidth">Update</button>
      <button class="button is-danger is-fullwidth" onclick="document.getElementById('modd{{$s->id}}').classList.remove('is-active')">Cancel</button>
    </footer>
    </form>
  </div>
</div>
@endforeach
  </tbody>
</table>
@endsection
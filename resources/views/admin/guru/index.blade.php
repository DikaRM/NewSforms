@extends("layouts.blank")
@section("content")
<button class="button is-info" style="margin:10px 2px;" onclick="openCreate()"><i class="fas fa-plus"></i>Tambah Guru</button>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* Overlay (background gelap) */
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(4px);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;

    opacity: 0;
    visibility: hidden;
    transition: 0.3s;
}

.overlay.is-active {
    opacity: 1;
    visibility: visible;
}

/* Bottom Sheet */
.bottom-sheet {
    width: 100%;
    max-width: 500px;
    background: white;
    border-radius: 20px 20px 0 0;
    transform: translateY(100%);
    transition: 0.4s ease;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}

/* Animasi muncul */
.overlay.is-active .bottom-sheet {
    transform: translateY(0);
}

/* Handle kecil atas */
.sheet-handle {
    width: 40px;
    height: 4px;
    background: #ccc;
    margin: 10px auto;
    border-radius: 10px;
}

/* Header */
.sheet-header {
    padding: 16px;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
}

.sheet-title {
    font-weight: bold;
}

/* Body */
.sheet-body {
    padding: 16px;
    overflow-y: auto;
}

/* Footer */
.sheet-footer {
    padding: 16px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}

/* Button */
.btn-submit {
    flex: 1;
    background: #3085d6;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 8px;
}

.btn-cancel {
    flex: 1;
    background: #d33;
    border: none;
    padding: 10px;
    color:white;
    border-radius: 8px;
}

/* Input biar rapi */
.form-input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}
</style>
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
@error('password')
<script>
Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Password Minimal 6',
        confirmButtonColor: '#d33'
    });
</script>

@enderror
@error('nip')
<script>
Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Nip Harus 18 digit',
        confirmButtonColor: '#d33'
    });
</script>

@enderror
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
    <div class="buttons">
      <button onclick="openEdit('{{ $s->id }}','{{ $s->nama }}','{{ $s->nip }}')" class="button is-warning is-light">
    Edit
</button>
    <form action="{{route('admin.guru.destroy',$s->id)}}" method="post">
      @csrf
      @method("DELETE")
      <button class="button is-danger" onclick="confirm('Yakin Hapus Ini?')">Delete</button>
    </form>
    </div>
    
  </td>
</tr>

<!-- Modal Edit - Gunakan ID yang konsisten -->

@endforeach
  </tbody>
</table>
<div class="overlay" id="global-sheet">
    <div class="bottom-sheet">
        <div class="sheet-handle" onclick="closeSheet()"></div>

        <div class="sheet-header">
            <h3 class="sheet-title" id="sheet-title">Title</h3>
            <button class="btn-close" onclick="closeSheet()" style="background:transparent;border:none;"></button>
        </div>

        <div class="sheet-body" id="sheet-body">
            <!-- isi dynamic -->
        </div>

        <div class="sheet-footer" id="sheet-footer">
            <!-- tombol dynamic -->
        </div>
    </div>
</div>
<script>
function openSheet({ title, body, footer }) {
    document.getElementById('sheet-title').innerHTML = title;
    document.getElementById('sheet-body').innerHTML = body;
    document.getElementById('sheet-footer').innerHTML = footer;

    document.getElementById('global-sheet').classList.add('is-active');
    document.body.style.overflow = 'hidden';
}

function closeSheet() {
    document.getElementById('global-sheet').classList.remove('is-active');
    document.body.style.overflow = 'auto';
}
function openCreate() {
    openSheet({
        title: "Tambah Guru",
        body: `
            <form id="formCreate" action="/admin/guru" method="POST">
                @csrf
                <input type="text" name="username" placeholder="Nama" class="form-input">
                <input type="password" name="password" placeholder="Password" class="form-input">
                <input type="text" name="nip" placeholder="NIP" class="form-input">
            </form>
        `,
        footer: `
            <button class="btn-cancel" onclick="closeSheet()">Cancel</button>
            <button class="btn-submit" onclick="document.getElementById('formCreate').submit()">Submit</button>
        `
    });
}
function openEdit(id, nama, nip) {
    openSheet({
        title: "Edit Guru",
        body: `
            <form id="formEdit" action="/admin/guru/${id}" method="POST">
                @csrf
                @method("PUT")
                <input type="hidden" name="_method" value="PUT">
                <input type="text" name="username" value="${nama}" class="form-input">
                <input type="password" name="password" placeholder="Password" class="form-input">
                <input type="text" name="nip" value="${nip}" class="form-input">
            </form>
        `,
        footer: `
            <button class="btn-cancel" onclick="closeSheet()">Cancel</button>
            <button class="btn-submit" onclick="document.getElementById('formEdit').submit()">Update</button>
        `
    });
}

</script>
@endsection
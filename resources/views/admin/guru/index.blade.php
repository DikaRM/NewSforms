@extends("layouts.blank")
@section("content")

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* Overlay (background gelap) */
:root {
        --primary: #3085d6;
        --primary-soft: #EEF2FF;
        --text-main: #111827;
        --text-muted: #6B7280;
        --border: #E5E7EB;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --input-height: 48px;
        --shadow-sheet: 0 -4px 30px rgba(0, 0, 0, 0.1);
        --focus-ring: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    .ft{
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    align-items: center;
    margin:20px 10px;
}
.btn-neat {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 5px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-top:20px;
        gap: 8px;
        transition: all 0.2s;
    }
     .btn-net {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 5px 24px;
        border-radius: 5px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-top:10px;
       
        transition: all 0.2s;
    }

    .btn-neat:hover,.btn-net:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(79, 70, 229, 0.4); }

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

.text-error {
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: -5px;
    display: block;
}

</style>
<div class="ft">
<button class="button is-info" style="margin:10px 2px;" onclick="openCreate()"><i class="fas fa-plus" style="margin-right:6px;"></i>Tambah Guru</button>
<form method="GET" action="">
    <div style="display:flex; gap:10px; margin-top:20px;">

        <input 
    type="text" 
    name="search" 
    value="{{ request('search') }}"
    placeholder="Cari nama / NISN..."
    class="form-input"
 style="margin-top:10px;">

        <button type="submit" class="btn-net">
            Cari
        </button>

    </div>
</form>
</div>
@if(request('search') && !request('page'))
    @if(isset($isSearching))
        @if($data->count() > 0)
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Data ditemukan',
                text: 'Ada {{ $data->count() }} hasil pencarian',
                timer: 1500,
                showConfirmButton: false
            });
        </script>
        @else
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Tidak ditemukan',
                text: 'Data tidak ditemukan!',
                confirmButtonColor: '#d33'
            });
        </script>
        @endif
    @endif
@endif
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
      <td>No</td>
      <td>Username</td>
      <td>Nama</td>
      <td>NIP</td>
      <td>More</td>
    </tr>
  </thead>
  <tbody>
   @foreach($data as $index => $s)
<tr>
  <td>{{$index + 1}}</td>
  <td>{{$s->user->username}}</td>
  <td>{{$s->nama}}</td>
  <td>{{$s->nip}}</td>
  <td>
    <div class="buttons">
      <button onclick="openEdit('{{ $s->id }}','{{ $s->nama }}','{{ $s->nip }}')" class="button is-warning is-light is-small">
    <i class="fas fa-edit" style="margin-right:6px;"></i>Edit
</button>
    <form action="{{route('admin.guru.destroy',$s->id)}}" method="post">
      @csrf
      @method("DELETE")
      <button type="button"
    class="button is-danger is-small btn-delete"
    data-id="{{$s->id}}">
    <i class="fas fa-trash" style="margin-right:6px;"></i>Hapus
</button>
    </form>
    </div>
    
  </td>
</tr>

<!-- Modal Edit - Gunakan ID yang konsisten -->

@endforeach
  </tbody>
</table>
<div style="margin-top:20px;">
    {{ $data->links() }}
</div>
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
                <input type="password" name="password" id="password" placeholder="Password" class="form-input">
                <small id="password-error" class="text-error"></small>
                <input type="text" name="nip" id="nip" placeholder="NIP" class="form-input">
                <small id="nip-error" class="text-error"></small>
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

                <input type="text" name="username" value="${nama}" class="form-input">

                <input type="password" id="password" name="password" placeholder="Password" class="form-input">
                <small id="password-error" class="text-error"></small>

                <input type="text" id="nip" name="nip" value="${nip}" class="form-input">
                <small id="nip-error" class="text-error"></small>
            </form>
        `,
        footer: `
            <button class="btn-cancel" onclick="closeSheet()">Cancel</button>
            <button class="btn-submit" onclick="document.getElementById('formEdit').submit()">Update</button>
        `
    });
}

</script>
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        let form = this.closest('form');

        Swal.fire({
            title: 'Yakin?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
<script>
document.addEventListener('input', function(e) {

    // VALIDASI NIP
    if (e.target.name === 'nip') {
        let value = e.target.value;
        let error = e.target.nextElementSibling;

        if (value.length !== 18) {
            error.innerText = "NIP harus 18 digit";
            e.target.style.borderColor = "red";
        } else {
            error.innerText = "";
            e.target.style.borderColor = "#ddd";
        }
    }

    // VALIDASI PASSWORD
    if (e.target.name === 'password') {
        let value = e.target.value;
        let error = e.target.nextElementSibling;

        if (value.length < 6) {
            error.innerText = "Password minimal 6 karakter";
            e.target.style.borderColor = "red";
        } else {
            error.innerText = "";
            e.target.style.borderColor = "#ddd";
        }
    }

});
</script>
@endsection
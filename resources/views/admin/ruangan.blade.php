@extends("layouts.blank")
@section("content")

<!-- SweetAlert2 CSS/JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Styles untuk Bottom Sheet -->
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
        cursor: pointer;
    }

    /* Header */
    .sheet-header {
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }

    .sheet-title {
        font-weight: bold;
        font-size: 1.1rem;
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
        cursor: pointer;
        font-weight: 600;
    }

    .btn-cancel {
        flex: 1;
        background: #d33;
        border: none;
        padding: 10px;
        color: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }

    /* Input biar rapi */
    .form-group {
        margin-bottom: 15px;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-size: 0.9rem;
        color: #444;
        font-weight: 600;
    }
    .form-input {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        background: #fff;
    }
</style>

<!-- Session Alerts -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
        timer: 1500,
        showConfirmButton: false
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

<!-- Tombol Tambah -->
<button class="button is-info" style="margin:10px 2px;" onclick="openCreateRuangan()">
    <i class="fas fa-plus"></i> Tambah Ruangan
</button>

<!-- Tabel Data -->
<table class="table is-stripped is-fullwidth is-hoverable">
  <thead>
    <tr>
      <th style="width: 50px;">ID</th>
      <th>Nama Ruangan</th>
      <th>Kode</th>
      <th style="text-align: right;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($dat as $d)
   <tr>
     <td>{{ $d->id }}</td>
     <td>{{ $d->nama_ruang }}</td>
     <td>{{ $d->kode }}</td>
     <td>
       <div class="buttons is-right" style="justify-content: flex-end;">
         <!-- Tombol Edit -->
         <button class="button is-warning is-light is-small" 
                 onclick="openEditRuangan('{{ $d->id }}','{{ $d->nama_ruang }}','{{ $d->kode }}')">
            <i class="fas fa-edit"></i> Edit
         </button>
         
         <!-- Tombol Hapus -->
         <form action="{{ route('admin.delete-ruangan', $d->id) }}" method="post" 
               onsubmit="return confirm('Yakin ingin menghapus ruangan ini?');">
           @csrf
           @method("DELETE")
           <button class="button is-danger is-small" type="submit">
             <i class="fas fa-trash"></i> Hapus
           </button>
         </form>
       </div>
     </td>
   </tr>
   @endforeach
  </tbody>
</table>

<!-- Global Bottom Sheet -->
<div class="overlay" id="global-sheet">
    <div class="bottom-sheet">
        <div class="sheet-handle" onclick="closeSheet()"></div>

        <div class="sheet-header">
            <h3 class="sheet-title" id="sheet-title">Title</h3>
            <button class="delete is-medium" onclick="closeSheet()"></button>
        </div>

        <div class="sheet-body" id="sheet-body">
            <!-- Isi dynamic form -->
        </div>

        <div class="sheet-footer" id="sheet-footer">
            <!-- Tombol dynamic -->
        </div>
    </div>
</div>


<script>
    // 1. Fungsi Buka Sheet
    function openSheet({ title, body, footer }) {
        document.getElementById('sheet-title').innerHTML = title;
        document.getElementById('sheet-body').innerHTML = body;
        document.getElementById('sheet-footer').innerHTML = footer;

        document.getElementById('global-sheet').classList.add('is-active');
        document.body.style.overflow = 'hidden';
    }

    // 2. Fungsi Tutup Sheet
    function closeSheet() {
        document.getElementById('global-sheet').classList.remove('is-active');
        document.body.style.overflow = 'auto';
    }

    // 3. Logika Tambah Ruangan (Create)
    function openCreateRuangan() {
        openSheet({
            title: "Tambah Ruangan",
            body: `
                <form id="formCreate" action="{{ route('admin.tambah-ruangan') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="nama_ruang" class="form-input" placeholder="Contoh: Ruang Teori 1 PPLG" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" name="kode" class="form-input" placeholder="Contoh: RT-PPLG-01" required autocomplete="off">
                    </div>
                    <!-- Input ID (Nomor Ruangan) DIHAPUS sesuai permintaan -->
                </form>
            `,
            footer: `
                <button class="btn-cancel" onclick="closeSheet()">Batal</button>
                <button class="btn-submit" onclick="document.getElementById('formCreate').submit()">Simpan</button>
            `
        });
    }

    // 4. Logika Edit Ruangan (Update)
    function openEditRuangan(id, nama, kode) {
        openSheet({
            title: "Edit Ruangan",
            body: `
                <form id="formEdit" action="/admin/ruangan/${id}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="nama_ruang" value="${nama}" class="form-input" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" name="kode" value="${kode}" class="form-input" required autocomplete="off">
                    </div>
                </form>
            `,
            footer: `
                <button class="btn-cancel" onclick="closeSheet()">Batal</button>
                <button class="btn-submit" onclick="document.getElementById('formEdit').submit()">Update</button>
            `
        });
    }

    // Menutup sheet jika klik di area overlay
    document.querySelector('.overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSheet();
        }
    });
</script>
@endsection
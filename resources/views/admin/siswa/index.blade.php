@extends("layouts.blank")
@section("content")

<!-- 1. LOAD LIBRARIES (Font & SweetAlert) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- 2. CUSTOM STYLE (Desain Rapi & Proporsional) -->
<style>
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

    /* Reset Font agar tajam */
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* --- Tombol Trigger Utama (Buat Data) --- */
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
        padding: 12px 24px;
        border-radius: 5px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-top:10px;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-neat:hover,.btn-net:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(79, 70, 229, 0.4); }

    /* --- Tombol Edit (Di Tabel) --- */
    .btn-edit-table {
        background-color: #F3F4F6;
        color: #374151;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }
    .btn-edit-table:hover { background-color: #E5E7EB; }

    /* --- Bottom Sheet Structure --- */
    .overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        z-index: 1000; /* Di atas segalanya */
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .overlay.is-active {
        opacity: 1;
        visibility: visible;
    }

    .bottom-sheet {
        width: 100%;
        max-width: 500px;
        background: #FFFFFF;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        box-shadow: var(--shadow-sheet);
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .overlay.is-active .bottom-sheet {
        transform: translateY(0);
    }

    .sheet-handle {
        width: 40px;
        height: 4px;
        background-color: #D1D5DB;
        border-radius: 2px;
        margin: 12px auto 16px;
        flex-shrink: 0;
    }

    /* --- Header Sheet --- */
    .sheet-header {
        padding: 0 24px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
    }
    .sheet-title { font-size: 1.25rem; font-weight: 700; color: var(--text-main); }
    .btn-close { background: none; border: none; color: var(--text-muted); cursor: pointer; font-weight: 600; }

    /* --- Form Body --- */
    .sheet-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    .input-wrapper { position: relative; display: flex; align-items: center; margin-bottom: 16px; }
    
    .input-icon {
        position: absolute; left: 16px; color: var(--text-muted); pointer-events: none;
        display: flex; align-items: center; justify-content: center;
    }

    .form-input, .form-select {
        width: 100%; height: var(--input-height); padding: 0 16px 0 44px;
        font-size: 0.95rem; color: var(--text-main); border: 1px solid var(--border);
        border-radius: var(--radius-md); outline: none; transition: all 0.2s;
    }

    .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; background-size: 16px; padding-right: 40px; }

    .form-input:focus, .form-select:focus { border-color: var(--primary); box-shadow: var(--focus-ring); }
    .form-input:focus + .input-icon, .input-wrapper:focus-within .input-icon { color: var(--primary); }

    /* --- Footer Sticky --- */
    .sheet-footer {
        padding: 16px 24px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-top: 1px solid var(--border);
        display: flex; gap: 12px;
    }

    .btn-submit {
        flex: 1; height: 48px; background-color: var(--primary); color: white;
        border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;
    }
    .btn-cancel {
        flex: 1; height: 48px; background-color: #F3F4F6; color: #374151;
        border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;
    }
    .btn-cancel:hover { background-color: #E5E7EB; }

    /* Tabel Bulma sedikit disesuaikan agar rapi */
    .table th { color: #6B7280; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
    .table td { vertical-align: middle; }
    .text-error {
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: -5px;
    display: block;
}

.ft{
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    align-items: center;
    margin:20px 10px;
}
</style>

<!-- 3. SWEETALERT LOGIC (Tetap dipertahankan) -->
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#4F46E5' });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#d33' });
</script>
@endif
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
@error('password')
<script>
Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Password Minimal 6', confirmButtonColor: '#d33' });
</script>
@enderror
@error('nisn')
<script>
Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Nisn Harus 10', confirmButtonColor: '#d33' });
</script>
@enderror


<!-- 4. CONTENT UTAMA -->
<!-- Tombol Trigger Baru -->
 <div class="ft">
    <button class="btn-neat" onclick="openSheet('modal-create')">
    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    Tambah Data Siswa
</button>
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


<!-- MODAL CREATE (Bottom Sheet) -->
<div class="overlay" id="modal-create">
    <div class="bottom-sheet">
        <div class="sheet-handle" onclick="closeSheet('modal-create')"></div>
        <div class="sheet-header">
            <h3 class="sheet-title">Tambah Siswa</h3>
            <button class="btn-close" onclick="closeSheet('modal-create')">Batal</button>
        </div>
        
        <form action="{{route('admin.siswa.store')}}" method="post">
            @csrf
            <div class="sheet-body">
                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg></span>
                    <input type="number" class="form-input" placeholder="Nomer Absen" name="nomor_absen" required>
                </div>
                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg></span>
                    <input type="text" class="form-input" placeholder="Nama Lengkap" name="nama" required>
                </div>

                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg></span>
                    <input type="password" class="form-input" placeholder="Password" name="password">
                </div>
                <small id="password-error" class="text-error"></small>

                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg></span>
                    <input type="number" class="form-input" placeholder="NISN (10 Digit)" minlength="10" maxlength="10" name="nisn" required>
                </div>
                <small id="nisn-error" class="text-error"></small>
                <div class="input-wrapper">
    <span class="input-icon">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
        </svg>
    </span>

    <select class="form-select" name="jenis_kelamin" required>
        <option value="">Jenis Kelamin</option>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select>
</div>
                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg></span>
                    <select class="form-select" name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $kl)
                        <option value="{{$kl->id}}">{{$kl->nama_kelas}}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="sheet-footer">
                <button type="button" class="btn-cancel" onclick="closeSheet('modal-create')">Batal</button>
                <button type="submit" class="btn-submit">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- TABEL DATA -->
 
 <div id="siswa-table">
<table class="table is-stripped is-fullwidth is-hoverable" style="margin-top: 20px;">
  <thead>
    <tr>
      <td style="width: 50px;">No</td>
      <td>Nomer Absen</td>
      <td>Username</td>
      <td>Nama Lengkap</td>
      <td>Jenis Kelamin</td>
      <td>NISN</td>
      <td>Kelas</td>
      <td>Aksi</td>
    </tr>
  </thead>
<tbody id="table-body">
    @foreach($data as $index => $d )
    <tr>
      <td>{{$index + 1}}</td>
      @if($d->nomor_absen)
        <td>{{$d->nomor_absen}}</td>
      @else
        <td>-</td>
      @endif
      <td>{{$d->user->username}}</td>
      <td>{{$d->nama}}</td>
      <td>
    @if($d->jenis_kelamin == 'L')
        <span class="tag is-primary is-light">Laki-laki</span>
    @else
        <span class="tag is-danger is-light">Perempuan</span>
    @endif
</td>
      <td>{{$d->nisn}}</td>
      <td><span class="tag is-info is-light">{{$d->kelas->nama_kelas}}</span></td>
      <td>
        <div style="display: flex; gap: 8px;">
            <!-- Trigger Edit -->
            <button 
    class="button is-warning is-light is-small btn-edit"
    data-id="{{$d->id_siswa}}"
    data-nama="{{$d->nama}}"
    data-absen="{{$d->nomor_absen}}"
    data-nisn="{{$d->nisn}}"
    data-jk="{{$d->jenis_kelamin}}"
>
    <i class="fas fa-edit" style="margin-right:6px;"></i>Edit
</button>
            
            <!-- Trigger Delete (Biar rapi pakai form biasa) -->
            <form action="{{route('admin.siswa.destroy',$d->id_siswa)}}"  method="post" style="display: inline;">
                @csrf
                @method("DELETE")
                <button type="button"
    class="button is-danger is-small btn-delete"
    data-id="{{$d->id_siswa}}">
   <i class="fas fa-trash" style="margin-right:6px;"></i> Hapus
</button>
            </form>
        </div>
      </td>
    </tr>

    <!-- MODAL EDIT (Looping untuk setiap siswa) -->
    
    @endforeach
  </tbody>
</table>
<div class="overlay" id="modal-edit">
    <div class="bottom-sheet">
        <div class="sheet-handle" onclick="closeSheet('modal-edit')"></div>

        <div class="sheet-header">
            <h3 class="sheet-title" id="edit-title">Edit Siswa</h3>
            <button class="btn-close" onclick="closeSheet('modal-edit')">Tutup</button>
        </div>

        <form id="edit-form" method="post">
            @csrf
            @method("PUT")

            <div class="sheet-body">

                <div class="input-wrapper">
                    <span class="input-icon">#</span>
                    <input type="number" class="form-input" name="nomor_absen" id="edit-absen" required>
                </div>

                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg></span>
                    <input type="text" class="form-input" name="nama" id="edit-nama" required>
                </div>

                <div class="input-wrapper">
                    <span class="input-icon"><svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg></span>
                    <input type="text" class="form-input" name="password" placeholder="Kosongkan jika tidak diubah">
                </div>

                <div class="input-wrapper">
                    <span class="input-icon">ID</span>
                    <input type="number" class="form-input" name="nisn" id="edit-nisn" required>
                </div>

                <div class="input-wrapper">
                    <span class="input-icon">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
        </svg>
    </span> 
                    <select class="form-select" name="jenis_kelamin" id="edit-jk">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

            </div>

            <div class="sheet-footer">
                <button type="button" class="btn-cancel" onclick="closeSheet('modal-edit')">Batal</button>
                <button type="submit" class="btn-submit">Update</button>
            </div>
        </form>
    </div>
</div>
<div style="margin-top:20px;">
    {{ $data->links() }}
</div>
    </div>
<!-- 5. JAVASCRIPT FUNGSI POPUP -->
<script>
    
    function openSheet(id) {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.classList.add('is-active');
            // Matikan scroll body belakang
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSheet(id) {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.classList.remove('is-active');
            // Hidupkan scroll body belakang
            document.body.style.overflow = 'auto';
        }
    }

    // Tutup jika klik area gelap (overlay)
    document.querySelectorAll('.overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeSheet(overlay.id);
            }
        });
    });
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

    // VALIDASI NISN
    if (e.target.name === 'nisn') {
        let value = e.target.value;
        let error = e.target.closest('.input-wrapper').nextElementSibling;

        if (value.length !== 10) {
            error.innerText = "NISN harus 10 digit";
            e.target.style.borderColor = "red";
        } else {
            error.innerText = "";
            e.target.style.borderColor = "#ddd";
        }
    }

    // VALIDASI PASSWORD
    if (e.target.name === 'password') {
        let value = e.target.value;
        let error = e.target.closest('.input-wrapper').nextElementSibling;

        // KHUSUS EDIT: boleh kosong
        if (value.length > 0 && value.length < 6) {
            error.innerText = "Password minimal 6 karakter";
            e.target.style.borderColor = "red";
        } else {
            error.innerText = "";
            e.target.style.borderColor = "#ddd";
        }
    }

});
</script>
<script>
document.addEventListener('click', function(e) {

    const btn = e.target.closest('.btn-edit');
    if (!btn) return;

    // ambil data dari tombol
    let id = btn.dataset.id;
    let nama = btn.dataset.nama;
    let absen = btn.dataset.absen;
    let nisn = btn.dataset.nisn;
    let jk = btn.dataset.jk;

    // isi ke form
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-absen').value = absen;
    document.getElementById('edit-nisn').value = nisn;
    document.getElementById('edit-jk').value = jk;

    // set action form
    document.getElementById('edit-form').action = `/admin/siswa/${id}`;

    // ubah title
    document.getElementById('edit-title').innerText = "Edit: " + nama;

    // buka modal
    openSheet('modal-edit');
});
</script>

@endsection
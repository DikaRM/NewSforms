@extends("layouts.blank")
@section("content")

<!-- Styles (Dari view Guru) -->
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
    .form-input, .form-select {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        background: #fff;
    }
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
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

</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toast / Alert Session -->
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
<div class="ft">
<button class="button is-info" style="margin:10px 2px;" onclick="openCreate()">
    <i class="fas fa-plus" style="margin-right:6px;"></i> Tambah Kelas
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
@if(request('search') && !request('page'))
    @if(isset($isSearching))
        @if($dat->count() > 0)
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Data ditemukan',
                text: 'Ada {{ $dat->count() }} hasil pencarian',
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
<!-- Tombol Tambah -->


<!-- Tabel Data -->
<table class="table is-fullwidth is-hoverable is-stripped">
  <thead>
    <tr>
      <th style="width: 50px;">ID</th>
      <th>Nama Kelas</th>
      <th>Nama Ruangan</th>
      <th style="text-align: right;">Aksi</th>
    </tr>
  </thead>
  <tbody>
   @foreach($dat as $d)
   <tr>
      <td>{{ $d->id }}</td>
      <td>
          <strong>{{ $d->nama_kelas }}</strong>
      </td>
      <td>
          {{-- Menampilkan nama ruangan dari relasi --}}
          @if(isset($d->ruangan))
              <span class="tag is-info is-light">{{ $d->ruangan->nama_ruang }}</span>
          @else
              <span class="tag is-warning is-light">Belum ada ruangan</span>
          @endif
      </td>
      <td style="text-align: right;">
        <div class="buttons is-right">
            {{-- Tombol Edit memanggil fungsi openEdit dengan ID, Nama, dan RuanganID --}}
            <button onclick="openEdit('{{ $d->id }}','{{ $d->nama_kelas }}','{{ $d->ruangan_id ?? '' }}')" class="button is-warning is-light is-small">
                <i class="fas fa-edit" style="margin-right:6px;" ></i> Edit
            </button>
            
            {{-- Form Delete --}}
            <form action="{{route('admin.let', $d->id)}}" method="post" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                @csrf
                @method("DELETE")
                <button type="button"
    class="button is-danger is-small btn-delete"
    data-id="{{$d->id}}">
     <i class="fas fa-trash" style="margin-right:6px;"></i> Hapus
</button>
                   
                </button>
            </form>
        </div>
      </td>
    </tr>
   @endforeach
  </tbody>
</table>
<div style="margin-top:20px;">
    {{ $dat->links() }}
</div>

<!-- Global Bottom Sheet -->
<div class="overlay" id="global-sheet">
    <div class="bottom-sheet">
        <div class="sheet-handle" onclick="closeSheet()"></div>

        <div class="sheet-header">
            <h3 class="sheet-title" id="sheet-title">Title</h3>
            <button class="delete is-small" onclick="closeSheet()"></button>
        </div>

        <div class="sheet-body" id="sheet-body">
            <!-- Isi dynamic form -->
        </div>

        <div class="sheet-footer" id="sheet-footer">
            <!-- Tombol dynamic -->
        </div>
    </div>
</div>

<!-- Script Logic -->
<script>
    // 1. Passing data ruangan (Siswa/Ruang) dari PHP ke JavaScript
    // Asumsi $siswa adalah data ruangan yang akan dijadikan option select
    const ruangData = @json($siswa ?? []);
const allRuangData = @json($allRuangan ?? []);

    // Fungsi helper untuk generate option select
    function getRuanganOptions(selectedId = null) {
    let data = ruangData;

    // kalau EDIT → pastikan ruangan yang dipakai tetap ada
    if (selectedId) {
        const exists = ruangData.find(r => r.id == selectedId);

        if (!exists) {
            const tambahan = allRuangData.find(r => r.id == selectedId);
            if (tambahan) {
                data = [...ruangData, tambahan];
            }
        }
    }

    let options = '<option value="">Pilih Ruangan</option>';

    data.forEach(ruang => {
        const selected = (ruang.id == selectedId) ? 'selected' : '';
        options += `<option value="${ruang.id}" ${selected}>${ruang.nama_ruang}</option>`;
    });

    return options;
}

    // 2. Fungsi Buka Sheet
    function openSheet({ title, body, footer }) {
        document.getElementById('sheet-title').innerHTML = title;
        document.getElementById('sheet-body').innerHTML = body;
        document.getElementById('sheet-footer').innerHTML = footer;

        document.getElementById('global-sheet').classList.add('is-active');
        document.body.style.overflow = 'hidden'; // Mencegah scroll background
    }

    // 3. Fungsi Tutup Sheet
    function closeSheet() {
        document.getElementById('global-sheet').classList.remove('is-active');
        document.body.style.overflow = 'auto';
    }

    // 4. Logika Tambah (Create)
    function openCreate() {
        openSheet({
            title: "Tambah Kelas",
            body: `
                <form id="formCreate" action="{{ route('admin.tambah') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-input" placeholder="Contoh: XI PPLG 1" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Pilih Ruangan</label>
                        <select name="ruangan_id" class="form-select" required>
                            ${getRuanganOptions()}
                        </select>
                    </div>
                </form>
            `,
            footer: `
                <button class="btn-cancel" onclick="closeSheet()">Batal</button>
                <button class="btn-submit" onclick="document.getElementById('formCreate').submit()">Simpan</button>
            `
        });
    }

    // 5. Logika Edit (Update)
    // Parameter: id, nama_kelas, ruangan_id
    function openEdit(id, nama, ruangan_id) {
        openSheet({
            title: "Edit Kelas",
            body: `
                <form id="formEdit" action="{{ route('admin.date', ':id:') }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" value="${nama}" class="form-input" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Pilih Ruangan</label>
                        <select name="ruangan_id" class="form-select" required>
                            ${getRuanganOptions(ruangan_id)}
                        </select>
                    </div>
                </form>
            `,
            footer: `
                <button class="btn-cancel" onclick="closeSheet()">Batal</button>
                <button class="btn-submit" onclick="submitEdit('${id}')">Update</button>
            `
        });

        // Fix URL action dinamis untuk edit form
        document.getElementById('formEdit').action = "/admin/kelas" + "/" + id;
    }
    
    // Helper untuk submit edit agar route fix terpanggil
    function submitEdit(id) {
        document.getElementById('formEdit').submit();
    }

    // Menutup sheet jika klik di area overlay (hitam)
    document.querySelector('.overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSheet();
        }
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
@endsection
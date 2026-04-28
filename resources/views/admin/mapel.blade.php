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

<!-- Tombol Tambah Mapel -->
<button class="button is-info" style="margin:10px 2px;" onclick="openCreateMapel()">
    <i class="fas fa-plus"></i> Tambah Mata Pelajaran
</button>

<!-- Daftar Mapel -->
<div class="columns is-multiline">
    @forelse($guru as $map)
    <div class="column is-4">
        <div class="card">
            <div class="card-header" style="box-shadow: none; border-bottom: 1px solid #eee;">
                <h5 class="card-header-title title is-5">
                    {{ $map->nama_mapel ?? 'Mapel' }}
                </h5>
            </div>
            
            <div class="card-content">
                <!-- Form Tambah Guru ke Mapel (Tetap Inline) -->
                <form action="{{ route('admin.built') }}" method="post" 
                      onsubmit="return cekDuplikasiGuru(event, this, {{ $map->guru->pluck('id') }})">
                    @csrf
                    <input type="hidden" name="mapel_id" value="{{ $map->id }}">
                    
                    <div class="field">
                        <label class="label is-small">Pilih Guru</label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth is-small">
                                <select name="guru_id" class="guru-select" data-mapel-id="{{ $map->id }}" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($guruList ?? [] as $g)
                                    <option value="{{ $g->id }}" 
                                        {{ $map->guru->contains('id', $g->id) ? 'disabled' : '' }}>
                                        {{ $g->nama }}
                                        {{ $map->guru->contains('id', $g->id) ? '(Sudah Ada)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="icon is-small is-left"><i class="fas fa-chalkboard-teacher"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="field mt-4">
                        <button type="submit" class="button is-info is-fullwidth is-small">
                            <span class="icon is-small"><i class="fas fa-plus"></i></span>
                            <span>Tambah Guru</span>
                        </button>
                    </div>
                </form>
                
                <!-- Daftar Guru untuk Mapel Ini -->
                <hr style="margin: 1rem 0;">
                <p class="subtitle is-6 mb-2">Daftar Guru:</p>
                @if($map->guru && $map->guru->count() > 0)
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul style="list-style: none; padding: 0;">
                            @foreach($map->guru as $guru)
                            <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed #eee;">
                                <span style="font-size: 0.9rem;">{{ $guru->nama }}</span>
                                <form action="{{ route('admin.letroy', $guru->id)}}" 
                                      method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger is-outlined is-small" 
                                            onclick="return confirm('Hapus guru ini dari mapel?')">
                                        <span class="icon is-small"><i class="fas fa-trash"></i></span>
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="has-text-centered py-2">
                        <span class="tag is-warning is-light">Belum ada guru</span>
                    </div>
                @endif
            </div>
            
            <div class="card-footer" style="border-top: 1px solid #eee;">
                <div class="card-footer-item">
                    <div class="buttons are-small" style="width: 100%; justify-content: space-between;">
                        <!-- Tombol Edit (Panggil Bottom Sheet) -->
                        <button class="button is-warning is-light" 
                                onclick="openEditMapel('{{ $map->id }}', '{{ $map->nama_mapel }}')"
                                style="flex: 1;">
                            <span class="icon"><i class="fas fa-edit"></i></span>
                            <span>Edit</span>
                        </button>
                        
                        <!-- Form Delete Mapel -->
                        <form action="{{ route('admin.letroy', $map->id) }}" method="post" style="flex: 1;">
                            @csrf
                            @method("DELETE")
                            <button class="button is-danger is-outlined" 
                                    type="submit" 
                                    style="width: 100%;"
                                    onclick="return confirm('Yakin ingin menghapus mapel ini beserta relasinya?')">
                                <span class="icon"><i class="fas fa-trash"></i></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="column is-12">
        <div class="notification is-warning has-text-centered">
            Belum ada data mata pelajaran.
        </div>
    </div>
    @endforelse
</div>

<!-- Global Bottom Sheet Container -->
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

    // 3. Logika Tambah Mapel (Create)
    function openCreateMapel() {
        openSheet({
            title: "Tambah Mata Pelajaran",
            body: `
                <form id="formCreate" action="{{ route('admin.made') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" class="form-input" placeholder="Contoh: PJOK" required autocomplete="off">
                    </div>
                </form>
            `,
            footer: `
                <button class="btn-cancel" onclick="closeSheet()">Batal</button>
                <button class="btn-submit" onclick="document.getElementById('formCreate').submit()">Simpan</button>
            `
        });
    }

    // 4. Logika Edit Mapel (Update)
    function openEditMapel(id, nama) {
        openSheet({
            title: "Edit Mata Pelajaran",
            body: `
                <form id="formEdit" action="{{ route('admin.built', '') }}/${id}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="field">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" value="${nama}" class="form-input" required autocomplete="off">
                    </div>
                </form>
            `,
            footer: `
                <button class="btn-cancel" onclick="closeSheet()">Batal</button>
                <button class="btn-submit" onclick="document.getElementById('formEdit').submit()">Update</button>
            `
        });
    }

    // 5. Logika Cek Duplikasi Guru (Pertahankan dari kode asli)
    function cekDuplikasiGuru(event, form, existingGuruIds) {
        const selectElement = form.querySelector('select[name="guru_id"]');
        const selectedGuruId = parseInt(selectElement.value);
        const selectedGuruName = selectElement.options[selectElement.selectedIndex].text;
        
        // Cek apakah guru sudah ada
        if (existingGuruIds.includes(selectedGuruId)) {
            event.preventDefault();
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menambahkan!',
                text: `Guru "${selectedGuruName}" sudah terdaftar di mapel ini!`,
                confirmButtonColor: '#d33'
            });
            
            return false;
        }
        
        // Konfirmasi sebelum menambah
        event.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: `Tambahkan "${selectedGuruName}" ke mapel ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        
        return false;
    }

    // Menutup sheet jika klik di area overlay
    document.querySelector('.overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSheet();
        }
    });
</script>
@endsection
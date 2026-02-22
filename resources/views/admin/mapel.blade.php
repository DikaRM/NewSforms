@extends("layouts.blank")
@section("content")

<!-- Tombol Tambah Mapel -->
<button class="button is-info" onclick="document.getElementById('mod').classList.add('is-active')">
    Tambah Mata Pelajaran
</button>

<!-- Modal Tambah Mapel -->
<div class="modal" id="mod">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <h5 class="title">Tambah Mapel</h5>
        </header>
        
        <form action="{{ route('admin.made') }}" method="post">
            @csrf
            <section class="modal-card-body"> 
                <div class="field">
                    <div class="control">
                        <input type="text" 
                               class="input" 
                               name="nama_mapel" 
                               placeholder="Contoh : PJOK (Pendidikan Jasmani dan Olahraga)"
                               required>
                    </div>
                </div>
            </section>
            
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button type="reset" 
                            class="button is-danger" 
                            onclick="document.getElementById('mod').classList.remove('is-active')">
                        Cancel
                    </button>
                    <button type="submit" class="button is-info">
                        Submit
                    </button>
                </div>
            </footer>
        </form>
    </div>
</div>

<!-- Daftar Mapel -->
<div class="columns is-multiline">
    @forelse($guru as $map)
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-header-title title is-5">
                    {{ $map->nama_mapel ?? 'Mapel' }} -- {{ $map->id }}
                </h5>
            </div>
            
            <div class="card-content">
                <!-- Form Tambah Guru ke Mapel -->
                <form action="{{ route('admin.built') }}" method="post">
                    @csrf
                    <input type="hidden" name="mapel_id" value="{{ $map->id }}">
                    
                    <div class="field">
                        <label class="label">Pilih Guru</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="guru_id" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($guruList ?? [] as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="field">
                        <button type="submit" class="button is-info is-fullwidth is-rounded">
                            Tambah Guru
                        </button>
                    </div>
                </form>
                
                <!-- Daftar Guru untuk Mapel Ini -->
                <hr>
                <p class="subtitle is-6">Daftar Guru:</p>
                @if($map->guru && $map->guru->count() > 0)
                    <ul>
                        @foreach($map->guru as $guru)
                        <li>{{ $guru->nama }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="has-text-grey">Belum ada guru</p>
                @endif
            </div>
            
            <div class="card-footer">
                <div class="card-footer-item">
                    <div class="buttons are-small">
                        <!-- Tombol Edit -->
                        <button class="button is-warning" 
                                onclick="document.getElementById('mods{{ $map->id }}').classList.add('is-active')">
                            Edit
                        </button>
                        
                        <!-- Form Delete -->
                        <form action="{{ route('admin.letroy', $map->id) }}" method="post">
                            @csrf
                            @method("DELETE")
                            <button class="button is-danger" 
                                    type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Edit Mapel -->
    <div class="modal" id="mods{{ $map->id }}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <h5 class="title is-5">Edit Mapel: {{ $map->nama_mapel }}</h5>
                <button class="delete" 
                        onclick="document.getElementById('mods{{ $map->id }}').classList.remove('is-active')">
                </button>
            </header>
            
            <form action="{{ route('admin.built', $map->id) }}" method="post">
                @csrf
                @method('PUT')
                <section class="modal-card-body">
                    <div class="field">
                        <label class="label">Nama Mapel</label>
                        <div class="control">
                            <input type="text" 
                                   class="input" 
                                   name="nama_mapel" 
                                   value="{{ $map->nama_mapel }}"
                                   required>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Tambahkan Guru</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="guru_id">
                                    <option value="">-- Pilih Guru (opsional) --</option>
                                    @foreach($guruList ?? [] as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
                
                <footer class="modal-card-foot">
                    <div class="buttons">
                        <button type="button" 
                                class="button is-danger" 
                                onclick="document.getElementById('mods{{ $map->id }}').classList.remove('is-active')">
                            Cancel
                        </button>
                        <button type="submit" class="button is-info">
                            Update
                        </button>
                    </div>
                </footer>
            </form>
        </div>
    </div>
    @empty
    <div class="column is-12">
        <div class="notification is-warning">
            Belum ada data mata pelajaran.
        </div>
    </div>
    @endforelse
</div>

@endsection
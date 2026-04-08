@extends("layouts.blank")
@section("content")

{{-- TAMPILAN TOTAL DATA --}}
<div class="columns is-multiline mb-5">
    {{-- Total Siswa --}}
    <div class="column is-2">
        <div class="card has-background-info">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $totalSiswa }}</p>
                <p class="heading has-text-white">Total Siswa</p>
            </div>
        </div>
    </div>
    
    {{-- Total Guru --}}
    <div class="column is-2">
        <div class="card has-background-success">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $totalGuru }}</p>
                <p class="heading has-text-white">Total Guru</p>
            </div>
        </div>
    </div>
    
    {{-- Total Kelas --}}
    <div class="column is-2">
        <div class="card has-background-warning">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $totalKelas }}</p>
                <p class="heading has-text-white">Total Kelas</p>
            </div>
        </div>
    </div>
    
    {{-- Total Mapel --}}
    <div class="column is-2">
        <div class="card has-background-danger">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $totalMapel }}</p>
                <p class="heading has-text-white">Total Mapel</p>
            </div>
        </div>
    </div>
    
    {{-- Total Pelanggaran --}}
    <div class="column is-2">
        <div class="card has-background-primary">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $totalPelanggaran }}</p>
                <p class="heading has-text-white">Total Pelanggaran</p>
            </div>
        </div>
    </div>
    
    {{-- Total Bank Soal --}}
    <div class="column is-2">
        <div class="card has-background-dark">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $totalBankSoal }}</p>
                <p class="heading has-text-white">Total Bank Soal</p>
            </div>
        </div>
    </div>
</div>

{{-- STATUS UJIAN --}}
<div class="columns is-multiline mb-5">
    <div class="column is-4">
        <div class="card has-background-link">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $ujianReady }}</p>
                <p class="heading has-text-white">📝 Ujian Siap (Ready)</p>
            </div>
        </div>
    </div>
    
    <div class="column is-4">
        <div class="card has-background-grey">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $ujianDraft }}</p>
                <p class="heading has-text-white">📄 Ujian Draft (Belum Upload)</p>
            </div>
        </div>
    </div>
    
    <div class="column is-4">
        <div class="card has-background-success">
            <div class="card-content has-text-white">
                <p class="title has-text-white">{{ $ujianDone }}</p>
                <p class="heading has-text-white">✅ Ujian Selesai (Done)</p>
            </div>
        </div>
    </div>
</div>

{{-- TAMPILAN SORT ROLE (YANG SUDAH ADA) --}}
<div class="level is-mobile">
    <div class="level-left">
        <form method="get">
            <div class="select">
                <select name="role" class="select" onchange="this.form.submit()">
                    <option value="">Sort Role</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="pengawas">Pengawas</option>
                    <option value="admin-ops">Admin Operasional</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </form>
    </div>
    <div class="level-right">
        <button class="button is-info" onclick="document.getElementById('modals').classList.add('is-active');">Tambah Users</button>
    </div>
</div>

{{-- TABEL USERS --}}
<table class="table is-fullwidth is-hoverable is-stripped">
    <thead>
        <tr>
            <td>id</th>
            <td>Nama Lengkap</th>
            <td>Role</th>
            <td>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
        <tr>
            <td>{{$d->id}}</td>
            <td>{{$d->nama}}</td>
            <td>{{$d->role}}</td>
            <td class="buttons">
                <form action="{{route('admin.destroy',$d->id)}}" method="post">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="button is-danger" onclick="return confirm('Yakin dihapus ?')">Hapus</button>
                </form>
                <button class="button is-warning" onclick="document.getElementById('mod{{$d->id}}').classList.add('is-active')">Edit</button>
            </td>
        </tr>
        
        {{-- Modal Edit --}}
        <div class="modal" id="mod{{$d->id}}">
            <div class="modal-background is-warning"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <h5 class="title">Edit Users</h5>
                    <button class="delete" onclick="document.getElementById('mod{{$d->id}}').classList.remove('is-active')"></button>
                </header>
                <section class="modal-card-body">
                    <form action="{{route('admin.update',$d->id)}}" method="post">
                        @csrf
                        @method("PUT")
                        <div class="field">
                            <div class="control">
                                <input type="text" class="input" value="{{$d->nama}}" name="nama">
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <input type="text" name="role" class="input" value="{{$d->role}}">
                            </div>
                        </div>
                </section>
                <footer class="modal-card-foot">
                    <div class="buttons">
                        <button type="reset" class="button is-danger">Reset</button>
                        <button type="submit" class="button is-info">Update</button>
                    </div>
                    </form>
                </footer>
            </div>
        </div>
        @endforeach
    </tbody>
</table>

{{-- Modal Tambah Users (YANG SUDAH ADA) --}}
<div class="modal" id="modals">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <h5 class="title">Create Users</h5>
            <button class="delete" onclick="document.getElementById('modals').classList.remove('is-active');"></button>
        </header>
        <section class="modal-card-body">
            <form action="{{route('admin.store')}}" method="post">
                @csrf
                <div class="field">
                    <div class="control">
                        <input type="text" class="input" name="nama" placeholder="Nama Anda">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <input type="text" class="input" name="password" placeholder="Password">
                    </div>
                </div>
                <div class="field">
                    <div class="select">
                        <select name="role" id="role">
                            <option>Pilih Role Ini</option>
                            <option value="admin-ops">Admin Operasional</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
        </section>
        <footer class="modal-card-foot">
            <div class="buttons is-centered">
                <button type="submit" class="button is-info">Create</button>
                <button type="reset" class="button is-danger">Reset</button>
            </div>
            </form>
        </footer>
    </div>
</div>

<nav class="navbar has-navbar-fixed-top has-text-centered">
    <h5 class="title">By Dika</h5>
</nav>
@endsection
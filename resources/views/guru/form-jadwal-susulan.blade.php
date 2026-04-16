{{-- form-jadwal-susulan.blade.php --}}
<form action="{{ route('guru.jadwal-susulan.store') }}" method="POST">
    @csrf
    <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
    <input type="hidden" name="untuk_susulan" value="1">
    
    <div class="field">
        <label>Tanggal Susulan</label>
        <input type="date" name="tanggal" class="input" required min="{{ date('Y-m-d') }}">
    </div>
    
    <div class="field">
        <label>Waktu Mulai</label>
        <input type="time" name="waktu_mulai" class="input" required>
    </div>
    
    <div class="field">
        <label>Pengawas</label>
        <select name="pengawas_id" class="select" required>
            @foreach($pengawas as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="field">
        <label>Ruangan</label>
        <input type="text" name="ruangan" class="input" placeholder="Misal: Lab. Komputer">
    </div>
    
    <div class="field">
        <label>Keterangan</label>
        <textarea name="keterangan" class="textarea"></textarea>
    </div>
    
    <div class="field">
        <label>Daftar Siswa Susulan</label>
        @foreach($siswaSusulan as $siswa)
            <div>
                <label>
                    <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" checked disabled>
                    {{ $siswa->siswa->nama }} - {{ $siswa->alasan }}
                </label>
            </div>
        @endforeach
    </div>
    
    <button type="submit" class="button is-primary">Buat Jadwal Susulan</button>
</form>
@foreach($data as $index => $d )
    <tr>
      <td>{{$index + 1}}</td>
      @if($d->nomor_absen)
        <td>{{$d->nomor_absen}}</td>
      @else
        <td>-</td>
      @endif
      <td>{{$d->username}}</td>
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
    class="btn-edit-table btn-edit"
    data-id="{{$d->id_siswa}}"
    data-nama="{{$d->nama}}"
    data-absen="{{$d->nomor_absen}}"
    data-nisn="{{$d->nisn}}"
    data-jk="{{$d->jenis_kelamin}}"
>
    Edit
</button>
            
            <!-- Trigger Delete (Biar rapi pakai form biasa) -->
            <form action="{{route('admin.siswa.destroy',$d->id_siswa)}}"  method="post" style="display: inline;">
                @csrf
                @method("DELETE")
                <button type="button"
    class="button is-danger is-small btn-delete"
    data-id="{{$d->id_siswa}}">
    Hapus
</button>
            </form>
        </div>
      </td>
    </tr>

    <!-- MODAL EDIT (Looping untuk setiap siswa) -->
    
    @endforeach
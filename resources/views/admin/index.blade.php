<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="bulma.min.css">
</head>
<body>
  <h1 class="title">Welcome Your Admin</h1>
  <button class="button is-info"  onclick="document.getElementById('modals').classList.add('is-active');">Tambah Users</button>
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
            <div class="control">
              <input type="text" class="input" name="no_id" placeholder="Nomor Identitas Anda">
            </div>
          </div>
            <div class="field">
            <div class="control">
              <select name="role" id="role">
                <option>Pilih Role Ini</option>
                <option value="siswa">Siswa</option>
                <option value="guru">Guru</option>
                <option value="pengawas">Pengawas</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div class="field" >
            <input type="text" class="input" name="mapel" id="mapel" placeholder="Mata Pelajaran Guru">
          </div>
          <div class="field" >
            <input type="text" class="input" name="kelas" id="kelas" placeholder="Kelas :">
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
  <table class="table is-fullwidth is-hoverable is-stripped">
    <thead>
      <tr>
        <td>id</td>
        <td>Nama Lengkap</td>
        <td>Role</td>
        <td>No Identitas</td>
        <td>Aksi</td>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $d)
      <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->nama}}</td>
        <td>{{$d->role}}</td>
        <td>{{$d->no_id}}</td>
        <td class="buttons">
          <form action="{{route('admin.destroy',$d->id)}}" method="post">
            @csrf
            @method("DELETE")
            <button type="submit" class="button is-danger" onclick="confirm('Dihapus ?')">Hapus</button>
          </form>
          <button class="button is-warning" onclick="document.getElementById('mod{{$d->id}}').classList.add('is-active')">Edit</button>
        </td>
      </tr>
      <div class="modal" id="mod{{$d->id}}">
        <div class="modal-background is-warning">
        </div>
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
              <div class="field">
                <div class="control">
                  <input type="text" name="no_id" class="input" value="{{$d->no_id}}">
            @if($d->role == "siswa")
              <div class="field">
                <div class="control">
                  <input type="text" class="input" value="{{$d->kelas}}">
                </div>
              </div>
                </div>
              </div>
            @else
            <div class="field">
                <div class="control">
                  <input type="text" class="input" value="{{$d->mapel}}" name="mapel">
                </div>
              </div>
            @endif
          
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
  <script>
    const kelas = document.getElementById("kelas");
    const mapel = document.getElementById("mapel");
    kelas.style.display="none"
    mapel.style.display="none"
    const role = document.getElementById("role");
    role.onchange = () => {
      if(role.value == "siswa"){
        kelas.style.display="block"
        mapel.style.display="none"
      }
      else if(role.value == "guru"){
        mapel.style.display="block"
        kelas.style.display="none"
      }
    }
  </script>
</body>
</html>
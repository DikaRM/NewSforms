@extends("layouts.blank")
@section("content")
<button class="button is-info" onclick="document.getElementById('mod').classList.add('is-active')">Tambah Mata Pelajaran</button>
<div class="modal" id="mod">
  <div class="modal-background">
    
  </div>
  <div class="modal-card">
    <header class="modal-card-head">
      <h5 class="title">
        Tambah Mapel
      </h5>
    </header>
    <section class="modal-card-body"> 
      <form action="{{route('admin.made')}}" method="post">
        @csrf
        <div class="field">
          <div class="control">
            <input type="text" class="input" name="nama_mapel" placeholder="Contoh : PJOK(Pendidikan Jasmani dan Olahraga)">
          </div>
        </div>
    </section>
    <footer class="modal-card-foot">
      <div class="buttons">
        <button type="reset" class="button is-danger" onclick="document.getElementById('mod').classList.remove('is-active')">
          Cancel
        </button>
        <button type="submit" class="button is-info">
          Submit
        </button>
      </div>
      </form>
    </footer>
  </div>
</div>
    @foreach($dat as $d)
    <div class="card">
      <div class="card-head">
        <h5 class="title">{{$d->nama_mapel}} -- {{$d->id}}</h5>
      </div>
      <div class="card-body">
         <form action="{{route('admin.built')}}" method="post">
            @csrf
            <input type="hidden" class="input" name="mapel_id" value="{{$d->id}}" placeholder="{{$d->nama_mapel}}">
            <div class="field">
              <div class="select">
               <select name="ids" id="ids">
                 <option value="">Pilih Guru</option>
                 @foreach($guru as $gu)
                 <option value="{{$gu->id}}">{{$gu->nama}}</option>
                 
               </select>
              </div>
            </div>
            <p class="subtitle">
              Data Guru Mapels
            </p>
            @foreach($dt as $data)
            @php
            
            $ada = \App\Models\Guru::firstWhere("id",$data->guru_id);
            @endphp
            @if($ada)
            <p>{{$gu->nama}}</p>
            @endif
            @endforeach
            <button type="submit" class="b utton is-info  is-rounded">Submit</button>
            </form>
      </div>
      <div class="card-footer">
            <div class="buttons">
              @foreach($dt as $data)
        <form action="{{route('admin.letroy',$data->id)}}" method="post">
          @csrf
          @method("DELETE")
          
          <button class="button is-danger" type="submit">Delete</button>
        </form>
        @endforeach
          
          <button class="button is-warning" onclick="document.getElementById('mods{{$d->id}}').classList.add('is-active')">Edit</button>
          
        </div>
    </div>
    </div>
    <div class="modal" id="mods{{$d->id}}">
      <div class="modal-background">
        
      </div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h5 class="title">Halo</h5>
          <button class="delete" onclick="document.getElementById('mods{{$d->id}}').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <form action="{{route('admin.built',$d->id)}}" method="post">
            @csrf
            <div class="field">
              <div class="select">
               <select name="ids" id="ids">
                 <option value="">Pilih Guru</option>
                 @foreach($guru as $gu)
                 <option value="{{$gu->id}}">{{$gu->nama}}</option>
                 @endforeach
               </select>
              </div>
            </div>
        </section>
        <footer class="modal-card-foot">
          <div class="buttons">
            <button type="reset" class="button is-danger  is-inverted" onclick="document.getElementById('mods{{$d->id}}').classList.remove('is-active')">Cancel</button>
            <button type="submit" class="button is-info is-inverted">Submit</button>
          </div>
          </form>
        </footer>
      </div>
    </div>
   
    @endforeach
  </tbody>
</table>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login LMS SKANIC</title>
    <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
</head>
<body>
    <div class="container">
        <div class="box">
            <h1 class="title"># Login</h1>
            <h2 class="welcome-text">Selamat Datang<br>di LMS SKANIC</h2>
            @if(session("error"))
              <div class="notification is-danger is-light m-2">
                <div class="content has-text-light has-text-weight-bold">
                  {{session("error")}}
                </div>
              </div>
            @endif
            <div class="id-pengguna">

            </div>

            <div class="form-group">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <div class="field">
                      <div class="control">
                    <input type="text" name="nama" class="input" placeholder="Masukan ID Pengguna">
                        
                      </div>
                    </div
                    <div class="field">
                      <div class="control has-icons-right">
                        
                    <input type="password" name="password" class="input" placeholder="Masukan Password">
                      </div>
                    </div>
                    <button type="submit">Masuk</button>
                </form>
            </div>

            <div class="masuk-button">
                Masuk
            </div>
        </div>
    </div>
    
</body>
</html>
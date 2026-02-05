<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="bulma.min.css">
  <title>Login</title>
</head>
<body>
  <div class="container">
    <div class="box">
      <div class="columns has-text-centered">
        <div class="column">
          <img src="" >
          <h5 class="title">Sforms Login</h5>
        </div>
        <div class="column">
          <form action="{{route('users.store')}}" method="post">
              @csrf
              <div class="field">
                <div class="control">
                  <input type="text" class="input" name="nama" placeholder="Username">
                </div>
              </div>
              <div class="field">
                <div class="control">
                  <input type="password" class="input" name="password" placeholder="Password">
                </div>
              </div>
                <div class="buttons">
                  <button type="submit" class="button is-info is-fullwidth mt-2">Submit</button>
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
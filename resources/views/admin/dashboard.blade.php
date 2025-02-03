<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Admin dashboard</title>
    </head>
    <body>
    <h1 class="text-primary">Admin dashboard page</h1>
        <div class="">
            @if (Session::has('error'))
                <li>{{ Session::get('error') }}</li>
            @endif
            @if (Session::has('success'))
                <li>{{ Session::get('success') }}</li>
            @endif
        </div>
        <a href="{{route('admin.logout')}}" class="btn btn-success">Deconnexion</a>
    </body>
</html>

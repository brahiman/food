<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="container">
<h1 class="text-primary mb-5">Admin Reset Password Page</h1>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif
@if (Session::has('error'))
    <li>{{ Session::get('error') }}</li>
@endif
@if (Session::has('success'))
    <li>{{ Session::get('success') }}</li>
@endif

<form action="{{ route('admin.forgetpwdsubmit') }}" method="post">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <div class="mb-5">
        <label for="password" class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
    </div>

    <div class="mb-5">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
    </div>

    <div class="mb-3 form-check">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
</body>

</html>

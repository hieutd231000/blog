<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>User Login</title>
</head>
<style>
    body{
        margin: 0;
        padding: 0;
        background: linear-gradient(120deg,#2980b9, #8e44ad);
        height: 100vh;
        overflow: hidden;
    }
    .container-form{
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 10%;
    }
    form {
        border: 3px solid #f1f1f1;
        width: 35%;
        background: white;
        padding: 20px;
    }
    input[type=text], input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }
    button {
        background-color: #04AA6D;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    button:hover {
        opacity: 0.8;
    }
    .signup_link{
        margin: 15px 0 0 0;
        text-align: center;
        font-size: 16px;
        color: #666666;
    }
    .signup_link a{
        color: #2691d9;
        text-decoration: none;
    }
    .signup_link a:hover{
        text-decoration: underline;
    }
    .help-block{
        height: 10px;
        color: red;
        font-size: 14px;
    }
</style>
<body>
<div class="container-form">
    <form action="{{ url("/login") }}" method="post">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label for="email"><b>Email</b></label>
        <input type="text" placeholder="Enter Email" name="email" value="{{ old("email") }}">
        @if ($errors->has('email'))
            <div class="help-block">
                {{ $errors->first('email') }}
            </div>
            <br>
        @endif
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password">
        @if ($errors->has('password'))
            <div class="help-block">
                {{ $errors->first('password') }}
            </div>
            <br>
        @endif
        <button type="submit">Login</button>
        <div class="signup_link">
            Not a member? <a href="{{ url("/signup") }}">Signup</a>
        </div>
    </form>
</div>
</body>
</html>

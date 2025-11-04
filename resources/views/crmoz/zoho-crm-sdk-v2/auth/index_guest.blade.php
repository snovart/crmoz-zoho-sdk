<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="are-token" content="{{config('are.ext.areToken')}}">

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,800,900|Material+Icons' rel="stylesheet">

    <title>Zoho oAuth setup</title>

    <style>
        body{
            height: 100vh;
            font-family: Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(7, 71, 166);
            overflow: hidden;
        }

        form{
            background: #fff;
            flex-direction: column;
            font-size: 14px;
            display: flex;
            width: 400px;
            height: 350px;
            border-radius: 2px;
            box-shadow: 0 0 70px -15px;
            justify-content: space-between;
        }

        form i{
            font-size: 100px !important;
            margin: 10px auto 0;
            color: rgb(7, 71, 166);
            justify-content: center;
        }

        .inputs{
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-bottom: 25px;
        }

        h2{
            text-align: center;
        }

        input{
            margin: 5px 10px;
        }

        label{
            margin: 0 10px
        }

        input[type=submit]{
            width: 100px;
            margin: 10px auto;
        }
    </style>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:300' rel="stylesheet">
</head>
    <body>
        <form action="/zoho/oauth/" method="get">
            <i class="material-icons">account_circle</i>
            <h2>ZohoCRM oAuth authentication</h2>
            <div class="inputs">
                {{csrf_field()}}
                <label for="">App Verify token</label>
                <input type="text" name="token">

                <input type="submit" name="submit" value="Proceed">
            </div>
        </form>
    </body>
</html>

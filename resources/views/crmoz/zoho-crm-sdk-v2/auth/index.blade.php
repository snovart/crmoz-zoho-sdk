<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="are-token" content="{{config('are.ext.areToken')}}">

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,800,900|Material+Icons' rel="stylesheet">

    <title>Zoho OAuth Setup</title>

    <style>
        body{
            height: 100vh;
            font-family: Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(7, 71, 166);
        }

        form{
            background: #fff;
            flex-direction: column;
            font-size: 14px;
            display: flex;
            width: 400px;
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

        input,select{
            margin: 5px 10px;
        }

        select {
            width:30%;
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
<form action="/zoho/oauth" method="post">
    <i class="material-icons">account_circle</i>
    <h2>Zoho OAuth Setup</h2><br>
    <div class="inputs">

        <label>
            Open the link you need:<br>
            For US:<a href="https://accounts.zoho.com/developerconsole" target="_blank">instruction</a><br>
            For EU:<a href="https://accounts.zoho.eu/developerconsole" target="_blank">instruction</a><br>
            For IN:<a href="https://accounts.zoho.in/developerconsole" target="_blank">instruction</a><br>
            For CN:<a href="https://accounts.zoho.com.cn/developerconsole" target="_blank">instruction</a><br>
            For AU:<a href="https://accounts.zoho.com.com.ua/developerconsole" target="_blank">instruction</a><br>
            and enter the following details:
        </label><br>
        <label>
            <b>Client Name</b><br>
            YOUR_APP_NAME
        </label><br>

        <label>
            <b>Client Domain</b><br>
            {{ config('app.url') }}
        </label><br>
        <label>
            <b>Authorized Redirect URIs</b><br>
            {{ \ZohoCrmSDK\Services\AuthService::callbackUrl() }}
        </label><br>
        <label>
            After click <b>Create</b>, you will receive the following credentials.<br>
            You need copied this details to fields:
        </label><br>
        {{csrf_field()}}
        <label for="">Client ID</label>
        <input type="text" name="id">
        <label for="">Client Secret</label>
        <input type="text" name="secret">
        <label for="">Domain</label>
        <select name="host">
            <option value="com">COM</option>
            <option value="eu">EU</option>
            <option value="in">IN</option>
            <option value="au">AU</option>
            <option value="cn">CN</option>
        </select>
        <input type="hidden" name="token" value="{{$token}}">
        <input type="submit" name="submit" value="Proceed">
        <label>
            <a href="https://www.zoho.com/crm/developer/docs/api/register-client.html" target="_blank">Official documentation</a>
        </label>
    </div>
</form>
</body>
</html>

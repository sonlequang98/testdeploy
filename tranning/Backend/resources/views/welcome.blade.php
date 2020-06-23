<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
         <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Bootstrap core CSS -->
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <title>Admin</title>

        <!-- Custom styling plus plugins -->
        <!-- Bootstrap core JS -->
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/nprogress.js')}}"></script>
        <link rel="stylesheet" href="{{asset('css/quillbubble.css')}}">
        <style>
            
        </style>
        <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="app">
            <main-app></main-app>
        </div>
        <script src="{{asset('js/app.js')}}"></script>


    </body>
</html>

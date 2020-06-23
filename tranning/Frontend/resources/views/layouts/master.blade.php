<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>User</title>

    <!-- Fonts -->

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/quillbubble.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/datepicker-ja.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <style>
        html {
            font-size: 1em
        }
        body {
            background-color: white;
        }
    </style>

</head>

<body>
    <header>
        <div id="header-left">

        </div>
        <div id="header-right">
            @if (Auth::guest())
            <div id="header-menu">
                <!-- <span>ログイン</span> -->
                <button id="showformlogin">Login</button>
                <button id="showformregister">Register&nbsp;&nbsp;&nbsp;|</button>
                <!-- <span ></span> -->
            </div>
            @else
            <div id="header-menu">

                <div id="username">
                    <a id="link-user-name" href="{{route('user-profile')}}">{{ Auth::user()->name }}</a>
                </div>
                <!-- <div id="logout">

                </div> -->
            </div>

            @endif
        </div>
    </header>

    <body>
        <div id="header-search">
            <div id="logo">
                <h2>SPORT MAGAZINE</h2>
            </div>
            <div id="search">
                <form action="/posts/search" method="GET" autocomplete="off">
                    <input id="input-search" name="searchQuery" type="text" placeholder="Type here to search...">
                    <button type="submit">Search</button>
                </form>
            </div>
            <div id="setting">
                Font size
                <button class="btnfontSize" id="fontSize13px"onclick="changeFontSize('13px')">13</button>
                <button class="btnfontSize" id="fontSize15px" onclick="changeFontSize('15px')">15 </button>
                <button class="btnfontSize" id="fontSize17px" onclick="changeFontSize('17px')">17</button>
            </div>

        </div>

            <div id="menu-category">
                <ul>
                    <a href="http:\\usersite.local">HOME&nbsp;&nbsp;&nbsp;&nbsp;|</a>
                    @foreach(Frontend\Category::with('categorychilds')->whereNull('parent_id')->get() as $category)
                    <li><a href="http:\\usersite.local\categories\{{$category->id}}">{{$category->category_name}}&nbsp;&nbsp;&nbsp;&nbsp;|</a>
                        @if($category->categorychilds->count()>0)
                        <div>
                            <ul>
                                @foreach($category->categorychilds as $categorychild)
                                <li> <a href="http:\\usersite.local\categories\{{$categorychild->id}}">{{$categorychild->category_name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </li>

                    @endforeach
                </ul>
            </div>

        <div id="menu-global">
            <ul>
                @foreach(Frontend\Category::paginate(7)->where('global_display',1) as $category)
                <li><a href="http:\\usersite.local\categories\{{$category->id}}">{{$category->category_name}}&nbsp;&nbsp;&nbsp;&nbsp;|</a></li>
                @endforeach
            </ul>
        </div>
        <div></div>

        <div id="block-form-register">
        <div id="form-register">
            <div id="form-header">
                <div id="title">
                    <span>Register</span>
                </div>
                <div id="btnClose">
                    <button>
                        Close
                    </button>
                </div>
            </div>

            <div id="form-body">
                <form action="{{route('register')}}" method="POST"
                    onsubmit="return  checkValidateRegister()" autocomplete="off">
                    @csrf
                    <p id="fail-register" class="alert alert-danger" ></p>
                    <table>
                        <tr>
                            <td><label for="">Email
                                </label></td>
                            <td><input name="email" class="input" type="text" placeholder="Enter your email
                                        "></td>

                        </tr>
                        <tr>
                            <td></td>
                            <td><span id="email-error" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Password
                                </label>
                            </td>
                            <td><input name="password" class="input" type="password" placeholder="Enter your password
                                        "></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><span id="password-error" class="error"></span></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="">Username
                                </label>
                            </td>
                            <td><input name="name" class="input" type="text" placeholder="Enter your user name
                                            "></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><span id="name-error" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Date of birth
                                </label>
                            </td>
                            <td>
                                <input name="dob" placeholder="Enter your birthday" id="registerDate" class="form-control" type="text">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><span id="dob-error" class="error"></span></td>
                        </tr>
                        <tr>
                        <tr>
                            <td><label for="">Sex
                                </label></td>
                            <td>
                                Male<input checked="checked" type="radio" name="gender" value="M">
                                Female<input type="radio" name="gender" value="FM">
                            </td>
                        </tr>
                    </table>
                    <button type="submit" id="btnSave">Submit</button>

                </form>
                <button id="btnCancel">Cancel</button>
            </div>


        </div>
        </div>

        <div id="block-form-login">
        <div id="form-login">
            <div id="login-header">

                <div id="title">
                    <span>
                    Login
                    </span>

                </div>
                <div id="btnCloseLogin">
                    <button id="btnCloseFormLogin">
                        Close
                    </button>

                </div>
            </div>

            <div id="login-body">
                <form action="{{route('login')}}" method="POST" onsubmit="return checkValidationLogin()" autocomplete="off">
                    @csrf
                    <p id="fail-login" class="alert alert-danger"></p>
                    <table>
                        <tr>
                            <td><label for="">Email
                                </label></td>
                            <td><input id="email-login" name="email" class="input" type="text" placeholder="Enter your email
                                                "></td>

                        </tr>
                        <tr>
                            <td></td>
                            <td><span id="email-login-error" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Password
                                </label>
                            </td>
                            <td><input id="password-login" name="password" class="input" type="password" placeholder="Enter your password
                                                "></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><span id="password-login-error" class="error"></span></td>
                        </tr>
                    </table>
                    <button type="submit" id="btnSave">Login</button>

                </form>
                <button id="btnCancelLogin">Cancel</button>
            </div>

        </div>
        </div>
    </body>

    <div id="content">
        @yield('content')
    </div>
    <div id="footer">
       <p> Copyrights © 2019 <a href="http:\\adminsite.local">Duy Tan University</a>.</p>
    </div>


    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{asset('/js/master-page.js')}}"></script>

    @if(Session::has('failedRegister'))
        <script>
            $('#block-form-register').show();
            $('#fail-register').css('display','block');
            $('#fail-register').text('{{Session::get('failedRegister')}}');
            setTimeout(function() {
                $('#fail-register').remove();
            }, 3000);
        </script>
    @endif
    @if(Session::has('failedLogin'))
        <script>
            $('#block-form-login').show();
            $('#fail-login').css('display','block');
            $('#fail-login').text('{{Session::get('failedLogin')}}');
            setTimeout(function() {
                $('#fail-login').remove();
            }, 3000);
        </script>
    @endif

</body>

</html>

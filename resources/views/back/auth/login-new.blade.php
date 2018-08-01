<!DOCTYPE html>
<html dir=ltr>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>登录</title>
    <!-- Custom CSS -->
    <link href="/back/css/login.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/back/css/libs/font-awesome.css"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="main-wrapper">

    <div class="preloader" style="display: none;">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
         style="background:url(/back/img/auth-bg.jpg) no-repeat center center;">
        <div class="auth-box">
            <div id="loginform">
                <div class="logo">
                    <span class="db"><img  src="/back/img/logo-icon.png" alt="logo"></span>
                    <h5 class="font-medium m-b-20">登录后台</h5>
                </div>
                <!-- Form -->
                <div class="row">
                    <div class="col-12">
                        <form class="form-horizontal m-t-20" id="loginform"  method="POST" action="{{ route('admin.login') }}">
                            {{ csrf_field() }}
                            <div class="input-group mb-3 {{ $errors->has('username') ? ' has-error' : '' }}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                                </div>

                                <input type="text" class="form-control form-control-lg" name="username" placeholder="账号"
                                       value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="input-group mb-3 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-pencil"></i></span>
                                </div>
                                <input id="password" type="password" class="form-control form-control-lg" placeholder="密码"
                                       name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">

                                        <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i
                                                    class="fa fa-lock m-r-5"></i>忘记密码</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20">
                                    <button class="btn btn-block btn-lg btn-info" type="submit">登录</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!--重置密码-->
            <div id="recoverform">
                <div class="logo">
                    <span class="db"><img src=""  alt="logo"></span>
                    <h5 class="font-medium m-b-20">重置密码</h5>
                    <span>输入您注册时的邮箱地址!</span>
                </div>
                <div class="row m-t-20">
                    <!-- Form -->
                    <form class="col-12" action="">
                        <!-- email -->
                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control form-control-lg" type="email" required=""
                                       placeholder="用户名">
                            </div>
                        </div>
                        <!-- pwd -->
                        <div class="row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-block btn-lg btn-danger" type="submit" name="action">重置
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
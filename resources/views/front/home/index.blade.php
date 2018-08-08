@extends('front.layouts.home')

@section('title', ' - 实名认证')

@section('css')
    <style>
        .main .bg {
            width: 100%;
            height: 350px;
        }
        .main .bg img {
            width: 100%;
            height: 100%;
        }
        .main .container {
            margin-top: 100px;
            height: 500px;
            background: url("/front/images/index_2.png") top center no-repeat;
        }

    </style>
@endsection


@section('main')
    <div class="main f-cb">
        <div class="bg">
            <img src="/front/images/index_banner.jpg" alt="">
        </div>
        <div class="container">

        </div>
    </div>
@endsection
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/js/jquery-1.11.0.min.js"></script>
<script>
    layui.use(['element'], function () {
        var element = layui.element;
    })
</script>
@section('js')
@endsection
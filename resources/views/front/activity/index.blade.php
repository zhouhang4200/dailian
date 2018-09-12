@extends('front.layouts.home')

@section('title', ' - 活动中心')

@section('css')
    <link rel="stylesheet" href="/front/css/about-us.css">
    <style>
        .container{
            text-align: center;
        }
        .container p{
            font-size: 18px;
            color: #666;
            margin-top: 20px;
        }
    </style>
@endsection

@section('main')
    <div class="main f-cb">
        <div class="container">
            <img src="/front/images/activity.jpg" alt="">
            <p>暂无活动，敬请期待</p>
        </div>
    </div>
@endsection

@section('js')
@endsection


@extends('front.layouts.home')

@section('title', ' - 帮助中心')

@section('css')
    <link rel="stylesheet" href="/front/css/help.css">
    <style>
        .layui-tab-title{
            float: left;
        }
        .layui-tab-content{
            float: right;
            position: static !important;
        }
    </style>
@endsection

@section('main')
    <div class="main">
        <div class="bg">
            <img src="/front/images/help-banner.jpg" alt="">
        </div>
        <div class="container">
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    @forelse($categories as $k => $category)
                        @if($k == 0)
                            <li class="layui-this">{!! $category->name !!}</li>
                        @else
                            <li>{!! $category->name !!}</li>
                        @endif
                    @empty
                    @endforelse
                </ul>
                <div class="layui-tab-content">
                    @forelse($categories as $k => $category)
                        @if($k == 0)
                            <div class="layui-tab-item layui-show">
                                @else
                                    <div class="layui-tab-item">
                                        @endif
                            <div class="layui-collapse" lay-filter="test">
                                @forelse($category->articles as $article)
                                    @if($article->status == 1)
                                        <div class="layui-colla-item">
                                            <h2 class="layui-colla-title">{!! $article->title !!}</h2>
                                            <div class="layui-colla-content">
                                                {!! $article->content !!}
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                @endforelse
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection

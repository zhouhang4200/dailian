@extends('front.layouts.home')

@section('title', ' - 帮助中心')

@section('css')
    <link rel="stylesheet" href="/front/css/help.css">
@endsection

@section('main')
    <div class="main">
        <div class="bg">
            <img src="/front/images/help-banner.jpg" alt="">
        </div>
        <div class="container">
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    @forelse($categories as $category)
                        <li class="layui-this">{!! $category->name !!}</li>
                    @empty
                    @endforelse
                </ul>
                <div class="layui-tab-content">
                    @forelse($categories as $category)
                        <div class="layui-tab-item">
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

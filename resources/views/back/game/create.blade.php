@extends('back.layouts.app')

@section('title', ' | 添加游戏')

@section('css')
    <link rel="stylesheet" href="/back/css/bootstrap-fileinput.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">添加游戏</li>
                        </ul>
                        <div class="layui-tab-content">

                            @if(Session::has('success'))
                            <div class="col-lg-12">
                                <div class="alert alert-block alert-success fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4>{{ \Session::get('success', 'default') }}</h4>
                                </div>
                            </div>
                            @endif

                            @if(Session::has('fail'))
                                <div class="col-lg-12">
                                    <div class="alert alert-block alert-danger fade in">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4>{{ \Session::get('fail', 'default') }}
                                        </h4>
                                    </div>
                                </div>
                            @endif


                            <div class="col-lg-12">
                                <div class="main-box-body clearfix">
                                    <form role="form" class="layui-form" href="{{ route('admin.game.create') }}" method="post"  enctype="multipart/form-data">
                                        {!! csrf_field() !!}


                                        <div class="form-group">
                                            <label>类型</label>
                                            <select class="form-control" lay-verify="required" name="game_type_id">
                                                <option value="">请选择</option>
                                                @foreach($gameTypes as $item)
                                                    <option value="{{ $item->id }}" @if(old('game_type_id') == $item->id) @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>分类</label>
                                            <select class="form-control" lay-verify="required" name="game_class_id">
                                                <option value="">请选择</option>
                                                @foreach($gameClasses as $item)
                                                    <option value="{{ $item->id }}" @if(old('game_class_id') == $item->id) @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">游戏名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ old('name') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">图标</label>
                                            <div class="" id="uploadForm" enctype="multipart/form-data">
                                                <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                                                    <div class="fileinput-new thumbnail" style="width: 200px;height: auto;max-height:150px;">
                                                        <img id="picImg" style="width: 100%;height: auto;max-height: 140px;" src="/back/img/image.png" alt="">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                            <span class="btn btn-primary btn-file">
                                                                <span class="fileinput-new">选择文件</span>
                                                                <span class="fileinput-exists">换一张</span>
                                                                <input type="hidden" value="" name="pic1">
                                                                <input type="file" name="icon" id="picture" accept="image/gif,image/jpeg,image/x-png">
                                                            </span>
                                                        <a href="javascript:;" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">移除</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <button class="btn btn-success" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.game') }}" type="button" class="btn btn-success" >返回列表</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/back/js/bootstrap-fileinput.js"></script>
@endsection
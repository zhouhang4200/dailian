@extends('back.layouts.app')

@section('title', ' | 修改游戏')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">修改游戏</li>
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
                                        <h4>{{ \Session::get('fail', 'default') }}</h4>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-12">
                                <div class="main-box-body clearfix">
                                    <form role="form" class="layui-form" href="{{ route('admin.game.update', ['id' => $game->id]) }}" method="post">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">游戏名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ $game->name }}">
                                        </div>

                                        <div class="form-group">
                                            <label>类型</label>
                                            <select class="form-control" lay-verify="required" name="game_type_id">
                                                @foreach($gameTypes as $item)
                                                    <option value="{{ $item->id }}" @if($item->id == $game->game_type_id) selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>分类</label>
                                            <select class="form-control" lay-verify="required" name="game_class_id">
                                                @foreach($gameClasses as $item)
                                                    <option value="{{ $item->id }}" @if($item->id == $game->game_class_id) selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">图标</label>
                                            <input type="text" lay-verify="required" class="form-control" name="icon">
                                        </div>


                                        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.admin-permission') }}" type="button" class="layui-btn layui-btn-normal " >取消</a>
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

@endsection
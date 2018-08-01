@extends('back.layouts.app')

@section('title', ' | 修改游戏服')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">修改游戏服</li>
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
                                    <form role="form" class="layui-form" href="{{ route('admin.game.update', ['id' => $server->id]) }}" method="post">
                                        {!! csrf_field() !!}

                                        <div class="form-group">
                                            <label for="">所属游戏</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ $server->region->game->name }}" readonly>
                                        </div>


                                        <div class="form-group">
                                            <label>所属区</label>
                                            <select class="form-control" lay-verify="required" name="region_id">
                                                @foreach($regions as $item)
                                                    <option value="{{ $item->id }}" @if($server->region_id  == $item->id) selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">服务器名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ $server->name }}">
                                        </div>

                                        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.server') }}" type="button" class="layui-btn layui-btn-normal ">返回列表</a>
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
@extends('back.layouts.app')

@section('title', ' | 修改游戏类型')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">修改游戏类型</li>
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
                                    <form role="form" class="layui-form" href="{{ route('admin.game-leveling-type.update', ['id' => $gameLevelingType->id]) }}" method="post">
                                        {!! csrf_field() !!}

                                        <div class="form-group">
                                            <label>所属游戏</label>
                                            <select class="form-control" lay-verify="required" name="game_id">
                                                @foreach($games as $item)
                                                    <option value="{{ $item->id }}" @if($gameLevelingType->id == $item->id) selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">游戏代练类型名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ $gameLevelingType->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">手续费(%)</label>
                                            <input type="text" lay-verify="required" class="form-control" name="poundage" value="{{ $gameLevelingType->poundage + 0 }}">
                                        </div>


                                        <button class="btn btn-success" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.game-leveling-type') }}" type="button" class="btn btn-success">返回列表</a>
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
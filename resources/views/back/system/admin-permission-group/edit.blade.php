@extends('back.layouts.app')

@section('title', ' | 修改权限组')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">修改权限组</li>
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

                            <div class="col-lg-6">
                                <div class="main-box-body clearfix">
                                    <form role="form" class="layui-form" href="{{ route('admin.admin-permission-group.update', ['id' => $permissionGroup->id]) }}" method="post">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">权限组</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ $permissionGroup->name }}">
                                        </div>

                                        <button class="btn btn-success" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.admin-permission-group') }}" type="button" class="btn btn-success" >取消</a>
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
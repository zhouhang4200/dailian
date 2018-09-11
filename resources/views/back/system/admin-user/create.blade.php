@extends('back.layouts.app')

@section('title', ' | 添加管理员')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">添加管理员</li>
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
                                    <form role="form" class="layui-form" href="{{ route('admin.admin-user.create') }}" method="post">
                                        {!! csrf_field() !!}

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">账号</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">邮箱</label>
                                            <input type="email" lay-verify="required" class="form-control" name="email">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">密码</label>
                                            <input type="text" lay-verify="required" class="form-control" name="password">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFile">角色</label>
                                            <div class="ibox float-e-margins">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-md-10 text-center">角色</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr>
                                                        <td>
                                                            @foreach($roles as  $role)
                                                                <div class="checkbox-nice checkbox-inline">
                                                                    <input type="checkbox" name="roles[]" lay-skin="primary" title="{{ $role->name }}" value="{{ $role->id }}">
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <button class="btn btn-success" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.admin-user') }}" type="button" class="btn btn-success" >取消</a>
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
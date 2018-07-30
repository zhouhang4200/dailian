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
                            <li class="layui-this" lay-id="add">修改用户</li>
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
                                    <form role="form" class="layui-form" href="{{ route('admin.admin-role.update', ['id' => $adminUser->id]) }}" method="post">
                                        {!! csrf_field() !!}

                                        <div class="form-group">
                                            <label for="">用户名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="username" value="{{ $adminUser->username }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">密码</label>
                                            <input type="text" lay-verify="required" class="form-control" name="password" value="">
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
                                                                        <input type="checkbox" name="roles[]" lay-skin="primary" title="{{ $role->name }}" value="{{ $role->id }}" @if(in_array($role->id, $adminUserHasRoles)) checked="" @endif>
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <button class="layui-btn layui-btn-normal" type="submit" >确认</button>
                                        <a  href="{{ route('admin.admin-permission-group') }}" type="button" class="layui-btn layui-btn-normal " >取消</a>
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
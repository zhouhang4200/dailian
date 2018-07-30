@extends('back.layouts.app')

@section('title', ' | 添加角色')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">添加角色</li>
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
                                    <form role="form" class="layui-form" href="{{ route('admin.admin-role.create') }}" method="post">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">组名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFile">权限</label>
                                            <div class="ibox float-e-margins">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-md-1 text-center">模块</th>
                                                        <th class="col-md-10 text-center">权限</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($permissions as $name => $permission)
                                                        <tr>
                                                            <td>{{ $name }}</td>
                                                            <td>
                                                                @foreach($permission as $v)
                                                                    <div class="checkbox-nice checkbox-inline">
                                                                        <input type="checkbox" name="permissions[]" lay-skin="primary" title="{{ $v->name }}" value="{{ $v->id }}">
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.admin-role') }}" type="button" class="layui-btn layui-btn-normal " >取消</a>
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
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

                            <div class="col-lg-12">
                                <div class="main-box-body clearfix">
                                    <form role="form" class="layui-form" href="{{ route('admin.admin-role.update', ['id' => $role->id]) }}" method="post">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label for="">角色名</label>
                                            <input type="text" lay-verify="required" class="form-control" name="name" value="{{ $role->name }}">
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
                                                            <td>
                                                                <div class="checkbox-nice checkbox-inline" style="width: 120px">
                                                                <input type="checkbox" lay-filter="choseAll" lay-skin="primary" title="{{ $name }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @foreach($permission as $v)
                                                                    <div class="checkbox-nice checkbox-inline">
                                                                        <input type="checkbox" name="permissions[]" lay-skin="primary" title="{{ $v->name }}" value="{{ $v->id }}" @if(in_array($v->id, $roleHasPermissions)) checked="" @endif>
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <button class="btn btn-success" type="submit" >确认</button>
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
    <script>
        layui.use(['form', 'layedit', 'laydate'], function() {
            var form = layui.form, layer = layui.layer;

            form.on('checkbox(choseAll)', function(data) {
                $(data.elem).closest('tr').find('input').each(function (i, item) {
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
        });

    </script>
@endsection
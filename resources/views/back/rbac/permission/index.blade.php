@extends('back.layouts.app')

@section('title', ' | 商户权限列表')

@section('css')
    <style>
        .layui-table th, td{
            text-align:center;
        }
        .layui-form-item .layui-form-label{
            width:100px;
        }
        .layui-form {
            padding-top: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">
                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">商户权限列表</li>
                        </ul>
                        <div class="layui-tab-content">
                            <form class="layui-form" action="">
                                <button class="btn btn-success" lay-submit="" lay-filter="create">新增</button>
                            </form>
                            <div class="layui-tab-item layui-show" id="permission-list">
                                @include('back.rbac.permission.list', ['permissions' => $permissions])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-content" style="display: none;" id="create">
            <form class="layui-form" action="">
                {!! csrf_field() !!}
                <div class="layui-form-item">
                    <label class="layui-form-label">选择模块</label>
                    <div class="layui-input-inline">
                        <select name="module_name" lay-verify="required" lay-search="">
                            <option value="">请选择</option>
                            <option value="接单管理">接单管理</option>
                            <option value="发单管理">发单管理</option>
                            <option value="财务管理">财务管理</option>
                            <option value="账号管理">账号管理</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">英文名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" lay-verify="required" value="" autocomplete="off" placeholder="请输入" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">中文名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="alias" lay-verify="required" value="" autocomplete="off" placeholder="请输入" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="store">确认</button>
                        <button  type="button" class="layui-btn layui-btn-normal cancel">取消</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="layui-tab-content" style="display: none;" id="edit">
            <form class="layui-form" action="">
                {!! csrf_field() !!}
                <div class="layui-form-item">
                        <label class="layui-form-label">选择模块</label>
                        <div class="layui-input-inline">
                            <select name="module_name" lay-verify="required" lay-search="">
                                <option value="">请选择</option>
                                <option value="接单管理">接单管理</option>
                                <option value="发单管理">发单管理</option>
                                <option value="财务管理">财务管理</option>
                                <option value="账号管理">账号管理</option>
                            </select>
                        </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">英文名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" lay-verify="required" value="" autocomplete="off" placeholder="请输入" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">中文名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="alias" lay-verify="required" value="" autocomplete="off" placeholder="请输入" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="update">确认</button>
                        <button  type="button" class="layui-btn layui-btn-normal cancel">取消</button>
                    </div>
                </div>
            </form>
        </div>
        @endsection

        @section('js')
            <script>
                layui.use('form', function(){
                    var form = layui.form,layer=layui.layer,laydate=layui.laydate,table=layui.table;

                    // 监听新增
                    form.on('submit(create)', function () {
                        var s = window.location.search; //先截取当前url中“?”及后面的字符串
                        var page=s.getAddrVal('page');

                        layer.open({
                            type: 1,
                            shade: 0.6,
                            title: '新增',
                            area: ['400px', '300px'],
                            content: $('#create')
                        });
                        form.on('submit(store)', function(data){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.permission.store') }}",
                                data:{data:data.field},
                                success: function (data) {
                                    layer.msg(data.message, {time:1500}, function () {
                                        if (data.status == 1) {
                                            layer.closeAll();
                                            if (page) {
                                                $.get("{{ route('admin.permission') }}?page="+page, function (result) {
                                                    $('#permission-list').html(result);
                                                    form.render();
                                                }, 'json');
                                            } else {
                                                $.get("{{ route('admin.permission') }}", function (result) {
                                                    $('#permission-list').html(result);
                                                    form.render();
                                                }, 'json');
                                            }
                                        } else {

                                        }
                                    });

                                }
                            });

                            return false;
                        });
                        return false;
                    })
                    // 取消按钮
                    $('.cancel').click(function () {
                        layer.closeAll();
                    });
                    // 编辑
                    form.on('submit(edit)', function () {
                        var id=this.getAttribute('lay-id');
                        var name=this.getAttribute('lay-name');
                        var alias=this.getAttribute('lay-alias');
                        var module_name=this.getAttribute('lay-module-name');
                        var s = window.location.search; //先截取当前url中“?”及后面的字符串
                        var page=s.getAddrVal('page');

                        $('#edit input[name="name"]').val(name);
                        $('#edit input[name="alias"]').val(alias);
                        $('#edit select[name="module_name"]').val(module_name);
                        form.render();
                        layer.open({
                            type: 1,
                            shade: 0.6,
                            title: '编辑',
                            area: ['400px', '300px'],
                            content: $('#edit')
                        });

                        form.on('submit(update)', function(data){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.permission.update') }}",
                                data:{id:id, data:data.field},
                                success: function (data) {
                                    layer.msg(data.message);
                                    if (page) {
                                        $.get("{{ route('admin.permission') }}?page="+page, function (result) {
                                            $('#permission-list').html(result);
                                            form.render();
                                        }, 'json');
                                    } else {
                                        $.get("{{ route('admin.permission') }}", function (result) {
                                            $('#permission-list').html(result);
                                            form.render();
                                        }, 'json');
                                    }
                                }
                            });
                            layer.closeAll();
                            return false;
                        });
                        return false;
                    })

                    // 删除
                    form.on('submit(destroy)', function (data) {
                        var id=this.getAttribute('lay-id');
                        var s = window.location.search; //先截取当前url中“?”及后面的字符串
                        var page=s.getAddrVal('page');
                        layer.confirm('确认要删除吗', {icon: 3, title: '提示',btnAlign: 'c'}, function (index) {
                            $.post("{{ route('admin.permission.delete') }}", {id: id}, function (result) {
                                layer.msg(result.message);

                                if (page) {
                                    $.get("{{ route('admin.permission') }}?page=" + page, function (result) {
                                        $('#permission-list').html(result);
                                        form.render();
                                    }, 'json');
                                } else {
                                    $.get("{{ route('admin.permission') }}", function (result) {
                                        $('#permission-list').html(result);
                                        form.render();
                                    }, 'json');
                                }
                            })
                        });
                            return false;
                    });

                    String.prototype.getAddrVal = String.prototype.getAddrVal||function(name){
                        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                        var data = this.substr(1).match(reg);
                        return data!=null?decodeURIComponent(data[2]):null;
                    }
                });
            </script>
@endsection
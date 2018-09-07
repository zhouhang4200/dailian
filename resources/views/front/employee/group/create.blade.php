@extends('front.layouts.app')

@section('title', '账号 - 岗位添加')
<style>
    a {
        text-decoration : none !important;
    }
    li a {
        height:56px !important;
    }

    dd a {
        height:40px !important;
    }
    .layui-nav-child {
        margin-bottom: 0px;
    }
</style>

@section('css')
    <link rel="stylesheet" type="text/css" href="/back/css/bootstrap/bootstrap.min.css"/>
    <style>
        .layui-form input {
            width:800px;
        }
        .table {
            width:800px;
        }
        .layui-form-label {
            width:100px;
        }
        .layui-form-item .layui-input-inline {
            float: left;
            width: 150px;
            margin-right: 10px;
        }
        .layui-form-checked[lay-skin="primary"] span {
            background-color: #fff;
        }
        .layui-form-checked[lay-skin=primary] i {
            border-color: #198cff;
            background-color: #198cff;
            color: #fff;
        }
        .layui-form-checkbox[lay-skin=primary]:hover i {
            border-color: #198cff;
            color: #fff;
        }
        .layui-logo a img{
            padding-bottom: 8px;
        }
    </style>

@endsection

@section('main')
<div class="layui-card qs-text">
    <div class="layui-card-header">岗位添加</div>
    <div class="layui-card-body">
        <form class="layui-form" method="" action="">
            {!! csrf_field() !!}
            <div style="width: 100%">
                <div class="layui-form-item">
                    <label class="layui-form-label">岗位名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="required" value="{{ old('name') }}" autocomplete="off" placeholder="请输入" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">拥有权限</label>
                        <div class="layui-input-block">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="col-md-1 text-center" style="width: 1%">模块</th>
                                <th class="col-md-1 text-center">权限</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($modulePermissions as $module => $permissions)
                                <tr>
                                    <td><input type="checkbox" name="module_name" lay-skin="primary" title="{{ $module }}" lay-filter="module" value="{{ $module }}"></td>
                                    <td>
                                        @forelse($permissions as $permission)
                                        <div class="layui-input-inline" style="width:240px">
                                            <input type="checkbox" name="permissions" lay-skin="primary" title="{{ $permission->alias }}" value="{{ $permission->id }}">
                                        </div>
                                        @empty
                                        @endforelse
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="qs-btn layui-btn-normal" lay-submit="" lay-filter="confirm">立即提交</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
<!--START 底部-->
@section('js')
    <script>
    layui.use(['form', 'table'], function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        var layer = layui.layer;
        // 全选
        form.on('checkbox(module)', function (data) {
            var child=$(data.elem).parents('td').next('td').find('input[type="checkbox"]');
            child.each(function(index, item){  
                item.checked = data.elem.checked;  
            });  
            form.render('checkbox');
        })
        form.on('submit(confirm)', function (data) {
            var ids=[];
            var name=data.field.name;
            $("input:checkbox[name='permissions']:checked").each(function() { // 遍历name=test的多选框
                $(this).val();  // 每一个被选中项的值
                ids.push($(this).val());
            });

            $.post("{{ route('employee.group.store') }}", {ids:ids,name:name}, function (result) {
                layer.msg(result.message, {time:500}, function () {
                    if (result.status == 1) {
                        window.location.href="{{ route('employee.group') }}";
                    }
                });
            })
            return false;
        })
    });
    </script>
@endsection
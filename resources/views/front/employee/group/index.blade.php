@extends('front.layouts.app')

@section('title', '账号 - 岗位列表')

@section('css')
    <style>
        .layui-form-label {
            width:100px;
        }
        .layui-table th, .layui-table td {
            text-align:center;
        }
        td a:hover{
            color:#fff;
        }
    </style>
@endsection

@section('main')
<div class="layui-card qs-text">
    <div class="layui-card-body">
        <div style="padding-top:5px; padding-bottom:10px; float:right">
            <a href="{{ route('employee.group.create') }}" style="color:#fff"><button class="qs-btn layui-btn-normal layui-btn-small"><i class="iconfont icon-add"></i><span style="padding-left: 3px">添加</span></button></a>
        </div>
        <form class="layui-form" method="" action="" id="role">
            @include('front.employee.group.list', ['userRoles' => $userRoles])
        </form>
        {!! $userRoles->render() !!}
    </div>
</div>
@endsection
<!--START 底部-->
@section('js')
    <script>
        layui.use('form', function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;
            // 删除
            form.on('submit(delete)', function (data) {
                var roleId=this.getAttribute('lay-id');
                layer.confirm('确定删除岗位吗？', {icon: 3, title:'提示'}, function(index) {
                    $.post("{{ route('employee.group.delete') }}", {roleId: roleId}, function (result) {
                        layer.msg(result.message, {time:1500}, function () {
                            if (result.status == 1) {
                                $.get("{{ route('employee.group') }}", function (result) {
                                    $('#role').html(result);
                                    form.render();
                                }, 'json');
                            }
                            form.render;
                            layer.closeAll();
                        });
                    });
                });
                return false;
            })
        });
    </script>
@endsection
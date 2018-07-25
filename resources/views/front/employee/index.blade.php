@extends('front.layouts.app')

@section('title', '账号 - 员工管理')

@section('css')
    <style>
        .layui-layer-btn{
            text-align:center !important;
        }
        .layui-table[lay-size=sm] td, .layui-table[lay-size=sm] th{
            text-align: center;
        }
        .layui-form-onswitch {
            border-color: #198cff;
            background-color: #198cff;
        }
        td a:hover{
            color:#fff;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <form class="layui-form" method="" action="" >
                <div class="layui-inline" style="float:left">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 80px; padding-left: 0px;">员工姓名：</label>
                        <div class="layui-input-inline">
                            <select name="userId" lay-verify="" lay-search="">
                                <option value="">请输入</option>
                                @forelse($children as $child)
                                    <option value="{{ $child->id }}" {{ $child->id == $userId ? 'selected' : '' }}>{{ $child->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <label class="layui-form-label" style="width: 45px; padding-left: 0px;">账号：</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" value="{{ $name ?? '' }}" name="name" placeholder="请输入">
                        </div>
                        <label class="layui-form-label" style="width: 45px; padding-left: 0px;">岗位：</label>
                        <div class="layui-input-inline">
                            <select name="station" lay-filter="">
                                <option value="">请输入</option>
                                @forelse($userRoles as $userRole)
                                    <option value="{{ $userRole->id }}" {{ $userRole->id == $station ? 'selected' : '' }} >{{ $userRole->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                <div style="float: left">
                    <div class="layui-inline" >
                        <button class="qs-btn layui-btn-normal layui-btn-small" lay-submit="" lay-filter="demo1" style="margin-left: 10px"><i class="iconfont icon-search"></i><span style="padding-left: 3px">查询</span></button>
                        &nbsp;
                        <a href="{{ route('employee.create') }}" style="color:#fff; float:right;" class="qs-btn layui-btn-normal layui-btn-small"><i class="iconfont icon-add"></i><span style="padding-left: 3px">添加</span></a>
                    </div>
                </div>
            </form>

            <div class="layui-tab-item layui-show" lay-size="sm" id="staff">
                @include('front.employee.list')
                {!! $users->appends([
                    'name' => $name,
                    'userId' => $userId,
                    'station' => $station,
                ])->render() !!}
            </div>
        </div>
    </div>
@endsection
<!--START 底部-->
@section('js')
    <script>
        layui.use(['form', 'layedit', 'laydate'], function(){
            var laydate = layui.laydate;
            var form = layui.form;
            // 获取路由后面的参数
            String.prototype.getAddrVal = String.prototype.getAddrVal||function(name){
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var data = this.substr(1).match(reg);
                return data!=null?decodeURIComponent(data[2]):null;
            }
            // 账号启用禁用
            form.on('switch(open)', function(data){
                var id = data.elem.getAttribute('lay-data');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('employee.forbidden') }}",
                    data:{id:id},
                    success: function (data) {
                        layer.msg(data.message);
                    }
                });
            });
            //页面显示修改结果
            var succ = "{{ session('succ') }}";
            var fail = "{{ session('fail') }}";

            if (succ) {
                layer.msg(succ, {icon: 6, time:1000});
            }
            if (fail) {
                layer.msg(fail, {icon: 5, time:1000});
            }
            // 删除
            form.on('submit(delete)', function (data) {
                var id = data.elem.getAttribute('lay-data');
                var s=window.location.search; //先截取当前url中“?”及后面的字符串
                var page=s.getAddrVal('page');

                layer.confirm('确认删除吗？', {
                    btn: ['确认', '取消']
                    ,title: '提示'
                    ,icon: 3
                }, function(index, layers){
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('employee.delete') }}",
                        data:{id:id},
                        success: function (data) {
                            layer.msg(data.message);
                            if (page) {
                                $.get("{{ route('employee') }}?page="+page, function (result) {
                                    $('#staff').html(result);
                                    form.render();
                                }, 'json');
                            } else {
                                $.get("{{ route('employee') }}", function (result) {
                                    $('#staff').html(result);
                                    form.render();
                                }, 'json');
                            }
                        }
                    });
                    layer.closeAll();
                }, function(index){
                    layer.closeAll();
                });
                return false;
            });
        });
    </script>
@endsection
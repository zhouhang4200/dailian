@extends('front.layouts.app')

@section('title', '个人资料修改')

@section('css')
    <style>
        .layui-table tr th , td {
            text-align: center;
        }
        .postion{
            position: relative;
        }
        .tip,
        .tips{
            width: 300px;
            height: 50px;
            padding: 5%;
            color: #fff;
            border-radius: 10px;
            background-color:#91C5FF;
            position: absolute;
            left:273px;
            top: -67px;
            padding: 5px;
        }
        .tip{
            top:-65px;
            left: 275px;
        }
        .tip::after,
        .tips::after{
            content: '';
            border: 10px solid rgba(0, 0, 0, 0);
            border-top-color:#91C5FF;
            position: absolute;
            right: 255px;
            top:60px;
        }
        .none{
            display: none;
        }
        #recharge,
        #store{
            font-size: 20px;
            position: absolute;
            left: 310px;
            top: 7px;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <div class="layui-tab-item layui-show" lay-size="sm">
                <form class="layui-form" action="">
                    <div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">昵称:</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" lay-verify="required" value="{{ $user->name }}" autocomplete="off" placeholder="请输入昵称" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">年龄:</label>
                            <div class="layui-input-inline">
                                <input type="text" name="age" lay-verify="required|number" value="{{ $user->age }}" autocomplete="off" placeholder="请输入年龄" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">QQ:</label>
                            <div class="layui-input-inline">
                                <input type="text" name="qq" lay-verify="required|number" value="{{ $user->qq }}" autocomplete="off" placeholder="请输入QQ" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">微信:</label>
                            <div class="layui-input-inline">
                                <input type="text" name="wechat" lay-verify="required|number" value="{{ $user->wechat }}" autocomplete="off" placeholder="请输入微信" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-inline">
                                <button type="hidden" class="qs-btn qs-btn-normal" lay-submit="" lay-filter="update">确认修改</button>
                                <button type="hidden" class="qs-btn qs-btn-normal" lay-submit="" lay-filter="cancel">取消</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
<!--START 底部-->
@section('js')
    <script>
        layui.use(['form', 'table', 'upload'], function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;
            var upload = layui.upload;

            form.on('submit(update)', function(data) {
                $.post("{{ route('profile.update') }}", {data:data.field}, function (result) {
                    if (result.status == 1) {

                        layer.msg(result.message, {
                            time:1500,
                            icon:6
                        }, function () {
                            window.location.href="{{ route('profile') }}";
                        });
                    } else {
                        layer.msg(result.message, {
                            time:1500,
                            icon:5
                        })
                    }
                    return false;
                });
                return false;
            });

            form.on('submit(cancel)', function(data) {
                window.location.href="{{ route('profile') }}";
                return false;
            });
        });
    </script>
@endsection
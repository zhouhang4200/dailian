@extends('back.layouts.app')

@section('title', ' | 用户资料')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class=""><span>首页</span></li>
                <li class=""><a href="{{ route('admin.user') }}"><span>商户列表</span></a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive">
                        <div class="layui-tab layui-tab-brief" lay-filter="detail">
                            <ul class="layui-tab-title">
                                <li lay-id="detail"><a href="{{ route('admin.user.show')}}?id={{ $user->id }}">用户资料</a></li>
                                <li lay-id="authentication"><a href="{{ route('admin.user.show') }}?id={{ $user->id }}">转账信息</a></li>
                                <li lay-id="poundage"><a href="{{ route('admin.user.poundage') }}?id={{ $user->id }}">手续费设置</a></li>
                                <li lay-id="spread" class="layui-this"><a href="{{ route('admin.user.spread') }}?id={{ $user->id }}">推广返利设置</a></li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="layui-tab-item layui-show detail">
                                    @if($userSpread)
                                        <form class="layui-form" action="" style="width: 500px">
                                            <input type="hidden" name="id" required disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->id }}">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">用户名</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="name" style="background-color: #e1e1e1" required disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->name }}">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">推广返利比例</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="spread_rate" value="{{ $userSpread->spread_rate + 0 }}" lay-verify="" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <div class="layui-input-block">
                                                    <button class="layui-btn" lay-submit="" lay-filter="update">确认</button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <form class="layui-form" action="" style="width: 500px">
                                            <input type="hidden" name="id" required disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->id }}">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">用户名</label>
                                                <div class="layui-input-block">
                                                    <input type="text" style="background-color: #e1e1e1" name="name" disabled lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->name }}">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">推广返利比例</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="spread_rate" placeholder="示例：0,0.01,0.05...可为空" lay-verify="" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <div class="layui-input-block">
                                                    <button class="layui-btn" lay-submit="" lay-filter="store">确认</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="layui-tab-item authentication"></div>
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
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            // 新增
            form.on('submit(store)', function (data) {
                $.post("{{ route('admin.user.spread.store') }}", {
                    id:data.field.id,
                    spread_rate:data.field.spread_rate,
                }, function (result) {
                    if (result.status == 1) {
                        layer.msg(result.message, {
                            time:1500,
                            icon:6
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

            // 编辑
            form.on('submit(update)', function (data) {
                $.post("{{ route('admin.user.spread.update') }}", {
                    id:data.field.id,
                    spread_rate:data.field.spread_rate,
                }, function (result) {
                    if (result.status == 1) {
                        layer.msg(result.message, {
                            time:1500,
                            icon:6
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
        });
    </script>
@endsection
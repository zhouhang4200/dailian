@extends('back.layouts.app')

@section('title', ' | 系统设置')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">系统设置</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-tab layui-tab-brief layui-form" lay-filter="key">
                            <ul class="layui-tab-title">
                                <li @if($key == 'withdraw') class="layui-this" @endif lay-id="withdraw">提现设置
                                </li>
                                <li @if($key == 'mini-program') class="layui-this" @endif lay-id="mini-program">小程序环境
                                </li>
                            </ul>
                        </div>

                        <div class="layui-tab-content">
                            <div class="layui-tab-item @if($key == 'withdraw') layui-show @endif">

                                <form class="layui-form layui-form-pane" action="{{ route('admin.setting.update', ['key' => 'withdraw']) }}" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="key" value="withdraw">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">手续费(%)</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="rate" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input" value="{{ $value['rate'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">最小金额</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="min_amount" autocomplete="off" class="layui-input" value="{{ $value['min_amount'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">最大金额</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="max_amount" autocomplete="off" class="layui-input" value="{{ $value['max_amount'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">提现说明</label>
                                        <div class="layui-input-block">
                                            <textarea name="tips" placeholder="请输入内容" class="layui-textarea">{{ $value['tips'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <button class="btn btn-success" lay-submit="" lay-filter="demo2">保存设置</button>
                                    </div>
                                </form>
                            </div>
                            <div class="layui-tab-item @if(request('key') == 'mini_program') layui-show @endif">
                                <form class="layui-form layui-form-pane" action="{{ route('admin.setting.update', ['key' => 'mini_program']) }}" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="key" value="withdraw">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">环境（1 测试 2 正式）</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="env" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input" value="{{ $value['env'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <button class="btn btn-success" lay-submit="" lay-filter="demo2">保存设置</button>
                                    </div>
                                </form>
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
        $('#export').click(function () {
            var url = "?export=1&" + $('#search-flow').serialize();
            window.location.href = url;
        });

        layui.use(['form', 'layedit', 'laydate', 'element'], function() {
            var form = layui.form, layer = layui.layer, element = layui.element, laydate = layui.laydate;

            element.on('tab(key)', function(){
                window.location.href="{{ route('admin.setting') }}?key=" + this.getAttribute('lay-id');
            });
            // 解冻
            form.on('submit(unfrozen)', function (data) {
                var notice = '此次罚款金额' + $(data.elem).attr('data-amount') + ',确认解冻?';
                layer.confirm(notice, {icon: 3, title:'提示'}, function(index) {
                    $.post('{{ route('admin.user.fine-ticket.unfrozen') }}', {
                        id:$(data.elem).attr('data-id')
                    }, function (result) {
                        if (result.status == 1) {
                            layer.msg(result.message, {time:600}, function () {
                                window.location.reload();
                            })
                        } else {
                            layer.msg(result.message, {time:600}, function () {})
                        }
                    }, 'json');
                });
            });
            // 罚款
            form.on('submit(fine)', function (data) {
                var notice = '此次罚款金额' + $(data.elem).attr('data-amount') + ',确认罚款?';
                layer.confirm(notice, {icon: 3, title:'提示'}, function () {
                    $.post('{{ route('admin.user.fine-ticket.fine') }}', {
                        id:$(data.elem).attr('data-id')
                    }, function (result) {
                        if (result.status == 1) {
                            layer.msg(result.message, {time:600}, function () {
                                window.location.reload();
                            })
                        } else {
                            layer.msg(result.message, {time:600}, function () {})
                        }
                    }, 'json');
                })
            });
        });
    </script>
@endsection

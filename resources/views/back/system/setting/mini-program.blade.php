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
                                <li @if($key == 'withdraw') class="layui-this" @endif lay-id="withdraw">
                                    提现设置
                                </li>
                                <li @if($key == 'mini-program') class="layui-this" @endif lay-id="mini-program">
                                    小程序环境
                                </li>
                            </ul>
                        </div>

                        <div class="layui-tab-content">
                            <div class="layui-tab-item @if(request('key') == 'mini-program') layui-show @endif">
                                <form class="layui-form layui-form-pane" action="{{ route('admin.setting.update', ['key' => 'mini-program']) }}" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="key" value="mini-program">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">环境</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="env" value="1" title="测试" @if($value['env'] == 1) checked @endif>
                                            <input type="radio" name="env" value="2" title="正式" @if($value['env'] == 2) checked @endif>
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

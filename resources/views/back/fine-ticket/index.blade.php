@extends('back.layouts.app')

@section('title', ' | 罚款管理')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">罚款管理</li>
                </ul>
                <div class="layui-tab-content">

                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="user_id"  placeholder="用户ID" value="{{ request('user_id') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="start-time" name="start_time"  autocomplete="off" placeholder="开始时间" value="{{ request('start_time') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="end-time" name="end_time"  autocomplete="off" placeholder="结束时间" value="{{ request('end_time') }}">
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.user.fine-ticket.create') }}" class="btn btn-success">添加罚款</a>
                                    <button class="btn btn-success" lay-submit="" lay-filter="search">搜索</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-tab layui-tab-brief layui-form" lay-filter="status">
                            <ul class="layui-tab-title">
                                <li @if(request('status') == 0) class="layui-this" @endif lay-id="0">全部</li>
                                <li @if(request('status') == 1) class="layui-this" @endif lay-id="1">冻结中
                                    @if(optional($statusCount)[1])<span class="layui-badge">{{ optional($statusCount)[1] }}</span> @endif
                                </li>
                                <li @if(request('status') == 2) class="layui-this" @endif lay-id="2">已解冻
                                    @if(optional($statusCount)[2])<span class="layui-badge">{{ optional($statusCount)[2] }}</span> @endif
                                </li>
                                <li @if(request('status') == 3) class="layui-this" @endif lay-id="3">已罚款
                                    @if(optional($statusCount)[3])<span class="layui-badge">{{ optional($statusCount)[3] }}</span> @endif
                                </li>
                            </ul>
                        </div>

                        <table class="layui-table" lay-size="sm">
                            <thead>
                            <tr>
                                <th>单号</th>
                                <th>ID / 呢称</th>
                                <th>关联单号</th>
                                <th>金额</th>
                                <th>原因</th>
                                <th>备注</th>
                                <th>冻结时间</th>
                                <th>解冻时间</th>
                                <th>罚款时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($fineTickets as $item)
                                <tr>
                                    <td>{{ $item->trade_no }}</td>
                                    <td>{{ $item->user_id }} / {{ $item->user->name }}</td>
                                    <td>{{ $item->relation_trade_no }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->reason }}</td>
                                    <td>{{ $item->remark }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->status == 2 ? $item->updated_at : '' }}</td>
                                    <td>{{ $item->status == 3 ? $item->updated_at : '' }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <button href="{{ route('admin.user.fine-ticket', ['trade_no' => $item->game_leveling_order_trade_no]) }}"
                                               type="button"
                                               class="btn btn-success"
                                               data-amount="{{ $item->amount }}"
                                               data-id="{{ $item->id }}"
                                               lay-submit=""
                                               lay-filter="unfrozen"
                                            >解冻</button>
                                            <button href="{{ route('admin.user.fine-ticket', ['trade_no' => $item->game_leveling_order_trade_no]) }}"
                                               type="button"
                                               class="btn btn-danger"
                                               data-amount="{{ $item->amount }}"
                                               data-id="{{ $item->id }}"
                                               lay-submit=""
                                               lay-filter="fine"
                                            >罚款</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $fineTickets->appends(request()->all())->links() }}
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

            element.on('tab(status)', function(){
                window.location.href="{{ route('admin.user.fine-ticket') }}?status=" + this.getAttribute('lay-id');
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

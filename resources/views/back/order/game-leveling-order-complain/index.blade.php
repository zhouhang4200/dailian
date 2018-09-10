@extends('back.layouts.app')

@section('title', ' | 订单列表')

<style>
    .layui-badge-dot {
        padding-left: 0px;
        border-left-width: 3px;
        margin-left: 4px;
        margin-right: -5px;
    }

</style>

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">订单列表</li>
                </ul>
                <div class="layui-tab-content">

                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="parent_user_id"  placeholder="用户ID" value="{{ request('parent_user_id') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="start-time" name="start_time"  autocomplete="off" placeholder="开始时间" value="{{ request('start_time') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="end-time" name="end_time"  autocomplete="off" placeholder="结束时间" value="{{ request('end_time') }}">
                                </div>
                                <div class="col-md-2">
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
                                <li @if(request('status') == 1) class="layui-this" @endif lay-id="1">待处理
                                    @if(optional($statusCount)[1])<span class="layui-badge">{{ optional($statusCount)[1] }}</span> @endif
                                </li>
                                <li @if(request('status') == 2) class="layui-this" @endif lay-id="2">已处理
                                    @if(optional($statusCount)[2])<span class="layui-badge">{{ optional($statusCount)[2] }}</span> @endif
                                </li>
                            </ul>
                        </div>

                        <table class="layui-table" lay-size="sm">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>投诉方</th>
                                <th>被投诉方</th>
                                <th>订单号</th>
                                <th>投诉原因</th>
                                <th>备注</th>
                                <th>投诉时间</th>
                                <th>处理时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($complainOrders as $item)
                                <tr>

                                    <td >{{ $item->id }}</td>
                                    <td>
                                        @if($item->parent_user_id == optional($item->order)->parent_user_id )
                                            {{ optional($item->order)->parent_user_id }} <br/>
                                            {{ optional($item->order)->parent_username }}
                                        @else
                                            {{ optional($item->order)->take_parent_user_id }} <br/>
                                            {{ optional($item->order)->take_parent_username }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->parent_user_id == optional($item->order)->parent_user_id )

                                            {{ optional($item->order)->take_parent_user_id }} <br/>
                                            {{ optional($item->order)->take_parent_username }}
                                        @else
                                            {{ optional($item->order)->parent_user_id }} <br/>
                                            {{ optional($item->order)->parent_username }}
                                        @endif
                                    </td>
                                    <td>{{ $item->game_leveling_order_trade_no }}</td>
                                    <td>{{ $item->reason }}</td>
                                    <td>{{ $item->remark }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->dispose_at }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <a href="{{ route('admin.game-leveling-order-complain.show', ['trade_no' => $item->game_leveling_order_trade_no]) }}" type="button" class="btn btn-success" data-id="" id="complain_message_{{ $item->game_leveling_order_trade_no  }}">处理</a>
                                        @elseif($item->status == 2)
                                            <a href="{{ route('admin.game-leveling-order-complain.show', ['trade_no' => $item->game_leveling_order_trade_no]) }}" type="button" class="btn btn-success" data-id="">查看</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $complainOrders->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="//cdn.bootcss.com/socket.io/1.3.7/socket.io.min.js"></script>
    <script>
        $('#export').click(function () {
            var url = "?export=1&" + $('#search-flow').serialize();
            window.location.href = url;
        });

        layui.use(['form', 'layedit', 'laydate', 'element'], function() {
            var form = layui.form, layer = layui.layer, element = layui.element, laydate = layui.laydate;

            element.on('tab(status)', function(){
                window.location.href="{{ route('admin.game-leveling-order-complain') }}?status=" + this.getAttribute('lay-id');
            });
        });

        (function () {
            var socket=io("{{ env('SOCKET') }}");
            socket.on("complain_message:all", function (message) {
                var id = 'complain_message_'+message;
                var data = document.getElementById(id);
                var createSpan = document.createElement("span");
                createSpan.className = "layui-badge-dot";

                data.appendChild(createSpan);
            });
        })(window);
    </script>
@endsection

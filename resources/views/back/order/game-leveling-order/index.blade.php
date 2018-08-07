@extends('back.layouts.app')

@section('title', ' | 订单列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">订单列表</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form id="search-flow" action="">
                            <div class="row">

                                <div class="form-group col-md-2">
                                    <select class="form-control" name="type">
                                        <option value="">游戏</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">区</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">服</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">状态</option>
                                        @foreach(\App\Models\GameLevelingOrder::$statusDescribe as $key => $value)
                                            <option value="{{ $key }}" @if(request('status') == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <button class="btn btn-primary" type="button" id="export">导出</button>
                                </div>
                            </div>
                        </form>

                        <table class="layui-table" lay-size="sm">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>游戏</th>
                                <th>区</th>
                                <th>服务器</th>
                                <th>代练标题</th>
                                <th>金额</th>
                                <th>安全保证金</th>
                                <th>效率保证金</th>
                                <th>状态</th>
                                <th>发单用户</th>
                                <th>接单用户</th>
                                <th>接单时间</th>
                                <th>下单时间</th>
                                <th>完成时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($orders as $item)
                                <tr>
                                    <td>{{ $item->trade_no }}</td>
                                    <td>{{ $item->game_name }}</td>
                                    <td>{{ $item->region_name }}</td>
                                    <td>{{ $item->server_name }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->security_deposit }}</td>
                                    <td>{{ $item->efficiency_deposit }}</td>
                                    <td>{{ $item->getStatusDescribe() }}</td>
                                    <td>{{ $item->parent_username }}</td>
                                    <td>{{ $item->take_parent_username }}</td>
                                    <td>{{ $item->take_at }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->complete_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.game-leveling-order.show', ['trade_no' => $item->trade_no]) }}" type="button" class="layui-btn layui-btn-normal layui-btn-mini complete" data-id="">详情</a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $orders->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#time-start').datepicker();
        $('#time-end').datepicker();

        $('#export').click(function () {
            var url = "?export=1&" + $('#search-flow').serialize();
            window.location.href = url;
        });

        layui.use(['layer'], function () {


        });
    </script>
@endsection

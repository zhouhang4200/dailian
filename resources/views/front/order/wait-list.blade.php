@extends('front.layouts.app')

@section('title', '接单中心')

@section('css')
    <link rel="stylesheet" href="/front/css/bootstrap-fileinput.css">
    <style>
        .layui-layout-admin .layui-body {
            top: 50px;
        }

        .layui-layout-admin .layui-footer {
            height: 52px;
        }

        .footer {
            height: 72px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .main {
            padding: 20px;
        }

        .layui-footer {
            z-index: 999;
        }

        .layui-card-header {
            height: auto;
            border-bottom: none;
            padding-bottom: 0;
        }

        .layui-card .layui-tab {
            margin-top: 3px;
            margin-bottom: 12px;
        }
        .layui-form-item {
            margin-bottom: 12px;
        }
        .last-item{
            margin-bottom: 5px;
        }
        .layui-tab-title li{
            min-width: 50px;
            font-size: 12px;
        }
        .qsdate{
            float: left;
            width: 40% !important;
        }
        .layui-card-header{
            padding: 15px 15px 0 15px;;

        }
        .layui-card-body{
            padding-top: 0;
        }
        .layui-card .layui-tab-brief .layui-tab-content {
            padding: 0px;
        }
        /* 修改同意字体为12px */
        .last-item .last-item-btn {
            margin-left: 0;
        }
        @media screen and (max-width: 990px){
            .layui-col-md12 .layui-card .layui-card-header .layui-row .layui-form .first .layui-form-label{
                width: 80px;
                padding: 5px 10px;
                text-align: right;
            }
            .layui-col-md12 .first .layui-input-block{
                margin-left: 110px;
            }
            .last-item .last-item-btn {
                margin-left: 40px;
            }
        }
        /* 改写header高度 */
        .layui-card-header {
            font-size:12px;
        }
        .layui-table-edit {
            height: 50px;
        }

        .layui-layer-btn .layui-layer-btn0 {
            border-color: #198cff;
            background-color: #198cff;
            color: #fff;
        }
        .layui-table-edit:focus {
            border-color: #e6e6e6 !important
        }
        /** 协商撤销样式重写 **/
        .consult-pop  .layui-form-label {
            width: 160px !important
        }
        /* 条件过滤 */
        .layui-card .dl_nav {
            /*border: 1px solid #e6e6e6;*/
            padding: 20px 20px 0;
        }
        .layui-card .dl_nav .filter {
            width: 96%;
            float: left;
            margin-bottom: 20px;
        }
        .layui-card .dl_nav .filter li {
            min-width: 55px;
            height: 20px;
            padding: 0 5px;
            line-height: 20px;
            text-align: center;
            color: #333;
            cursor: pointer;
            float: left;
            margin: 0 5px;
            white-space: nowrap;
            box-sizing: border-box;
            overflow: hidden;
        }
        .layui-card .dl_nav .filter .islink {
            background-color: #198cff;
            color: #fff;
            border-radius: 3px;
        }
        .layui-card .dl_nav .filter .islink a{
            color: #fff;
        }
        .layui-card .dl_nav .choose {
            width: 4%;
            float: left;
            height: auto;
            color: #000;
            text-align: center;
            font-weight: 600;
        }
        .layui-card .layui-form {
            padding: 20px;
        }
        .layui-card .layui-form .layui-form-item {
            margin-bottom: 0;
        }
        .layui-card .layui-form .layui-form-item .layui-inline .layui-form-label {
            min-width: 40px;
            width: auto;
            text-align: left;
            box-sizing: border-box;
        }
        .layui-card .layui-form .layui-form-item .layui-inline .layui-input-inline {
            margin-right: 20px;
        }
        /*.layui-card .layui-form .layui-form-item .layui-inline .layui-input-inline input {*/
            /*height: 35px;*/
        /*}*/
        .layui-card .layui-form .layui-form-item .layui-inline .layui-input-inline .layui-form-select dl dd.layui-this {
            background-color: #198cff;
        }
        .layui-card .layui-form .layui-form-item .layui-inline .layui-btn {
            width: 80px;
            height: 35px;
            line-height: 35px;
            font-weight: 600;
        }
        .layui-card .layui-form .layui-form-item .query {
            margin-right: 0;
        }
        .layui-card .layui-table {
            margin-top: 0;
        }
        .layui-card .layui-table tbody tr td {
            text-align: left;
        }
        .layui-card .layui-table tbody tr td .item-title {
            font-weight: 600;
            color: #000;
        }
        .layui-card .layui-table tbody tr td .item-title span {
            display: inline-block;
            width: 24px;
            height: 20px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            text-align: center;
            line-height: 20px;
            border-radius: 3px;
        }
        .layui-card .layui-table tbody tr td .item-title .hot {
            background-color: #ff5722;
        }
        .layui-card .layui-table tbody tr td .item-title .credit {
            background-color: #009d8e;
        }
        .layui-card .layui-table tbody tr td .item-title .top {
            background-color: #ffb800;
        }
        .layui-card .layui-table tbody tr td .order_number {
            text-indent: 10px;
            color: #484848;
        }
        .layui-card .layui-table tbody tr td .layui-btn-sm {
            box-sizing: border-box;
            width: 60px;
            border-color: #198cff;
            color: #198cff;
            font-weight: 600;
            font-size: 14px;
        }
        .layui-card .layui-table tbody tr .price {
            font-size: 15px;
            font-weight: 600;
            color: #000;
        }
        .layui-card .layui-table tbody tr .red {
            color: red;
        }
        .btn-sm, .btn-sm a{
            box-sizing: border-box;
            width: 60px;
            height: 30px;
            background: #198cff;
            color: #fff;
            border: 1px solid #198cff;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
@endsection

@section('main')
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="dl_nav f-cb">
                <p class="game choose">游戏：</p>
                <ul class="game_filter filter">
                    <li @if(request('game_id') == 0) class="islink" @endif>
                        <a href="{{ route('order.wait-list', array_merge(['game_id' => 0], request()->except('game_id'))) }}">全部</a>
                    </li>
                    @foreach($games as $item)
                        <li @if(request('game_id') == $item->id) class="islink" @endif>
                            <a href="{{ route('order.wait-list', array_merge(['game_id' => $item->id], request()->except('game_id'))) }}">{{ $item->name }}</a>
                        </li>
                    @endforeach
                </ul>
                <p class="price choose">价格：</p>
                <ul class="price_filter filter">
                    <li @if(request('amount') == 0) class="islink" @endif>
                        <a href="{{ route('order.wait-list', array_merge(['amount' => 0], request()->except('amount'))) }}">全部</a>
                    </li>
                    <li @if(request('amount') == 1) class="islink" @endif>
                        <a href="{{ route('order.wait-list', array_merge(['amount' => 1], request()->except('amount'))) }}">10元以下</a>
                    </li>
                    <li @if(request('amount') == 2) class="islink" @endif>
                        <a href="{{ route('order.wait-list', array_merge(['amount' => 2], request()->except('amount'))) }}">10元-100元（含）</a>
                    </li>
                    <li @if(request('amount') == 3) class="islink" @endif>
                        <a href="{{ route('order.wait-list', array_merge(['amount' => 3], request()->except('amount'))) }}">100元-200元（含）</a>
                    </li>
                    <li @if(request('amount') == 4) class="islink" @endif>
                        <a href="{{ route('order.wait-list', array_merge(['amount' => 4], request()->except('amount'))) }}">200元以上</a>
                    </li>
                </ul>
            </div>
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <input type="hidden" name="game_id" value="{{ request('game_id') }}">
                    <div class="layui-inline">
                        <label class="layui-form-label">区</label>
                        <div class="layui-input-inline">
                            <select name="region_id" lay-filter="region">
                                <option value="">请选择</option>
                                @foreach($regions as $item)
                                    <option value="{{ $item->id }}" @if($item->id == request('region_id')) selected @endif>{{ $item->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">服</label>
                        <div class="layui-input-inline">
                            <select name="server_id" lay-filter="server">
                                <option value="">请选择</option>
                                @foreach($servers as $item)
                                    <option value="{{ $item->id }}" @if($item->id == request('server_id')) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">代练类型</label>
                        <div class="layui-input-inline">
                            <select name="game_leveling_type_id" lay-filter="">
                                <option value="">请选择</option>
                                @foreach($gameLevelingTypes as $item)
                                    <option value="{{ $item->id }}" @if($item->id == request('game_leveling_type_id')) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">其他</label>
                        <div class="layui-input-inline">
                            <input type="text" name="keyword" placeholder="订单号/发布人/标题" autocomplete="off" class="layui-input" value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="layui-inline query f-fr">
                        <button class="qs-btn" lay-submit="" lay-filter="query">查询</button>
                    </div>
                </div>
            </form>
            <table class="layui-table" lay-skin="line">
                <colgroup>
                    <col>
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>代练标题/订单号</th>
                    <th>游戏区服</th>
                    <th>代练价格</th>
                    <th>效率保证金</th>
                    <th>安全保证金</th>
                    <th>代练时间</th>
                    <th>发布人</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $item)
                    <tr>
                        <td>
                            <p class="item-title">【{{ $item->game_name }}】{{ $item->title }}
                                {{--<span class="hot f-ff1">热</span>--}}
                                @if($item->top)
                                    <span class="top f-ff1">顶</span>
                                @endif
                            </p>
                            <p class="order_number">订单号：{{ $item->trade_no }}</p>
                        </td>
                        <td>{{ $item->region_name }}/{{ $item->server_name }}</td>
                        <td class="price red">¥ {{ $item->amount }}</td>
                        <td class="price">¥ {{ $item->efficiency_deposit }}</td>
                        <td class="price">￥{{ $item->security_deposit }}</td>
                        <td>{{ $item->day ? $item->day . '天' : '' }}{{ $item->hour }}小时</td>
                        <td>{{ $item->parent_username}}</td>
                        <td>
                            <button class="layui-btn layui-btn-primary layui-btn-sm"
                                    lay-submit lay-filter="detail"
                                    data-trade_no="{{ $item->trade_no }}"
                                    data-take_password="{{ $item->take_order_password ? 1 : 2 }}"
                                    data-guest="{{ $guest }}"
                            >接单</button>
                        </td>
                    </tr>
                @empty

                @endforelse
                </tbody>
            </table>
            {{ $orders->appends(request()->all())->links('front.pagination.default') }}
        </div>
    </div>
@endsection


@section('pop')
    @include('front.order-operation.take-order-pop')
@endsection

@section('js')
    @include('front.order-operation.take-order-js')
@endsection



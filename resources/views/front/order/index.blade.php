@extends('front.layouts.home')

@section('title', ' - 接单中心')

@section('css')
    <link rel="stylesheet" href="/front/css/order-receiving.css">
@endsection

@section('main')
<div class="main">
    <div class="container">
        <div class="dl_nav f-cb">
            <p class="game choose">游戏：</p>
            <ul class="game_filter filter">
                <li @if(request('game_id') == 0) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['game_id' => 0], request()->except('game_id'))) }}">全部</a>
                </li>
                @foreach($games as $item)
                <li @if(request('game_id') == $item->id) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['game_id' => $item->id], request()->except('game_id'))) }}">{{ $item->name }}</a>
                </li>
                @endforeach
            </ul>
            <p class="price choose">价格：</p>
            <ul class="price_filter filter">
                <li @if(request('amount') == 0) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['amount' => 0], request()->except('amount'))) }}">全部</a>
                </li>
                <li @if(request('amount') == 1) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['amount' => 1], request()->except('amount'))) }}">10元以下</a>
                </li>
                <li @if(request('amount') == 2) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['amount' => 2], request()->except('amount'))) }}">10元-100元（含）</a>
                </li>
                <li @if(request('amount') == 3) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['amount' => 3], request()->except('amount'))) }}">100元-200元（含）</a>
                </li>
                <li @if(request('amount') == 4) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['amount' => 4], request()->except('amount'))) }}">200元以上</a>
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
                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="query">查询</button>
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
    @include('front.profile.password-pop')
@endsection

@section('js')
    @include('front.order-operation.take-order-js', [
        'js' => 'front.profile.password-js'
    ])
@endsection



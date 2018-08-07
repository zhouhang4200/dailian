@extends('back.layouts.app')

@section('title', ' | 订单详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/css/libs/nifty-component.css"/>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class=""><span>首页</span></li>
                <li class=""><a href="{{ route('admin.game-leveling-order') }}"><span>平台订单</span></a></li>
                <li class="active"><span>订单详情</span></li>
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
                                <li  lay-id="detail"><a href="{{ route('admin.game-leveling-order.show', ['trade_no' => request('trade_no')])  }}">订单内容</a></li>
                                <li  @if(Route::currentRouteName() == 'admin.game-leveling-order.log') class="layui-this"  @endif lay-id="authentication">订单日志</li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="layui-tab-item content">
                                </div>
                                <table class="layui-table" lay-size="sm">
                                        <thead>
                                            <tr>
                                                <td width="13%">订单号</td>
                                                <td>操作人</td>
                                                <td>操作名</td>
                                                <td>操作说明</td>
                                                <td>时间</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($logs as $item)
                                            <tr>
                                                <td>{{ $item->game_leveling_order_trade_no }}</td>
                                                <td>{{ $item->username }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->created_at }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="999">没有数据</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

    </div>

    <div class="md-overlay"></div>
@endsection

@section('js')
@endsection

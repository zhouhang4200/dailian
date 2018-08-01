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
                                <li  class="layui-this"  lay-id="detail"><a href="{{ route('admin.game-leveling-order.show', ['trade_no' => request('trade_no')])  }}">订单内容</a></li>
                                <li lay-id="authentication"><a href="{{ route('admin.game-leveling-order.log', ['trade_no' => request('trade_no')])  }}">订单日志</a></li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="layui-tab-item layui-show content">
                                    <div class="tab-pane active" id="tab-user">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="main-box clearfix" style="border: 1px solid #ddd">

                                                            <div class="main-box-body clearfix">
                                                              
                                                                <div id="invoice-companies" class="row">
                                                                    <div class="col-sm-6 invoice-box">
                                                                        <div class="invoice-company">
                                                                            <h4>发单方</h4>
                                                                            <ul class="fa-ul">
                                                                                <li><i class="fa-li fa fa-truck"></i>主用户ID:
                                                                                    <span>{{ $order->parent_user_id }}</span>
                                                                                </li>
                                                                                <li><i class="fa-li fa fa-truck"></i>主用户名:
                                                                                    <span>{{ $order->parent_username }}</span>
                                                                                </li>
                                                                                <li><i class="fa-li fa fa-comment"></i>操作用户ID:
                                                                                    <span>{{ $order->user_id }}</span>
                                                                                </li>
                                                                                <li><i class="fa-li fa fa-tasks"></i>操作用户名:
                                                                                    <span>{{ $order->username }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 invoice-box">
                                                                        <div class="invoice-company">
                                                                            <h4>接单方</h4>
                                                                            <ul class="fa-ul">
                                                                                <li><i class="fa-li fa fa-truck"></i>主用户ID:
                                                                                    <span>{{ $order->take_parent_user_id }}</span>
                                                                                </li>
                                                                                <li><i class="fa-li fa fa-truck"></i>主用户名:
                                                                                    <span>{{ $order->take_parent_username }}</span>
                                                                                </li>
                                                                                <li><i class="fa-li fa fa-comment"></i>操作用户ID:
                                                                                    <span>{{ $order->take_user_id }}</span>
                                                                                </li>
                                                                                <li><i class="fa-li fa fa-tasks"></i>操作用户名:
                                                                                    <span>{{ $order->take_username }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped table-hover">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>订单号</td>
                                                                            <td>{{ $order->trade_no }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>状态</td>
                                                                            <td>{{ $order->getStatusDescribe() }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>游戏/区/服</td>
                                                                            <td>{{ $order->game_name }} {{ $order->region_name }} {{ $order->server_name }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>游戏账号</td>
                                                                            <td>{{ $order->game_account }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>游戏角色</td>
                                                                            <td>{{ $order->game_role }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>标题</td>
                                                                            <td>{{ $order->title }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>金额</td>
                                                                            <td>{{ $order->amount }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>安全/效率(保证金)</td>
                                                                            <td>{{ $order->security_deposit }} / {{ $order->efficiency_deposit }}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>代练(天/小时)</td>
                                                                            <td>{{ $order->day }} 天 {{ $order->hour }} 小时</td>
                                                                        </tr>

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
                                </div>
                                <div class="layui-tab-item record"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md-overlay"></div>
@endsection

@section('js')
@endsection

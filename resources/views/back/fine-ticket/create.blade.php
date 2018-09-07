@extends('back.layouts.app')

@section('title', ' | 添加罚款')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">


                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">添加罚款</li>
                        </ul>
                        <div class="layui-tab-content">

                            @if(Session::has('success'))
                            <div class="col-lg-12">
                                <div class="alert alert-block alert-success fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4>{{ \Session::get('success', 'default') }}</h4>
                                </div>
                            </div>
                            @endif

                            @if(Session::has('fail'))
                                <div class="col-lg-12">
                                    <div class="alert alert-block alert-danger fade in">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4>{{ \Session::get('fail', 'default') }}</h4>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-8">
                                <div class="main-box-body clearfix">
                                    <form role="form" class="layui-form" href="{{ route('admin.user.fine-ticket.create') }}" method="post">
                                        {!! csrf_field() !!}

                                        <div class="form-group">
                                            <label for="">*被罚款方ID</label>
                                            <select name="user_id" lay-verify="required">
                                                <option value=""></option>
                                                @foreach($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">罚款关联订单号</label>
                                            <input type="text" lay-verify="" class="form-control" name="relation_trade_no">
                                        </div>

                                        <div class="form-group">
                                            <label for="">*罚款金额</label>
                                            <input type="text" lay-verify="required" class="form-control" name="amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="">*罚款原因</label>
                                            <textarea type="text" lay-verify="required" class="form-control" name="reason"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="">备注</label>
                                            <textarea type="text" lay-verify="" class="form-control" name="remark"></textarea>
                                        </div>

                                        <button class="btn btn-success" lay-submit="" lay-filter="store">确认</button>
                                        <a  href="{{ route('admin.user.fine-ticket') }}" type="button" class="layui-btn layui-btn-normal ">返回列表</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
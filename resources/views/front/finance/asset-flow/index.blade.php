@extends('front.layouts.app')

@section('title', '财务 - 资金流水')

@section('css')
    <style>
        .layui-form-item .layui-inline {
            margin-bottom: 5px;
            margin-right: 5px;
        }
        .layui-form-mid {
            margin-right: 4px;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <form class="layui-form" id="search">
                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-mid">类型：</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-search="">
                                <option value="">所有类型</option>
                                @foreach (config('user_asset.type') as $key => $value)
                                    <option value="{{ $key }}" {{ $key == request('type') ? 'selected' : '' }}> {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-mid">说明：</label>
                        <div class="layui-input-inline">
                            <select name="sub_type" lay-search="">
                                <option value="">请选择</option>
                                @foreach (config('user_asset.sub_type') as $key => $value)
                                    <option value="{{ $key }}" {{ $key ==  request('sub_type') ? 'selected' : '' }}> {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-mid">交易单号：</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="trade_no" placeholder="订单号" value="{{ request('trade_no') }}">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-mid">时间：</label>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="start-time" name="start_time" autocomplete="off" value="{{ request('start_time') }}" placeholder="开始时间">
                        </div>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="end-time" name="end_time" autocomplete="off" value="{{ request('end_time') }}" placeholder="结束时间">
                        </div>
                        <button class="qs-btn layui-btn-normal" type="submit">查询</button>
                        <button class="qs-btn layui-btn-normal" type="button" id="export">导出</button>
                    </div>
                </div>
            </form>

            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col width="150">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>流水号</th>
                    <th>交易单号</th>
                    <th>类型</th>
                    <th>变动金额</th>
                    <th>账户余额</th>
                    <th>说明</th>
                    <th>时间</th>
                </tr>
                </thead>
                <tbody>
                @forelse($assetFlow as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->trade_no }}</td>
                        <td>{{ config('user_asset.type')[$item->type] }}</td>
                        <td>{{ $item->amount + 0 }}</td>
                        <td>{{ $item->balance + 0 }}</td>
                        <td>{{ config('user_asset.sub_type')[$item->sub_type] }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="999">暂时没有数据</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $assetFlow->appends(request()->all())->links() }}
            @endsection
        </div>
    </div>
@section('js')
    <script>
        $('#export').click(function () {
            window.location.href = "{{ route('finance.asset-flow.export') }}?" + $('#search').serialize();
        });
    </script>
@endsection

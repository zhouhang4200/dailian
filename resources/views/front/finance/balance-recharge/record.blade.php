@extends('front.layouts.app')

@section('title', '财务 - 我的提现')

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
                        <label class="layui-form-mid">充值方式：</label>
                        <div class="layui-input-inline">
                            <select name="source" lay-search="">
                                <option value="">所有方式</option>
                                @foreach(config('user_asset.recharge_source') as $k => $v)
                                    <option value="{{ $k }}" {{ $k == request('source') ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-mid">时间：</label>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="start-time"  autocomplete="off" name="start_time" value="{{ request('start_time') }}" placeholder="开始时间">
                        </div>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="end-time" autocomplete="off" name="end_time" value="{{ request('end_time') }}" placeholder="结束时间">
                        </div>
                        <button class="qs-btn layui-btn-normal" type="submit">查询</button>
                        <button class="qs-btn layui-btn-normal" type="button" id="export">导出</button>
                    </div>
                </div>
            </form>

            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>充值单号</th>
                    <th>充值金额</th>
                    <th>充值方式</th>
                    <th>充值时间</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recharges as $item)
                    <tr>
                        <td>{{ $item->trade_no }}</td>
                        <td>{{ $item->amount + 0 }}</td>
                        <td>{{ config('user_asset.recharge_source')[$item->source] }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="999">暂时没有数据</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $recharges->appends(request()->all())->links() }}
            @endsection
        </div>
    </div>
@section('js')
    <script>
        $('#export').click(function () {
            window.location.href = "{{ route('finance.balance-recharge.export') }}?" + $('#search').serialize();
        });
    </script>
@endsection

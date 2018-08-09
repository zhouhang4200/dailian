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
                        <label class="layui-form-mid">类型：</label>
                        <div class="layui-input-inline">
                            <select name="status" lay-search="">
                                <option value="">所有类型</option>
                                @foreach (config('user_asset.withdraw_status') as $key => $value)
                                    <option value="{{ $key }}" {{ $key == request('status') ? 'selected' : '' }}> {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-mid">时间：</label>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="time-start"  autocomplete="off" name="start_time" value="{{ request('start_time') }}" placeholder="开始时间">
                        </div>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="time-end" autocomplete="off" name="end_time" value="{{ request('end_time') }}" placeholder="结束时间">
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
                    <th>提现单号</th>
                    <th>提现金额</th>
                    <th>状态</th>
                    <th>备注</th>
                    <th>创建时间</th>
                </tr>
                </thead>
                <tbody>
                @forelse($withdraws as $item)
                    <tr>
                        <td>{{ $item->trade_no }}</td>
                        <td>{{ $item->amount + 0 }}</td>
                        <td>{{ config('user_asset.withdraw_status')[$item->status] }}</td>
                        <td>{{ $item->remark }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="999">暂时没有数据</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $withdraws->appends(request()->all())->links() }}
            @endsection
        </div>
    </div>
@section('js')
    <script>
        layui.use(['laydate', 'form'], function () {
            var laydate = layui.laydate;

            laydate.render({elem: '#time-start'});
            laydate.render({elem: '#time-end'});
        });

        $('#export').click(function () {
            window.location.href = "{{ route('finance.balance-withdraw.export') }}?" + $('#search').serialize();
        });
    </script>
@endsection

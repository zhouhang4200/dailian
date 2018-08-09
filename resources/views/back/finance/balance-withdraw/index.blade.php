@extends('back.layouts.app')

@section('title', ' | 用户提现管理')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">用户提现管理</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form id="search-flow" action="">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" id="start-time" name="start_time" value="{{ request('start_time') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" id="end-time" name="end_time" value="{{ request('end_time') }}">
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                    <select class="form-control" name="status">
                                        <option value="">所有状态</option>
                                        @foreach (config('user_asset.withdraw_status') as $key => $value)
                                            <option value="{{ $key }}" {{ $key == request('status') ? 'selected' : '' }}>{{ $key }}. {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder="相关单号" name="trade_no" value="{{ request('trade_no') }}">
                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control" placeholder="用户ID" name="user_id" value="{{ request('user_id') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder="拒绝原因" name="remark" value="{{ request('remark') }}">
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
                                <th>提现单号</th>
                                <th>主账号ID</th>
                                <th>当前余额</th>
                                <th>当前冻结</th>
                                <th>姓名</th>
                                <th>开户行</th>
                                <th>卡号</th>
                                <th>提现金额</th>
                                <th>状态</th>
                                <th>拒绝原因</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($balanceWithdraws as $balanceWithdraw)
                                <tr>
                                    <td>{{ $balanceWithdraw->trade_no }}</td>
                                    <td>{{ $balanceWithdraw->user_id }}</td>
                                    <td>{{ optional(optional($balanceWithdraw->userAssetFlows)[0])->balance }}</td>
                                    <td>{{ optional(optional($balanceWithdraw->userAssetFlows)[0])->frozen }}</td>
                                    <td>{{ $balanceWithdraw->real_name ?? '' }}</td>
                                    <td>{{ $balanceWithdraw->bank_name ?? '' }}</td>
                                    <td>{{ $balanceWithdraw->bank_card ?? '' }}</td>
                                    <td>{{ $balanceWithdraw->amount+0 }}</td>
                                    <td>{{ config('user_asset.withdraw_status')[$balanceWithdraw->status] }}</td>
                                    <td>{{ $balanceWithdraw->remark ?? '--' }}</td>
                                    <td>{{ $balanceWithdraw->created_at }}</td>
                                    <td>{{ $balanceWithdraw->updated_at }}</td>
                                    <td>
                                        @if ($balanceWithdraw->status == 1)
                                            <button type="button" class="layui-btn layui-btn-normal layui-btn-mini complete" data-id="{{ $balanceWithdraw->id }}">完成</button>
                                            <button type="button" class="layui-btn layui-btn-mini layui-btn-danger refuse" data-id="{{ $balanceWithdraw->id }}">拒绝</button>
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                                @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $balanceWithdraws->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        layui.use(['layer'], function () {
            // 拒绝
            $('.refuse').click(function () {
                var id = $(this).data('id');
                layer.confirm('拒绝提现？' , function (layerConfirm) {
                    layer.close(layerConfirm);
                    layer.prompt({title: '请输入备注',formType: 2},function(value, promptIndex, elem){
                        $.post("{{ route('admin.balance-withdraw.refuse') }}", {remark:value, id:id},function (data) {
                            if (data.status === 1) {
                                layer.alert('操作成功', function () {
                                    location.reload();
                                });
                            } else {
                                layer.alert(data.message, function (index) {
                                    layer.close(index);
                                });
                            }
                            layer.close(promptIndex);
                        }, 'json');
                    });
                });
            });

            // 同意
            $('.complete').click(function () {
                var id = $(this).data('id');
                layer.confirm('同意提现？' , function (layerConfirm) {
                    $.post("{{ route('admin.balance-withdraw.agree') }}", {id:id}, function (data) {
                        layer.close(layerConfirm);
                        if (data.status === 1) {
                            layer.alert('操作成功', function () {
                                location.reload();
                            });
                        } else {
                            layer.alert(data.message);
                        }
                    }, 'json');
                });
            });
        });

        $('#export').click(function () {
            var has="{{ $balanceWithdraws }}";
            if (has) {
                window.location.href = "{{ route('admin.balance-withdraw.export') }}?" + $('#search-flow').serialize();
            }
        });
    </script>
@endsection

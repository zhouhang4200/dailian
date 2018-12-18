<form class="layui-form">
    <table class="layui-table layui-form" lay-size="sm">
        <thead>
        <tr>
            <th>账号ID</th>
            <th>昵称</th>
            <th>状态</th>
            <th>手机号</th>
            <th>可用余额</th>
            <th>冻结余额</th>
            <th>注册时间</th>
            <th>最后登录时间</th>
            <th>实名认证</th>
            <th>发单手续费比例</th>
            <th>接单手续费比例</th>
            <th>推广比例</th>
            <th style="text-align: center">操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>
                    @if($user->status == 1)
                        正常
                    @elseif($user->status == 2)
                        已封号
                    @elseif($user->status == 3)
                        已删除
                    @else
                        未知
                    @endif
                </td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->userAsset ? $user->userAsset->balance+0 : 0 }}</td>
                <td>{{ $user->userAsset ? $user->userAsset->frozen+0 : 0 }}</td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->last_login_at }}</td>
                <td>
                    @if ($user->realNameCertification && $user->realNameCertification->status === 1)
                        <span style="color: #95a5a6">待审核</span>
                    @elseif ($user->realNameCertification && $user->realNameCertification->status === 2)
                        <span style="color: #2980b9">通过</span>
                    @elseif ($user->realNameCertification && $user->realNameCertification->status === 3)
                        <span style="color: #cc0000">拒绝</span>
                    @else
                        --
                    @endif
                </td>
                <td>{{ $user->userPoundage && $user->userPoundage->send_poundage ? $user->userPoundage->send_poundage : '默认' }}</td>
                <td>{{ $user->userPoundage && $user->userPoundage->take_poundage ? $user->userPoundage->take_poundage : '默认' }}</td>
                <td>{{ $user->userSpread ? $user->userSpread->spread_rate : '默认' }}</td>
                <td style="text-align: center;">
                    <button lay-name="{{ $user->name  }}" lay-id="{{ $user->id }}"  send-poundage="{{ $user->userPoundage ? $user->userPoundage->send_poundage : 0 }}"
                            take-poundage="{{ $user->userPoundage ? $user->userPoundage->take_poundage : 0 }}" spread-rate="{{ $user->userSpread ? $user->userSpread->spread_rate : 0 }}"
                            class="btn btn-success" lay-submit="" lay-filter="setting">设置</button>
                    <button lay-id="{{ $user->id }}" class="btn btn-success" lay-submit="" lay-filter="detail">详情</button>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</form>
<div class="row">
    <div class="col-xs-3">
        总数：{{ $users->total() }}　本页显示：{{ $users->count() }}
    </div>
    <div class="col-xs-9">
        <div class=" pull-right">
            {!! $users->appends(compact('id', 'name', 'phone'))->render() !!}
        </div>
    </div>
</div>

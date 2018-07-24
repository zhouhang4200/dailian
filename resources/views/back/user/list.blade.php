<div class="main-box-body clearfix">
    <div class="row">
        <div class="col-xs-3">
            总数：{{ $users->total() }}　本页显示：{{  $users->count() }}
        </div>
        <div class="col-xs-9">
        </div>
    </div>
    <form class="layui-form">
        <table class="layui-table layui-form" lay-size="sm">
            <thead>
            <tr>
                <th>账号ID</th>
                <th>昵称</th>
                <th>手机号</th>
                <th>可用余额</th>
                <th>冻结余额</th>
                <th>注册时间</th>
                <th>最后登录时间</th>
                <th>实名认证</th>
                <th style="text-align: center">操作</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->userAsset->balance+0 }}</td>
                    <td>{{ $user->userAsset->frozen+0 }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->last_login_at }}</td>
                    <td>
                        @if ($user->realNameCertification && $user->realNameCertification->status === 1)
                            <span style="color: #95a5a6">待审核</span>
                        @elseif ($user->realNameCertification && $user->realNameCertification->status === 2)
                            <span style="color: #00F7DE">通过</span>
                        @elseif ($user->realNameCertification && $user->realNameCertification->status === 3)
                            <span style="color: #cc0000">拒绝</span>
                        @else
                            --
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.user.show', ['id' => $user->id])  }}" class="layui-btn layui-btn layui-btn-normal layui-btn-mini">详情</a>
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
</div>
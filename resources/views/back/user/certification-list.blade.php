<form class="layui-form">
    <table class="layui-table layui-form" lay-size="sm">
        <thead>
        <tr>
            <th>序号</th>
            <th>用户名</th>
            <th>真实姓名</th>
            <th>开户银行名称</th>
            <th>银行卡号</th>
            <th>邮箱</th>
            <th>状态</th>
            <th>申请认证时间</th>
            <th style="text-align: center">操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse($certifications as $certification)
            <tr>
                <td>{{ $certification->id }}</td>
                <td>{{ $certification->user->name }}</td>
                <td>{{ $certification->real_name }}</td>
                <td>{{ $certification->bank_name }}</td>
                <td>{{ $certification->bank_card }}</td>
                <td>{{ $certification->user->email }}</td>
                <?php $statusName = ['1' => '审核中', '2' => '通过', 3 => '拒绝']; ?>
                <td>{{ $statusName[$certification->status] }}</td>
                <td>{{ $certification->created_at }}</td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.user.certification-show', ['id' => $certification->id])  }}" class="layui-btn layui-btn layui-btn-normal layui-btn-mini">详情</a>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</form>
<div class="row">
    <div class="col-xs-3">
        总数：{{ $certifications->total() }}　本页显示：{{ $certifications->count() }}
    </div>
    <div class="col-xs-9">
        <div class=" pull-right">
            {!! $certifications->appends(compact('name', 'status', 'startDate', 'endDate'))->render() !!}
        </div>
    </div>
</div>

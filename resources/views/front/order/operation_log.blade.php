<table class="layui-table" lay-size="sm">
    <thead>
    <tr>
        <th width="8%">操作人</th>
        <th width="10%">操作名</th>
        <th>描述</th>
        <th width="15%">时间</th>
    </tr>
    </thead>
    <tbody>
    @forelse($operationLog as $item)
        <tr>
            <td>{{ $item->username }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->created_at }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10">空</td>
        </tr>
    @endforelse
    </tbody>
</table>

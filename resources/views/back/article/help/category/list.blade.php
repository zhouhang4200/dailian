<from class="layui-form">
    <table class="layui-table" lay-size="sm">
        <thead>
        <tr>
            <th>序号</th>
            <th>分类</th>
            <th>显示</th>
            <th style="width: 13%">操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->sort }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->status == 1 ? '是' : '否' }}</td>
                <td>
                    <a type="button" class="layui-btn layui-btn-normal layui-btn-mini edit" href="{{ route('admin.article.category-help-edit', ['id' => $category->id]) }}" data-id="{{ $category->id }}">编辑</a>
                    <button type="button" id="help" class="layui-btn layui-btn-normal layui-btn-mini" category-id="{{ $category->id }}">问题管理</button>
                    <button lay-submit="" lay-filter="delete" class="layui-btn layui-btn-mini layui-btn-danger delete" lay-id="{{ $category->id }}">删除</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center">暂无数据</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</from>
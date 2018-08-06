<from class="layui-form">
    <table class="layui-table" lay-size="sm">
        <thead>
        <tr>
            <th style="width: 5%">序号</th>
            <th>分类</th>
            <th style="width: 5%">显示</th>
            <th style="width: 20%">操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->sort }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->status == 1 ? '是' : '否' }}</td>
                <td>
                    <a type="button" class="layui-btn layui-btn-normal edit" href="{{ route('admin.article.category-help-edit', ['id' => $category->id]) }}" data-id="{{ $category->id }}">编辑</a>
                    <button lay-submit="" lay-filter="help" class="layui-btn layui-btn-normal" category-id="{{ $category->id }}">问题管理</button>
                    <button lay-submit="" lay-filter="category-delete" class="layui-btn layui-btn-danger delete" lay-id="{{ $category->id }}">删除</button>
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
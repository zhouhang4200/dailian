<from class="layui-form">
    <table class="layui-table" lay-size="sm">
        <thead>
        <tr>
            <th>序号</th>
            <th>标题</th>
            <th style="width: 8%">操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($articles as $article)
            <tr>
                <td>{{ $article->sort }}</td>
                <td>{{ $article->title }}</td>
                <td>
                    <a type="button" class="layui-btn layui-btn-normal layui-btn-mini edit" href="{{ route('admin.article.notice-edit', ['id' => $article->id]) }}?category_id={{ $categoryId }}" data-id="{{ $article->id }}" category-id="{{ $categoryId }}">编辑</a>
                    <button lay-submit="" lay-filter="delete" class="layui-btn layui-btn-mini layui-btn-danger delete" lay-id="{{ $article->id }}" category-id="{{ $categoryId }}">删除</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="text-align: center">暂无数据</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</from>
<from class="layui-form">
    <table class="layui-table" lay-size="sm">
        <thead>
        <tr>
            <th style="width: 5%">序号</th>
            <th>标题</th>
            <th style="width: 12%">操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($articles as $article)
            <tr>
                <td>{{ $article->sort }}</td>
                <td>{{ $article->title }}</td>
                <td>
                    <a class="btn btn-success edit" href="{{ route('admin.article.help-edit', ['id' => $article->id]) }}?category_id={{ $categoryId }}" data-id="{{ $article->id }}" category-id="{{ $categoryId }}">编辑</a>
                    <button lay-submit="" lay-filter="article-delete" class="btn btn-danger delete" lay-id="{{ $article->id }}" category-id="{{ $categoryId }}">删除</button>
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
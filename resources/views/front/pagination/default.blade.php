@if ($paginator->hasPages())
    <div class="layui-box layui-laypage layui-laypage-molv">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a href="javascript:;" class="layui-laypage-prev layui-disabled" data-page="0">上一页</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="layui-laypage-prev" data-page="1">上一页</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a href="javascript:;" class="layui-laypage-prev layui-disabled" data-page="0">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="layui-laypage-curr">
                            <em class="layui-laypage-em" style="background-color:#198cff;"></em><em>{{ $page }}</em>
                        </span>
                    @else
                        <a href="{{ $url }}" data-page="3">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="layui-laypage-prev" data-page="1">下一页</a>
        @else
            <a href="javascript:;" class="layui-laypage-prev layui-disabled" data-page="0">下一页</a>
        @endif
    </div>
@endif

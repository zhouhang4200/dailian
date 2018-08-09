<ul>
    @foreach($messages as $item)
        @if($item->from_user_id == request()->user()->parent_id)
            <li class="layim-chat-mine">
                <div class="layim-chat-user">
                    <img src="/front/images/service_avatar.jpg">
                    <cite>
                        <i>{{ $item->created_at }}</i>您
                    </cite>
                </div>
                <div class="layim-chat-text">{{ $item->content }}</div>
            </li>
        @else
            <li>
                <div class="layim-chat-user">
                    <img src="/front/images/customer_avatar.jpg">
                    <cite>对方
                        <i>{{ $item->created_at }}</i>
                    </cite>
                </div>
                <div class="layim-chat-text">{{ $item->content }}</div>
            </li>
        @endif
    @endforeach
</ul>

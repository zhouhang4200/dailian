<html>
<header>
    <meta name="_token" content="{{ csrf_token() }}" >
    <link rel="stylesheet" href="/front/lib/js/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/front/lib/css/admin.css" media="all">
    <link rel="stylesheet" href="/front/lib/css/new.css">
    <link id="layuicss-layer" rel="stylesheet" href="/front/lib/js/layui/css/modules/layer/default/layer.css" media="all">
</header>
<style>
    body {
        background-color: #fff;
    }
    .title {
        height: 30px;
        line-height: 30px;
    }
    .layui-input,.layui-textarea {
       background-color: #f7f7f7;
    }
</style>
<body>
<form class="layui-form" action="" lay-filter="component-form-group" id="form-order" style="padding: 20px 30px 0 0;">

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm4">
            <label class="layui-form-label">订单号</label>
            <div class="layui-input-block">
                <div class="title">{{ $detail['trade_no'] }}</div>
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">发布时间</label>
            <div class="layui-input-block">
                <div class="title">{{ $detail['created_at'] }}</div>
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">订单状态</label>
            <div class="layui-input-block">
                <div class="title">{{ \App\Models\GameLevelingOrder::$statusDescribe[$detail['status']] }}</div>
            </div>
        </div>
    </div>
    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">订单标题</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $detail['title'] }}">
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm4">
            <label class="layui-form-label">游戏</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $detail['game_name'] }}">
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">区</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $detail['region_name'] }}">
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm4">
            <label class="layui-form-label">价格</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $detail['amount'] }}">
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">安全证金</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $detail['security_deposit'] }}">
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">效率保证金</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $detail['efficiency_deposit'] }}">
            </div>
        </div>
    </div>


    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">代练要求</label>
            <div class="layui-input-block">
                <textarea type="text" class="layui-textarea" >{{ $detail['requirement'] }}</textarea>
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">代练说明</label>
            <div class="layui-input-block">
                <textarea type="text" class="layui-textarea" >{{ $detail['explain'] }}</textarea>
            </div>
        </div>
    </div>

    <div  style="text-align: center;">
        @if($detail['status'] == 1)
            <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="take" data-trade_no="{{ $detail['trade_no'] }}">立即接单</button>
        @else

        @endif

    </div>
</form>
@include('front.order-operation.take-order-pop')
@include('front.profile.password-pop')
</body>
<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/js/encrypt.js"></script>
<script src="/front/js/helper.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
</script>
@include('front.order-operation.take-order-js', [
    'js' => 'front.profile.password-js'
])
</html>
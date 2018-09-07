<html>
<header>
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
                <div class="title">sdf</div>
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">发布时间</label>
            <div class="layui-input-block">
                <div class="title">sdf</div>
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">订单状态</label>
            <div class="layui-input-block">
                <div class="title">sdf</div>
            </div>
        </div>
    </div>
    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">订单标题</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm4">
            <label class="layui-form-label">游戏</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">区</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm4">
            <label class="layui-form-label">游戏</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">区</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
        <div class="layui-col-sm4">
            <label class="layui-form-label">区</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">订单标题</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="sdfsdfsdf">
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">订单标题</label>
            <div class="layui-input-block">
                <textarea type="text" class="layui-textarea" value="sdfsdfsdf"></textarea>
            </div>
        </div>
    </div>

    <div class="layui-row  layui-form-item">
        <div class="layui-col-sm12">
            <label class="layui-form-label">订单标题</label>
            <div class="layui-input-block">
                <textarea type="text" class="layui-textarea" value="sdfsdfsdf"></textarea>
            </div>
        </div>
    </div>

    <div  style="text-align: center;">
        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="take">立即接单</button>
    </div>
</form>
@include('front.order-operation.take-order-pop')
@include('front.profile.password-pop')
</body>
<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/front/lib/js/layui/layui.js"></script>
@include('front.order-operation.take-order-js', [
    'js' => 'front.profile.password-js'
])
</html>
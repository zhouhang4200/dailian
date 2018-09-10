<style>
    body {
        background-color: #fff;
    }
    .title {
        height: 30px;
        line-height: 36px;
    }
    .layui-input,.layui-textarea {
       background-color: #f7f7f7;
    }
</style>
<form class="layui-form" action="" lay-filter="component-form-group" id="form-order" style="padding: 20px 30px 20px 0;">

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
            <label class="layui-form-label">安全保证金</label>
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

<div id="take-pop" style="padding: 24px 0 15px 15px;display: none">
    <form class="layui-form" action="" method="post">
        <input type="hidden" name="trade_no">
        <div class="layui-form-item">
            <label class="layui-form-label">接单密码</label>
            <div class="layui-input-inline">
                <input type="password" name="take_password" required lay-verify="required" placeholder="请输入支付密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付密码</label>
            <div class="layui-input-inline">
                <input type="password" name="pay_password" required lay-verify="required" placeholder="请输入支付密码" autocomplete="off" class="layui-input">
            </div>
            @if(Auth::user()->isParent())
            <div class="layui-form-mid layui-word-aux reset-pay-password" id="">忘记密码</div>
            @endif
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="confirm-take">确定</button>
                <button type="reset" class="layui-btn layui-btn-primary">取消</button>
            </div>
        </div>
    </form>
</div>
<div id="take-no-password-pop" style="padding: 24px 0 15px 15px;display: none">
    <form class="layui-form" action="" method="post">
        <input type="hidden" name="trade_no">
        <div class="layui-form-item">
            <label class="layui-form-label">支付密码</label>
            <div class="layui-input-inline">
                <input type="password" name="pay_password" required lay-verify="required" placeholder="请输入支付密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux reset-pay-password" id="">忘记密码</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="qs-btn" lay-submit lay-filter="confirm-take">确定</button>
                <button type="reset" class="qs-btn cancel">取消</button>
            </div>
        </div>
    </form>
</div>
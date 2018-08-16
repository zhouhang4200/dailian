<div id="change-password-pop" style="padding: 24px 45px 15px 15px;display: none">
    <form class="layui-form" action="" method="post">
        <input type="hidden" name="trade_no">
        <div class="layui-form-item">
            <label class="layui-form-label">原密码</label>
            <div class="layui-input-inline">
                <input type="password" name="old_password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="repeat_password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="qs-btn" lay-submit lay-filter="confirm-change-password">确定</button>
                <button type="reset" class="qs-btn cancel">取消</button>
            </div>
        </div>
    </form>
</div>
<div id="change-pay-password-pop" style="padding: 24px 45px 15px 15px;display: none">
    <form class="layui-form" action="" method="post">
        <input type="hidden" name="trade_no">
        <div class="layui-form-item">
            <label class="layui-form-label">原密码</label>
            <div class="layui-input-inline">
                <input type="password" name="old_password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="repeat_password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="qs-btn" lay-submit lay-filter="confirm-change-pay-password">确定</button>
                <button type="reset" class="qs-btn cancel">取消</button>
            </div>
        </div>
    </form>
</div>
<div id="set-pay-password-pop" style="padding: 24px 45px 15px 15px;display: none">
    <form class="layui-form" action="" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="repeat_password" required lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="qs-btn" lay-submit lay-filter="confirm-set-pay-password">确定</button>
                <button type="reset" class="qs-btn cancel">取消</button>
            </div>
        </div>
    </form>
</div>

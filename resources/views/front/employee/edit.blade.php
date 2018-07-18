@extends('front.layouts.app')

@section('title', '账号 - 员工管理 - 员工编辑')

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-header">员工编辑</div>
        <div class="layui-card-body">
            <form class="layui-form" method="" action="">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">
                <div class="layui-form-item">
                    <label class="layui-form-label">员工姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="required|length" value="{{ $user->name }}" autocomplete="off" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" disabled="disabled" value="{{ $user->phone }}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" value="" lay-verify="" placeholder="不填写则为原密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">支付密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="pay_password" value="" lay-verify="" placeholder="不填写则为原密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">岗位</label>
                    <div class="layui-input-block">
                        @forelse($userRoles as $userRole)
                            <input type="checkbox" name="roles" value="{{ $userRole->id }}" lay-skin="primary" title="{{ $userRole->alias }}" {{ $user->roles && in_array($userRole->id, $user->roles->pluck('id')->flatten()->toArray()) ? 'checked' : '' }} >
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">*QQ号</label>
                    <div class="layui-input-block">
                        <input type="text" name="qq" value="{{ $user->qq }}" lay-verify="required|number" placeholder="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">*微信号</label>
                    <div class="layui-input-block">
                        <input type="text" name="wechat" value="{{ $user->wechat }}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="qs-btn qs-btn-normal" lay-submit="" lay-id="{{ $user->id }}" lay-filter="update">确认</button>
                        <a type="button" class="qs-btn qs-btn-primary cancel" >取消</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        layui.use('form', function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;

            form.verify({
                length: [
                    /^\S{1,30}$/
                    ,'长度超出允许范围'
                ]
                ,pass: [
                    /^[\S]{6,12}$/
                    ,'密码必须6到12位，且不能出现空格'
                ]
            });
            // 取消按钮
            $('.cancel').click(function () {
                window.location.href="{{ route('employee') }}";
            });
            // 编辑
            form.on('submit(update)', function (data) {
                var roles=[];
                var id=this.getAttribute('lay-id');
                $("input:checkbox[name='roles']:checked").each(function() { // 遍历name=test的多选框
                    $(this).val();  // 每一个被选中项的值
                    roles.push($(this).val());
                });

                $.post("{{ route('employee.update') }}", {id:id,roles:roles,data:data.field,password:encrypt(data.field.password),pay_password:encrypt(data.field.pay_password)}, function (result) {
                    layer.msg(result.message, {time:500}, function () {
                        if(result.status == 1) {
                            window.location.href="{{ route('employee') }}";
                        }
                    });
                });
                return false;
            });
        });

    </script>
@endsection
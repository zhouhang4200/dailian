@extends('back.layouts.app')

@section('title', ' | 商户列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">
                        用户列表
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form" id="search-flow">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="id"  placeholder="账号ID" value="{{ $id }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="name"  placeholder="昵称" value="{{ $name }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="phone"  placeholder="手机号" value="{{ $phone }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="search">搜索</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="user">@include('back.user.list', ['users' => $users, 'id' => $id, 'name' => $name, 'phone' => $phone])</div>
            </div>
        </div>
    </div>
    <div id="recharge-pop" style="display: none;padding: 20px">
        <form class="layui-form layui-form-pane" action="">

            <div class="layui-form-item">
                <label class="layui-form-label">ID</label>
                <div class="layui-input-block">
                    <input type="text" name="id" autocomplete="off" class="layui-input layui-disabled" readonly value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">商户名</label>
                <div class="layui-input-block">
                    <input type="text" name="name" autocomplete="off" class="layui-input layui-disabled" readonly value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">金额</label>
                <div class="layui-input-block">
                    <input type="text" name="amount" autocomplete="off" placeholder="请输入加款金额" class="layui-input" lay-verify="required|number">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <input type="text" name="remark" autocomplete="off" class="layui-input" lay-verify="required">
                </div>
            </div>

            <div class="layui-form-item">
                <button class="layui-btn layui-bg-blue col-lg-12" lay-submit="" lay-filter="recharge">确定</button>
            </div>
        </form>
    </div>

    <!-- 减款弹窗 -->
    <div id="subtract-money-popup" style="display: none;padding: 20px">
        <form class="layui-form layui-form-pane" action="">

            <div class="layui-form-item">
                <label class="layui-form-label">ID</label>
                <div class="layui-input-block">
                    <input type="text" name="id" autocomplete="off" class="layui-input layui-disabled" readonly value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">商户名</label>
                <div class="layui-input-block">
                    <input type="text" name="name" autocomplete="off" class="layui-input layui-disabled" readonly value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">金额</label>
                <div class="layui-input-block">
                    <input type="text" name="amount" autocomplete="off" placeholder="请输入减款金额" class="layui-input" lay-verify="required|number">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <input type="text" name="remark" autocomplete="off" class="layui-input" lay-verify="required">
                </div>
            </div>

            <div class="layui-form-item">
                <button class="layui-btn layui-bg-blue col-lg-12" lay-submit="" lay-filter="subtract-money-popup">确定</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'laytpl', 'element'], function(){
            var form = layui.form, layer = layui.layer;
            // 查找
            form.on('submit(search)', function (data) {
                var id=$("input[name=id]").val();
                var name=$("input[name=name]").val();
                var phone=$("input[name=phone]").val();
                var s = window.location.search;
                var page=s.getAddrVal('page');
                $.get("{{ route('admin.user') }}", {id:id,name:name,phone:phone}, function (result) {
                    $('#user').html(result);
                    form.render();
                });
                return false;
            }, 'json');
            String.prototype.getAddrVal = String.prototype.getAddrVal||function(name){
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var data = this.substr(1).match(reg);
                return data!=null?decodeURIComponent(data[2]):null;
            }
            // 手动加款按钮
            form.on('submit(recharge-button)', function(data){
                $('input[name=id]').val(data.elem.getAttribute('data-id'));
                $('input[name=name]').val(data.elem.getAttribute('data-name'));
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '手动加款',
                    content: $('#recharge-pop')
                });
                return false;
            });
            form.on('submit(recharge)', function(data){
                layer.confirm('您确认给用户ID为: ' + data.field.id  +' 的商户加款' + data.field.amount + ' 元吗？', {icon: 3, title:'提示'}, function(index){
                    $.post('{{ route('admin.user') }}', {
                        user_id:data.field.id,
                        amount:data.field.amount,
                        remark:data.field.remark
                    }, function (result) {
                        layer.msg(result.message)
                    }, 'json');
                    layer.closeAll();
                });
                return false;
            });

            // 手动减款
            form.on('submit(subtract-money-button)', function(data) {
                $('input[name=id]').val(data.elem.getAttribute('data-id'));
                $('input[name=name]').val(data.elem.getAttribute('data-name'));
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '手动减款',
                    content: $('#subtract-money-popup')
                });
                return false;
            });

            form.on('submit(subtract-money-popup)', function(data){
                layer.confirm('您确认要扣用户ID为: ' + data.field.id  +' 商户 <br/><span style="color:red;">' + $(data.form).find("option:selected").text()  + data.field.amount + ' </span>元吗？', {icon: 3, title:'提示'}, function(index){
                    $.post("{{ route('admin.user') }}", {
                        user_id:data.field.id,
                        amount:data.field.amount,
                        remark:data.field.remark
                    }, function(result){
                        layer.msg(result.message)
                    }, 'json');
                    layer.closeAll();
                });
                return false;
            });

        });
    </script>
@endsection

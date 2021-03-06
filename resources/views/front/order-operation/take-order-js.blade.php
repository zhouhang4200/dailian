<script>
    layui.use(['form', 'laydate', 'element'], function () {
        var form = layui.form ,layer = layui.layer, element = layui.element, laydate = layui.laydate;
        layer.config({
            isOutAnim: false
        });
        // 查看详情
        form.on('submit(detail)', function (data) {
            var url = "{{ route('order.detail') }}/" + $(data.elem).attr('data-trade_no');
            $.get(url, function (result) {
                layer.open({
                    type: 1,
                    title: '订单详情',
                    shadeClose: true,
                    resize: false,
                    scrollbar: false,
                    shade: 0.2,
                    area: ['50%'],
                    content: result
                }, 'json');
            });
        });
        form.on('submit(take)', function (data) {

            if ($(data.elem).attr('data-guest')) {
                layer.msg('请先登录');
                return false;
            }

            if ($(data.elem).attr('data-pay_password') == 2) {
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '设置支付密码',
                    area: ['440px'],
                    content: $('#set-pay-password-pop')
                });
                return false;
            }

            $('input[name=trade_no]').val($(data.elem).attr('data-trade_no'));

            if ($(data.elem).attr('data-take_password') == 1) {
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '接单验证',
                    area: ['440px'],
                    content: $('#take-pop')
                });
            } else {
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '接单验证',
                    area: ['440px'],
                    content: $('#take-no-password-pop')
                });
            }
            return false;
        });
        // 确认接单
        form.on('submit(confirm-take)', function (data) {
            $.post('{{ route('order.operation.take') }}', {
                trade_no:data.field.trade_no,
                pay_password: encrypt(data.field.pay_password),
                take_password: data.field.take_password ? encrypt(data.field.take_password) : ''
            }, function (result) {
                if (result.status == 1) {
                    layer.closeAll();
                    layer.alert("接单成功！", {
                        title: '提示',
                        shade: 0.6,
                        btnAlign: 'c',
                        time: 3000,
                        btn: ['立即前往'],
                        success: function(layero,index){
                            var i = 3;
                            var timer = null;
                            var fn = function() {
                                layero.find(".layui-layer-content").text('接单成功！' + i + ' 秒后前往订单详情');
                                if(!i) {
                                    clearInterval(timer);
                                }
                                i--;
                            };
                            timer = setInterval(fn, 1000);
                            fn();
                        },
                        end:function () {
                            window.location.href = "{{ route('order.take-show') }}/" + data.field.trade_no;
                        }
                    }, function() {
                        window.location.href = "{{ route('order.take-show') }}/" + data.field.trade_no;
                    });
                } else if (result.status == 4001) {
                    layer.confirm('您的账号余额不足，是否前往充值？', {
                        btn: ['立即前往', '取消'],
                        btnAlign: 'c'
                    }, function(index, layero){
                        window.location.href = "{{ route('finance.balance-recharge') }}";
                    }, function(index){
                        layer.close();
                    });
                } else {
                    layer.msg(result.message);
                }
            }, 'json');
            return false;
        });
        form.on('select(region)', function (data) {
            $.post('{{ route('order.get-server') }}', {region_id:data.value}, function (result) {
                if (result.status) {
                    $('[name=server_id]').empty();
                    $.each(result.content, function (i, v) {
                        $('[name=server_id]').append("<option value='" + v.id + "'>" + v.name + "</option>")
                    });
                    form.render()
                }
            }, 'json');
        });
        $('.cancel').click(function () {
            layer.closeAll();
        });
        @if(isset($js))
        @include($js)
        @endif
    });
</script>
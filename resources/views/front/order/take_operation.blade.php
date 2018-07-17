
    // 接单
    form.on('submit(take)', function (data) {
        $.post('{{ route('order.operation.take') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {

            }
        }, 'json');
        return false;
    });

    // 申请验收
    form.on('submit(apply-complete)', function (data) {
        $.post('{{ route('order.operation.apply-complete') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif

            } else {
                layer.alert(result.message);
            }
        }, 'json');
        return false;
    });
    // 取消验收
    form.on('submit(cancel-complete)', function (data) {
        $.post('{{ route('order.operation.cancel-complete') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json')
        return false;
    });

    // 异常
    form.on('submit(anomaly)', function (data) {
        $.post('{{ route('order.operation.anomaly') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json')
        return false;
    });

    // 取消异常
    form.on('submit(cancel-anomaly)', function (data) {
        $.post('{{ route('order.operation.cancel-anomaly') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json')
        return false;
    });

    // 申请撤销
    form.on('submit(apply-consult)', function (data) {
        // 打开协商撤销弹窗
        $('input[name=trade_no]').val($(data.elem).attr('data-no'));
        $('input[name=order_amount]').val($(data.elem).attr('data-amount'));
        $('input[name=order_security_deposit]').val($(data.elem).attr('data-security_deposit'));
        $('input[name=order_efficiency_deposit]').val($(data.elem).attr('data-efficiency_deposit'));
        layer.open({
            type: 1,
            shade: 0.2,
            title: '协商撤销',
            area: ['650px'],
            content: $('.consult-pop')
        });
        return false;
    });
    // 确认提交撤销
    form.on('submit(confirm-apply-consult)', function (data) {
        layer.confirm('您确认提交撤销吗?', {icon: 3}, function(index){
            $.post('{{ route('order.operation.apply-consult') }}', {
                trade_no: data.field.trade_no,
                amount: data.field.amount,
                deposit: data.field.deposit,
                remark: data.field.remark
            }, function (result) {
                if (result.status) {
                    @if($type == 'list')
                        layer.msg(result.message);
                        reloadOrderList();
                    @else
                        layer.msg(result.message,{time:500}, function(){
                            location.reload();
                        });
                    @endif
                } else {
                    layer.alert(result.message);
                }
            }, 'json');
            layer.close(index);
        });
        return false;
    });

    // 取消撤销
    form.on('submit(cancel-consult)', function (data) {
        $.post('{{ route('order.operation.cancel-consult') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json')
        return false;
    });
    // 同意撤销
    form.on('submit(agree-consult)', function (data) {
        $.post('{{ route('order.operation.agree-consult') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json');
        return false;
    });
    // 申请仲裁
    form.on('submit(apply-complain)', function (data) {
        $.post('{{ route('order.operation.apply-complain') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json');
        return false;
    });
    // 取消仲裁
    form.on('submit(cancel-complain)', function (data) {
        $.post('{{ route('order.operation.cancel-complain') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
            if (result.status) {
                @if($type == 'list')
                    layer.msg(result.message);
                    reloadOrderList();
                @else
                    layer.msg(result.message,{time:500}, function(){
                        location.reload();
                    });
                @endif
            } else {
                layer.alert(result.message);
            }
        }, 'json');
        return false;
    });


    // 接单
    form.on('submit(take)', function (data) {
        var index = layer.load();
        $.post('{{ route('order.operation.take') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
        layer.close(index);
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
        $('.apply-complete-pop input[name=trade_no]').val($(data.elem).attr('data-no'));
        layer.open({
            type: 1,
            shade: 0.2,
            title: '申请验收',
            area: ['550px'],
            content: $('.apply-complete-pop')
        });
        return false;
    });
    // 确认申请验收
    form.on('submit(confirm-apply-complete)', function (data) {

        var pic1 = $('.apply-complete-image-1 img').attr('src');
        var pic2 = $('.apply-complete-image-2 img').attr('src');
        var pic3 = $('.apply-complete-image-3 img').attr('src');

        if (pic1 == undefined && pic2 == undefined && pic3 == undefined) {
            layer.alert('请至少上传一张图片');
            return false;
        }
        var index = layer.load();
        $.post('{{ route('order.operation.apply-complete') }}', {
            trade_no: data.field.trade_no,
            image_1:pic1,
            image_2:pic2,
            image_3:pic3
        }, function (result) {
        layer.close(index);
            if (result.status == 1) {
                layer.closeAll();
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
    var index = layer.load();
        $.post('{{ route('order.operation.cancel-complete') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
    layer.close(index);
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
        var index = layer.load();
        $.post('{{ route('order.operation.anomaly') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
        layer.close(index);
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
        var index = layer.load();
        $.post('{{ route('order.operation.cancel-anomaly') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
        layer.close(index);
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
        $('.consult-pop input[name=trade_no]').val($(data.elem).attr('data-no'));
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
            var index1 = layer.load();
            $.post('{{ route('order.operation.apply-consult') }}', {
                trade_no: data.field.trade_no,
                amount: data.field.amount,
                deposit: data.field.deposit,
                reason: data.field.reason
            }, function (result) {
                layer.close(index1);
                if (result.status == 1) {
                    layer.closeAll();
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
    var index = layer.load();
        $.post('{{ route('order.operation.cancel-consult') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
    layer.close(index);
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
    var index = layer.load();
        $.post('{{ route('order.operation.agree-consult') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
    layer.close(index);
            if (result.status == 1) {
                layer.closeAll();
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
    // 不同意撤销
    form.on('submit(reject-consult)', function (data) {
    var index = layer.load();
        $.post('{{ route('order.operation.reject-consult') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
    layer.close(index);
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
        // 打开申请仲裁弹窗
        $('.complain-pop input[name=trade_no]').val($(data.elem).attr('data-no'));
        layer.open({
            type: 1,
            shade: 0.2,
            title: '申请仲裁',
            area: ['650px'],
            content: $('.complain-pop')
        });
        return false;
    });
    // 确定申请仲裁
    form.on('submit(confirm-apply-complain)', function (data) {

        var pic1 = $('.complain-image-1 img').attr('src');
        var pic2 = $('.complain-image-2 img').attr('src');
        var pic3 = $('.complain-image-3 img').attr('src');

        if (pic1 == undefined && pic2 == undefined && pic3 == undefined) {
            layer.alert('请至少上传一张图片');
            return false;
        }
    var index1 = layer.load();
        $.post('{{ route('order.operation.apply-complain') }}', {
            trade_no: data.field.trade_no,
            reason: data.field.reason,
            image_1: pic1,
            image_2: pic2,
            image_3: pic3
        }, function (result) {
    layer.close(index1);
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
    var index = layer.load();
        $.post('{{ route('order.operation.cancel-complain') }}', {trade_no: $(data.elem).attr('data-no')}, function (result) {
    layer.close(index);
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



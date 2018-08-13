$('#change-password').click(function () {
    layer.open({
        type: 1,
        shade: 0.2,
        title: '修改密码',
        content: $('#change-password-pop')
    });
    return false;
});
$('#change-pay-password').click(function () {
    layer.open({
        type: 1,
        shade: 0.2,
        title: '修改密码',
        content: $('#change-pay-password-pop')
    });
    return false;
});

form.on('submit(confirm-change-password)', function(data){
    $.post('{{ route('profile.change-password') }}', {
        'old_password':encrypt(data.field.old_password),
        'password':encrypt(data.field.password),
        'repeat_password':encrypt(data.field.repeat_password)
    }, function(result){
        if (result.status == 1) {
            layer.closeAll();
            layer.msg(result.message);
        } else {
            layer.msg(result.message);
        }
    }, 'json');
    return false;
});

form.on('submit(confirm-set-pay-password)', function(data){
    $.post('{{ route('profile.set-pay-password') }}', {
        'password':encrypt(data.field.password),
        'repeat_password':encrypt(data.field.repeat_password)
    }, function(result){
        if (result.status == 1) {
            layer.closeAll();
            layer.msg(result.message);
        } else {
            layer.msg(result.message);
        }
    }, 'json');
    return false;
});

form.on('submit(confirm-change-pay-password)', function(data){
    $.post('{{ route('profile.change-pay-password') }}', {
        'old_password':encrypt(data.field.old_password),
        'password':encrypt(data.field.password),
        'repeat_pay_password':encrypt(data.field.repeat_password)
    }, function(result){
        if (result.status == 1) {
            layer.closeAll();
            layer.msg(result.message);
        } else {
            layer.msg(result.message);
        }
    }, 'json');
    return false;
});
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

form.on('submit(confirm-change-password)', function(){
    $.post('{{ route('profile.change-password') }}', {
        'old_password':data.field.old_password,
        'password':data.field.password,
        'repeat_password':data.field.repeat_password
    }, function(result){

    }, 'json')
});

form.on('submit(confirm-set-pay-password)', function(){
    $.post('{{ route('profile.set-pay-password') }}', {
        'password':data.field.password,
        'repeat_password':data.field.repeat_password
    }, function(result){

    }, 'json')
});

form.on('submit(confirm-change-pay-password)', function(){
    $.post('{{ route('profile.change-pay-password') }}', {
        'old_pay_password':data.field.old_pay_password,
        'pay_password':data.field.pay_password,
        'repeat_pay_password':data.field.repeat_pay_password
    }, function(result){

    }, 'json')
});
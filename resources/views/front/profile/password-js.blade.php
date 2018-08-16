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
$('.reset-pay-password').click(function () {
    layer.open({
        type: 1,
        shade: 0.2,
        title: '重置支付密码',
        area: ['470px'],
        content: $('#reset-pay-password-pop')
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
form.on('submit(confirm-reset-pay-password)', function(data){
    $.post('{{ route('profile.reset-pay-password') }}', {
        'verification_code':data.field.verification_code,
        'password':encrypt(data.field.password),
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

$("body").on('click',".send-code",function(){
    setTime();
});

var countdown = 59;
function setTime() {
    if (countdown == 59) {
        $.post('{{ route('profile.reset-pay-password-verification-code') }}', function(result){
            console.log(result);
        }, 'json');
    }
    if (countdown == '-1') {
        $('.send-code-btn').addClass("send-code");
        $('.send-code-btn').css("background-color", "");
        $('.send-code-btn').text("重新发送");
        countdown = 59;
    } else {

        $('.send-code-btn').removeClass("send-code");
        $('.send-code-btn').css("background-color", "#bbbbbb");
        $('.send-code-btn').text("重新发送" + countdown);
        countdown--;
        setTimeout(function () {
            setTime()
        }, 1000);//1s后执行setTime 函数；
    };
};
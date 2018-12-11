<script src="https://static.geetest.com/static/tools/gt.js"></script>
<script>
    $('.get-verification').click(function () {
        var phone = $('input[name="phone"]').val();

        if ($('input[name="phone"]').val() == '' || !phone.match(/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/)) {
            layer.msg('请输入正确手机号', {icon:5})
        } else {
            $(".geetest_radar_tip").click();
        }
        return false
    });

    var countdown = 59;

    geetest();

    function setTime(phone) {
        var sendSuccess = true;
        if (countdown == 59) {

            $.post('{{ route('password.reset.verification-code') }}', {
                phone: phone,
                type: 1,
                geetest_challenge: $('input[name=geetest_challenge]').val(),
                geetest_seccode: $('input[name=geetest_seccode]').val(),
                geetest_validate: $('input[name=geetest_validate]').val()
            }, function (result) {
                if (result.code != 0) {
                    sendSuccess = false;
                    return layer.msg(result.message);
                } else {

                    return layer.msg('验证码发送成功请注意查收');
                }
            }, 'json');
        }

        if (sendSuccess) {
            if (countdown == '-1') {
                $('.send-code-btn').addClass("get-verification");
                $('.send-code-btn').css("background-color", "");
                $('.send-code-btn').text("重新发送");
                countdown = 59;
            } else {

                $('.send-code-btn').removeClass("get-verification");
                $('.send-code-btn').css("background-color", "#bbbbbb");
                $('.send-code-btn').text("重新发送" + countdown);
                countdown--;
                setTimeout(function () {
                    setTime()
                }, 1000);//1s后执行setTime 函数；
            }
        }
    }

    function geetest() {
        $.ajax({
            url: "{{ Config::get('geetest.url', 'geetest') }}?t=" + (new Date()).getTime(),
            type: "get",
            dataType: "json",
            success: function (data) {
                //请检测data的数据结构， 保证data.gt, data.challenge, data.success有值
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    offline: !data.success,
                    new_captcha: true,
                    product: 'custom',
                    area: '#captcha-container',
                    next_width: '300px',
                    bg_color: 'black',
                    lang: '{{ Config::get('geetest.lang', 'zh-cn') }}',
                    http: '{{ Config::get('geetest.protocol', 'http') }}' + '://'
                }, function (captchaObj) {
                    captchaObj.appendTo("#captchaBox");
                    captchaObj.onReady(function () {
                    }).onSuccess(function () {
                        setTime($('input[name=phone]').val());
                    }).onError(function () {
                    })
                });
            }
        });
    }
</script>
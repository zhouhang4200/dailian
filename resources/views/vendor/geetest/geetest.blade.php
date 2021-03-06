<script src="/js/jquery-1.11.0.min.js"></script>
<script src="https://static.geetest.com/static/tools/gt.js"></script>
<div id="geetest-captcha"></div>
<p id="wait" class="show">正在加载验证码...</p>
@define use Illuminate\Support\Facades\Config
<script>
    var geetest = function(url) {
        var handlerEmbed = function(captchaObj) {
            captchaObj.appendTo("#geetest-captcha");
            captchaObj.onReady(function() {
                $("#wait")[0].className = "hide";
            });

            {{--$("#geetest-captcha").closest('form').submit(function(e) {--}}
                {{--var validate = captchaObj.getValidate();--}}
                {{--if (!validate) {--}}
                    {{--// alert('{{ Config::get('geetest.client_fail_alert')}}');--}}
                    {{--layer.msg('请点击按钮进行验证！');--}}
                    {{--e.preventDefault();--}}
                {{--}--}}
            {{--});--}}

            {{--if ('{{ $product }}' == 'popup') {--}}
                {{--captchaObj.bindOn($('#geetest-captcha').closest('form').find(':submit'));--}}
                {{--captchaObj.appendTo("#geetest-captcha");--}}
            {{--}--}}

            captchaObj.onSuccess(function () {
                var result = captchaObj.getValidate();
                // console.log(result);
                setTime(phone);
            });
        };
        $.ajax({
            url: url + "?t=" + (new Date()).getTime(),
            type: "get",
            dataType: "json",
            success: function(data) {
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    product: "{{ $product?$product:Config::get('geetest.product', 'float') }}",
                    offline: !data.success,
                    new_captcha: data.new_captcha,
                    lang: '{{ Config::get('geetest.lang', 'zh-cn') }}',
                    http: '{{ Config::get('geetest.protocol', 'http') }}' + '://'
                }, handlerEmbed);
            }
        });
    };
    (function() {
        geetest('{{ $url?$url:Config::get('geetest.url', 'geetest') }}');
    })();
</script>
<style>
    .hide {
        display: none;
    }
    .geetest_holder.geetest_wind .geetest_btn {
        width:100%;
    }
    .geetest_btn .geetest_radar_btn {
        width:300px !important;
    }
</style>
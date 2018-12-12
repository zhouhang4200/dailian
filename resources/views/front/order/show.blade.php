@extends('front.layouts.app')

@section('title', '接单管理 - 订单详情')

@section('css')
    <style>
        .layui-layout-admin .layui-body {
            top: 50px;
        }

        .layui-layout-admin .layui-footer {
            height: 52px;
        }
        .layui-footer {
            z-index: 999;
        }
        .layui-card .layui-tab {
            margin: 10px 0;
        }
        .layui-tab-title li {
            min-width: 50px;
        }
        .layui-card-header {
            color: #303133;
            font-size: 14px;
        }
        .order-operation {
            float: right;
            padding-top: 5px;
        }
        .order-btn .iconfont {
            position: absolute;
            top: 0%;
            left: -8px;
            font-size: 22px;
        }

        .layui-footer .qs-btn {
            margin: 5px 3px 0 5px;
        }

        .template {
            float: right;
        }

        .template .qs-btn {
            height: 32px;
            line-height: 32px;
        }

        /* 改写header高度 */
        .layui-card-header {
            height: 56px;
            line-height: 56px;
        }

        /* 改写dl-type 下面的radio样式 */
        #dl-type .layui-form-radio {
            position: relative;
            height: 30px;
            line-height: 28px;
            margin-right: 10px;
            padding-right: 30px;
            border: 1px solid #d2d2d2;
            cursor: pointer;
            font-size: 0;
            border-radius: 2px;
            -webkit-transition: .1s linear;
            transition: .1s linear;
            box-sizing: border-box;
        }

        #dl-type .layui-form-radio div {
            padding: 0 10px;
            height: 100%;
            font-size: 12px;
            background-color: #d2d2d2;
            color: #fff;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        #dl-type .layui-form-radio i {
            position: absolute;
            right: 0;
            width: 30px;
            color: #fff;
            font-size: 20px;
            text-align: center;
            margin-right: 0;
        }

        #dl-type .layui-form-checked i,
        #dl-type .layui-form-checked:hover i {
            color: #5FB878
        }

        #dl-type .layui-form-radio:hover i {
            border-color: #5FB878;
            color: #d2d2d2
        }

        #dl-type .layui-form-radioed:hover i {
            border-color: #5FB878;
            color: #5FB878;
        }

        #dl-type .layui-form-radioed i {
            color: #5FB878;
            border-color: #5FB878;
        }

        #dl-type .layui-form-radioed div {
            color: #fff;
            background-color: #5FB878;
        }
        .layui-col-lg6 .layui-input-block .tips{
            left: 95%;
        }
        .layui-input-block input,
        .layui-form-select, .layui-textarea,
        .layui-col-lg6 .layui-input-block input,
        .layui-col-lg6 .layui-form-select{
            width: 95%;
        }
        .layui-col-lg6 .layui-form-select input{
            width: 100%
        }
        .tips {
            position: absolute;
            width: 10%;
            height: 30px;
            right: 0;
            top: 5px;
            text-align: center
        }

        .tips .iconfont {
            left: 0px;
            font-size: 25px;
        }
        #carousel {
            position: relative;
        }
        .carousel-tips {
            width: 100%;
            height: 40px;
            line-height: 40px;
            text-align: left;
            text-indent: 20px;
            background-color: rgba(0, 0, 0, .8);
            color: #fff;
            position: absolute;
            bottom: 0px;
        }
        .layui-table{
            margin: 0;
        }
        @media screen and (max-width: 990px){
            .layui-col-lg6 .layui-input-block input {
                width: 90%;
            }
            .layui-col-lg6 .layui-input-block .tips {
                left: 90%;
            }
            .layui-col-lg6 .layui-form-select {
                width: 90%;
            }
            .layui-col-lg6 .layui-form-select input{
                width: 100%;
            }
        }
        .layui-layer-btn .layui-layer-btn0 {
            background: #198cff;
            border: #198cff;
        }
        /** 协商撤销样式重写 **/
        .consult-pop  .layui-form-label {
            width: 160px !important
        }
    </style>
    <link rel="stylesheet" href="/front/lib/css/im.css">
    <link rel="stylesheet" href="/front/css/bootstrap-fileinput.css">
@endsection

@section('main')
    <div class="layui-col-md8">

        <div class="layui-card qs-text" >
            <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief" style="margin-top: 0;">
                <ul class="layui-tab-title" style="height:56px;line-height: 56px;"  lay-filter="detail">
                    <li class="layui-this" lay-id="1">订单信息</li>
                    <li lay-id="2">仲裁证据</li>
                    <li lay-id="3">操作记录</li>
                    <div class="order-operation">
                        <button class="order-btn" id="carousel-btn">
                            <i class="iconfont icon-image"></i>查看图片</button>
                        <button class="order-btn" id="im" style="margin:0 15px 0 5px">
                            <i class="iconfont icon-duihua"></i>留言</button>
                    </div>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form" action="" lay-filter="component-form-group" id="form-order">

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">游戏</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_name"   class="layui-input"  disabled="disabled" value="{{ $order->game_name }}">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">区</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="region_name"   class="layui-input"  disabled="disabled" value="{{ $order->region_name }}">
                                    </div>
                                </div>

                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">服</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="server_name"   class="layui-input"  disabled="disabled" value="{{ $order->server_name }}">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">角色名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_role"   class="layui-input"  disabled="disabled" value="{{ $order->game_role }}">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">账号</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_account"   class="layui-input"  disabled="disabled" value="{{ $order->game_account }}">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">密码</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_password"   class="layui-input"  disabled="disabled" value="{{ $order->game_password }}">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练类型</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_leveling_type_name"   class="layui-input"  disabled="disabled" value="{{ $order->game_leveling_type_name }}">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练标题</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="title"   class="layui-input"  disabled="disabled" value="{{ $order->title }}">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item layui-form-text">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练说明</label>
                                    <div class="layui-input-block">
                                        <textarea name="explain"  class="layui-textarea"   disabled="disabled">{{ $order->explain }}</textarea>
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练要求</label>
                                    <div class="layui-input-block">
                                        <textarea name="requirement"   class="layui-textarea"  disabled="disabled">{{ $order->requirement  }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="amount"  class="layui-input"  disabled="disabled" value="{{ $order->amount }}">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">安全保证金</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="security_deposit"  class="layui-input"  disabled="disabled" value="{{ $order->security_deposit }}">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">效率保证金</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="efficiency_deposit"  class="layui-input"  disabled="disabled" value="{{ $order->efficiency_deposit }}">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练天数</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="day"  class="layui-input"  disabled="disabled" value="{{ $order->day }}">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练小时</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="hour"  class="layui-input"  disabled="disabled" value="{{ $order->hour }}">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">玩家电话</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="player_phone"  class="layui-input" value="{{ $order->player_phone }}">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">商户QQ</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="parent_qq"  class="layui-input"  disabled="disabled" value="{{ $order->parent_user_qq }}">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <div class="layui-footer" style="left: 0;">

                                        @if ($order->getStatusDescribe() == '代练中')
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="apply-complete">申请验收</button>
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="{{ $order->trade_no }}" data-amount="{{ $order->amount }}" data-security_deposit="{{ $order->security_deposit }}" data-efficiency_deposit="{{ $order->efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
                                            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="apply-complain">申请仲裁</button>
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="anomaly">异常</button>
                                        @endif

                                        @if ($order->getStatusDescribe() == '待验收')
                                            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;" data-no="{{ $order->trade_no }}" lay-submit lay-filter="cancel-complete">取消验收</button>
                                                <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="{{ $order->trade_no }}" data-amount="{{ $order->amount }}" data-security_deposit="{{ $order->security_deposit }}" data-efficiency_deposit="{{ $order->efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
                                                <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="apply-complain">申请仲裁</button>
                                        @endif

                                        @if ($order->getStatusDescribe() == '撤销中' && optional($order->consult)->initiator == 2)
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="cancel-consult">取消撤销</button>
                                            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="apply-complain">申请仲裁</button>
                                        @endif

                                        @if ($order->getStatusDescribe() == '仲裁中' && optional($order->consult)->initiator == 1)
                                                {{--<button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="{{ $order->trade_no }}"--}}
                                                        {{--lay-submit lay-filter="agree-consult" data-consult-amount="{{ $order->consult->amount }}"--}}
                                                {{--data-consult-deposit="{{ $order->consult->security_deposit+$order->consult->efficiency_deposit }}"--}}
                                                {{--data-consult-reason="{{ $order->consult->reason }}">同意撤销</button>--}}
                                        @endif

                                        @if ($order->getStatusDescribe() == '撤销中' && optional($order->consult)->initiator == 1)
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="agree-consult" data-consult-amount="{{ $order->consult->amount }}"
                                                    data-consult-deposit="{{ $order->consult->security_deposit+$order->consult->efficiency_deposit }}"
                                                    data-consult-reason="{{ $order->consult->reason }}">同意撤销</button>
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="reject-consult">不同意撤销</button>
                                            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="{{ $order->trade_no }}" lay-submit lay-filter="apply-complain">申请仲裁</button>
                                        @endif

                                        @if ($order->getStatusDescribe() == '仲裁中' && optional($order->complain)->initiator == 2)
                                            <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="{{ $order->trade_no }}" lay-submit lay-filter="cancel-complain">取消仲裁</button>
                                        @endif


                                        @if ($order->getStatusDescribe() == '异常')
                                        <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="{{ $order->trade_no }}" lay-submit lay-filter="cancel-anomaly">取消异常</button>
                                        <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="{{ $order->trade_no }}" data-amount="{{ $order->amount }}" data-security_deposit="{{ $order->security_deposit }}" data-efficiency_deposit="{{ $order->efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
                                        @endif

                                        @if ($order->getStatusDescribe() == '锁定')
                                        <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="{{ $order->trade_no }}" data-amount="{{ $order->amount }}" data-security_deposit="{{ $order->security_deposit }}" data-efficiency_deposit="{{ $order->efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="layui-tab-item" lay-id="complain-info" id="complain-info">
                    </div>
                    <div class="layui-tab-item" lay-id="operation-log" id="operation-log">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="layui-col-md4">
        <div class="layui-card" style="">
            <div class="layui-card-header">平台数据</div>
            <div class="layui-card-body qs-text">
                <table class="layui-table">
                    <colgroup>
                        <col width="115">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <td>平台订单</td>
                        <td>{{ $order->trade_no }}</td>
                    </tr>
                    <tr>
                        <td>发布人</td>
                        <td>{{ $order->username }}</td>
                    </tr>
                    <tr>
                        <td>订单状态</td>
                        <td>{{ $order->getStatusDescribe() }}</td>
                    </tr>
                    <tr>
                        <td>剩余时间</td>
                        <td>{{ $order->getRemainingTime() }}</td>
                    </tr>
                    <tr>
                        <td>发布时间</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td>接单时间</td>
                        <td>{{ $order->take_at }}</td>
                    </tr>
                    <tr>
                        <td>提验时间</td>
                        <td>{{ $order->apply_complete_at }}</td>
                    </tr>
                    <tr>
                        <td>结算时间</td>
                        <td>{{ $order->complete_at }}</td>
                    </tr>
                    <tr>
                        <td>撤销说明</td>
                        <td>{!! $order->getConsultDescribe() !!}</td>
                    </tr>
                    <tr>
                        <td>仲裁说明</td>
                        <td>{!! $order->getComplainDescribe() !!}</td>
                    </tr>
                    <tr>
                        <td>仲裁结果</td>
                        <td>{!! $order->getComplainResult() !!}</td>
                    </tr>
                    <tr>
                        <td>获得代练费</td>
                        <td>{{ $order->getIncomeAmount() }}</td>
                    </tr>
                    <tr>
                        <td>支付赔偿金</td>
                        <td>{{ $order->getExpendAmount() }}</td>
                    </tr>
                    <tr>
                        <td>手续费</td>
                        <td>{{ $order->getPoundage() }}</td>
                    </tr>
                    <tr>
                        <td>最终获得金额</td>
                        <td>{{ $order->getProfit() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('pop')
    @include('front.order-operation.pop')
@endsection

@section('js')
    <script src="/front/js/bootstrap-fileinput.js"></script>
    <script>
        layui.use(['form', 'layedit', 'laydate', 'laytpl', 'element', 'carousel'], function(){
            var form = layui.form, layer = layui.layer, layTpl = layui.laytpl, element = layui.element, carousel =  layui.carousel;

            @include('front.order-operation.operation', ['type' => 'detail'])

            element.on('tab()', function(){
                var id = this.getAttribute('lay-id');
                if (id == 2) {
                   complainInfo();
                }
                if (id == 3) {
                    operationLog();
                }
            });

            // 发送仲裁留言
            form.on('submit(send-complain-message)', function (data) {
                var image = $('.pic-add img').attr('src');
                if (data.field.content) {
                    $.post("{{ route('order.operation.send-complain-message') }}", {
                        'trade_no': "{{ $order->trade_no }}",
                        'content': data.field.content,
                        'image': image
                    }, function (data) {
                        if (data.status === 1) {
                            layer.msg(data.message, {icon: 1});
                            complainInfo();
                        } else {
                            layer.msg(data.message, {icon: 5});
                            return false;
                        }
                    }, 'json');
                } else {
                    layer.msg('请输入要发送的内容', {icon: 5});
                }
                return false;
            });

            // 关闭留言
            form.on('submit(cancel)', function (data) {
                layer.closeAll();
                return false;
            });

            // 发送普通留言
            form.on('submit(send-message)', function (data) {
                if (data.field.content) {
                    $.post("{{ route('order.operation.send-message', ['trade_no' => $order->trade_no]) }}", {
                        'content': data.field.content
                    }, function (data) {
                        if (data.status === 1) {
                            message();
                            $('.layim-chat-main').scrollTop( $('.layim-chat-main')[0].scrollHeight );
                        } else {
                            layer.msg(data.message, {icon: 5});
                            return false;
                        }
                        $('textarea[name=content]').val('');
                    }, 'json');
                } else {
                    layer.msg('请输入要发送的内容', {icon: 5});
                }
                return false;
            });
            // 留言弹窗
            $('#im').click(function () {
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: false,
                    area: ['850px', '561px'],
                    shade: 0.2,
                    moveType: 1,  //拖拽模式，0或者1
                    content: $('#im-pop'),
                    success: function (layero) {
                        message();
                    }
                });
            });
            // 查看图片
            var ins = carousel.render({
                elem: '#carousel',
                anim: 'fade',
                width: '500px',
                arrow: 'always',
                autoplay: false,
                height: '500px',
                indicator: 'none'
            });
            $('#carousel-btn').click(function () {
                $.get("{{ route('order.operation.apply-complete-image', ['trade_no' => $order->trade_no]) }}", function (result) {
                    if (result.status === 1) {
                        $('#carousel').html(result.content);
                        layer.open({
                            type: 1,
                            title: false ,
                            area: ['50%', '500px'],
                            shade: 0.8,
                            shadeClose: true,
                            moveType: 1,
                            content: $('#carousel'),
                            success: function () {
                                //改变下时间间隔、动画类型、高度
                                ins.reload({
                                    elem: '#carousel',
                                    anim: 'fade',
                                    width: '100%',
                                    arrow: 'always',
                                    autoplay: false,
                                    height: '100%',
                                    indicator: 'none'
                                });
                            }
                        });
                    } else {
                        layer.msg('暂时没有图片', {icon: 5});
                    }
                });
            });
            // 加载订单操作日志
            function operationLog() {
                $.get("{{ route('order.operation.log', ['trade_no' => $order->trade_no]) }}", function (result) {
                    $('#operation-log').html(result);
                });
            }
            // 订单仲裁信息
            function complainInfo() {
                $.get('{{ route('order.operation.complain-info', ['trade_no' => $order->trade_no])  }}', function (result) {
                    $('#complain-info').html(result);
                });
            }
            // 订单留言
            function message() {
                $.get('{{ route('order.operation.message', ['trade_no' => $order->trade_no])  }}', function (result) {
                    $('.layim-chat-main').html(result);
                });
            }
            $('.layui-card').on('click', '.photo', function () {
                var imgSrc = $(this).attr('data-img');
                layer.photos({
                    photos: {
                        "id": 123, //相册id
                        "data": [   //相册包含的图片，数组格式
                            {
                                "src": imgSrc, //原图地址
                                "thumb": imgSrc //缩略图地址
                            }
                        ]
                    },
                    anim: -1,
                    shade: 0.8
                });
            });
        });
    </script>
@endsection

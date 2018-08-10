@extends('front.layouts.app')

@section('title', '接单管理')

@section('css')
    <link rel="stylesheet" href="/front/css/bootstrap-fileinput.css">
    <style>
        .layui-layout-admin .layui-body {
            top: 50px;
        }

        .layui-layout-admin .layui-footer {
            height: 52px;
        }

        .footer {
            height: 72px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .main {
            padding: 20px;
        }

        .layui-footer {
            z-index: 999;
        }

        .layui-card-header {
            height: auto;
            border-bottom: none;
            padding-bottom: 0;
        }

        .layui-card .layui-tab {
            margin-top: 3px;
            margin-bottom: 12px;
        }
        .layui-form-item {
            margin-bottom: 12px;
        }
        .last-item{
            margin-bottom: 5px;
        }
        .layui-tab-title li{
            min-width: 50px;
            font-size: 12px;
        }
        .qsdate{
            float: left;
            width: 40% !important;
        }
        .layui-card-header{
            padding: 15px 15px 0 15px;;

        }
        .layui-card-body{
            padding-top: 0;
        }
        .layui-card .layui-tab-brief .layui-tab-content {
            padding: 0px;
        }
        /* 修改同意字体为12px */
        .last-item .last-item-btn {
            margin-left: 0;
        }
        @media screen and (max-width: 990px){
            .layui-col-md12 .layui-card .layui-card-header .layui-row .layui-form .first .layui-form-label{
                width: 80px;
                padding: 5px 10px;
                text-align: right;
            }
            .layui-col-md12 .first .layui-input-block{
                margin-left: 110px;
            }
            .last-item .last-item-btn {
                margin-left: 40px;
            }
        }
        /* 改写header高度 */
        .layui-card-header {
            font-size:12px;
        }
        .layui-table-edit {
            height: 50px;
        }

        .layui-layer-btn .layui-layer-btn0 {
            border-color: #ff8500;
            background-color: #ff8500;
            color: #fff;
        }
        .layui-table-edit:focus {
            border-color: #e6e6e6 !important
        }
        /** 协商撤销样式重写 **/
        .consult-pop  .layui-form-label {
            width: 160px !important
        }
    </style>
@endsection

@section('main')
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">
                <div class="layui-row layui-col-space5">
                    <form class="layui-form" action="">
                        <div class="layui-col-md3 first">
                            <div class="layui-form-item">
                                <label class="layui-form-label" >订单单号</label>
                                <div class="layui-input-block" style="">
                                    <input type="text" name="trade_no" lay-verify="title" autocomplete="off" placeholder="请输入订单号" class="layui-input">
                                </div>
                            </div>
                        </div>

                        <div class="layui-col-md3">
                            <div class="layui-form-item">
                                <label class="layui-form-label">代练游戏</label>
                                <div class="layui-input-block">
                                    <select name="game_id" lay-search="" lay-filter="game">
                                        <option value="">请选择游戏</option>
                                        @foreach($games as  $game)
                                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="layui-col-md4">
                            <div class="layui-form-item last-item">
                                <label class="layui-form-label">发布时间</label>
                                <div class="layui-input-block">
                                    <input type="text"  class="layui-input qsdate" id="start-time" autocomplete="off" name="start_time" placeholder="开始日期">
                                    <div class="layui-form-mid">
                                        -
                                    </div>
                                    <input type="text" class="layui-input qsdate" id="end-time"  autocomplete="off" name="end_time" placeholder="结束日期">
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md2">
                            <div class="layui-form-item last-item">
                                <div class="layui-input-block last-item-btn">
                                    <button class="qs-btn" lay-submit="" lay-filter="search" style="height: 30px;line-height: 30px;float: left;font-size: 12px;">搜索</button>
                                    <button class="qs-btn" lay-submit="" lay-filter="export" style="margin-left:10px;height: 30px;line-height: 30px;float: left;font-size: 12px;">导出</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="layui-card-body">
                <div class="layui-tab layui-tab-brief layui-form" lay-filter="order-list">
                    <ul class="layui-tab-title">
                        <li class="layui-this" lay-id="0">全部 <span  class="layui-badge layui-bg-blue wait-handle-quantity  layui-hide"></span></li>
                        <li class="" lay-id="2">代练中
                            <span class="qs-badge quantity-13 layui-hide"></span>
                        </li>
                        <li class="" lay-id="3">待验收
                            <span class="qs-badge quantity-14 layui-hide"></span>
                        </li>
                        <li class="" lay-id="4">撤销中
                            <span class="qs-badge quantity-15 layui-hide"></span>
                        </li>
                        <li class="" lay-id="5">仲裁中
                            <span class="qs-badge quantity-16 layui-hide"></span>
                        </li>
                        <li class="" lay-id="6">异常
                            <span class="qs-badge quantity-17 layui-hide"></span>
                        </li>
                        <li class="" lay-id="7">锁定
                            <span class="qs-badge quantity-18 layui-hide"></span>
                        </li>
                        <li class="" lay-id="8">已撤销
                        </li>
                        <li class="" lay-id="10">已结算
                        </li>
                        <li class="" lay-id="9">已仲裁
                        </li>
                    </ul>
                </div>
                <div id="order-list" lay-filter="order-list">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pop')
    @include('front.order.take.pop')
@endsection

@section('js')
    <script src="/front/js/bootstrap-fileinput.js"></script>
    <script type="text/html" id="operation">

        @{{# if (d.status_describe == '代练中') {  }}
            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="@{{ d.trade_no }}" lay-submit lay-filter="apply-complete">申请验收</button>
            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="@{{ d.trade_no }}" data-amount="@{{ d.amount }}" data-security_deposit="@{{ d.security_deposit }}" data-efficiency_deposit="@{{ d.efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
        @{{# }   }}

        @{{# if (d.status_describe == '待验收') {  }}
        <button class="qs-btn qs-btn-sm " style="width: 80px;" data-no="@{{ d.trade_no }}" lay-submit lay-filter="cancel-complete">取消验收</button>
        <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;" data-no="@{{ d.trade_no }}" data-amount="@{{ d.amount }}" data-security_deposit="@{{ d.security_deposit }}" data-efficiency_deposit="@{{ d.efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
        @{{# }   }}

        @{{# if (d.status_describe == '撤销中' && d.consult_initiator == 2) {  }}
            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="@{{ d.trade_no }}" lay-submit lay-filter="cancel-consult">取消撤销</button>
            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="@{{ d.trade_no }}" lay-submit lay-filter="apply-complain">申请仲裁</button>
        @{{# } else if(d.status_describe == '撤销中' && d.consult_initiator == 1) {  }}
            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="@{{ d.trade_no }}" lay-submit lay-filter="reject-consult" >不同意撤销</button>
            <button class="qs-btn qs-btn-sm" style="width: 80px;"   data-no="@{{ d.trade_no }}" lay-submit lay-filter="agree-consult" >同意撤销</button>
            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="@{{ d.trade_no }}" lay-submit lay-filter="apply-complain">申请仲裁</button>
        @{{# }  }}

        @{{# if (d.status_describe == '仲裁中' && d.complain_initiator == 2) {  }}
            <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="@{{ d.trade_no }}" lay-submit lay-filter="cancel-complain">取消仲裁</button>
        @{{# }  }}

        @{{# if (d.status_describe == '仲裁中' && d.complain_initiator == 1) {  }}
            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;" data-no="@{{ d.trade_no }}" lay-submit lay-filter="agree-consult" >同意撤销</button>
        @{{# }   }}

        @{{# if (d.status == '异常') {  }}
            <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="@{{ d.trade_no }}" lay-submit lay-filter="cancel-anomaly">取消异常</button>
            <button class="qs-btn qs-btn-primary qs-btn-sm qs-btn-table" style="width: 80px;"   data-no="@{{ d.trade_no }}" data-amount="@{{ d.amount }}" data-security_deposit="@{{ d.security_deposit }}" data-efficiency_deposit="@{{ d.efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
        @{{# }   }}

        @{{# if (d.status == '锁定') {  }}
            <button class="qs-btn qs-btn-sm" style="width: 80px;" data-no="@{{ d.trade_no }}" data-amount="@{{ d.amount }}" data-security_deposit="@{{ d.security_deposit }}" data-efficiency_deposit="@{{ d.efficiency_deposit }}" lay-submit lay-filter="apply-consult">协商撤销</button>
        @{{# }   }}

    </script>
    <script type="text/html" id="noTemplate">
        <a style="color:#1f93ff"  href="{{ route('order.take') }}/@{{ d.trade_no }}" target="_blank">@{{ d.trade_no }}</a>
    </script>
    <script type="text/html" id="statusTemplate">
        @{{ d.status_describe }}  <br>
        @{{# if(d.timeout == 1 && d.status == 13)  { }}
        <span style="color:#ff8500"> @{{ d.timeout_time }}</span>
        @{{# } else if(d.status == 13 || d.status == 1) { }}
        @{{ d.status_time }}
        @{{# } else {  }}
        @{{ d.status_time }}
        @{{# }  }}
    </script>
    <script type="text/html" id="gameTemplate">
        @{{ d.game_name }} <br>
        @{{ d.region_name }} / @{{ d.serve_name }}
    </script>
    <script type="text/html" id="accountPasswordTemplate">
        @{{ d.game_account }} <br/>
        @{{ d.game_password }}
    </script>
    <script type="text/html" id="createdAtAndReceiving">
        @{{ d.created_at }}<br/>
        @{{ d.receiving_time }}
    </script>
    <script type="text/html" id="dayHours">
        @{{ d.day }} 天 @{{ d.hour }} 小时
    </script>
    <script type="text/html" id="titleTemplate">
        <span class="tips" lay-tips="@{{ d.title  }}">@{{ d.title }}</span>
    </script>
    <script type="text/html" id="changeStyleTemplate">
        <style>
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-no,
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-status_text,
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-game_name,
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-account_password,
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-receiving_time,
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-hatchet_man_qq,
            .layui-table-view .layui-table[lay-size=sm] td .laytable-cell-@{{ d  }}-seller_nick {
                height: 40px;
                line-height: 20px;
            }
            .layui-table-view .layui-table[lay-size=sm] td  .laytable-cell-@{{ d  }}-button{
                display: block;
                height: 40px;
                line-height: 40px;
                word-break: break-all;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding-left: 15px;
            }
            .layui-laypage .layui-laypage-curr .layui-laypage-em {
                background-color: #ff8500;
            }
        </style>
    </script>
    <script id="images" type="text/html">
        <div carousel-item="" id="">
            @{{# var i = 0; layui.each(d, function(index, item){ }}
            <div  style="background: url(@{{ item.url }}) no-repeat center/contain;"  @{{# if(i == 0){ }} class="layui-this" @{{# } }} >
                <div class="carousel-tips" >@{{ item.description }} &nbsp;&nbsp;&nbsp; @{{ item.created_at }} </div>
            </div>
            @{{# if(i == 0){   i = 1;  } }}
            @{{# }); }}
        </div>
    </script>
    <script>
        layui.use(['table', 'form', 'layedit', 'laydate', 'laytpl', 'element', 'carousel'], function () {
            var form = layui.form,
                    layer = layui.layer,
                    element = layui.element,
                    layTpl = layui.laytpl,
                    table = layui.table,
                    carousel =  layui.carousel;

            // 订单操作
            @include('front.order.take.operation', ['type' => 'list'])

            // 状态切换
            element.on('tab(order-list)', function () {
                $('form').append('<input name="status" type="hidden" value="' + this.getAttribute('lay-id')  + '">');
                reloadOrderList();
            });
            // 搜索
            form.on('submit(search)', function (data) {
                reloadOrderList(data.field);
                return false;
            });
            // 导出
            form.on('submit(export)', function (data) {
                var formCondition = $('form').serializeArray();
                var condition = '';
                $.each(formCondition, function() {
                    condition += this.name + '=' + this.value + '&';
                });
                {{--window.location.href = "{{ route('frontend.workbench.leveling.index', ['export' => 1])}}" + condition;--}}
                return false;
            });

            // 加载数据
            table.render({
                elem: '#order-list',
                url: '{{ route('order.take') }}',
                method: 'post',
                cols: [[
                    {field: 'trade_no', title: '订单号', width: 260, templet: '#noTemplate', style:"height: 40px;line-height: 20px;"},
                    {field: 'status_des', title: '订单状态', width: 95, style:"height: 40px;line-height: 20;", templet:'#statusTemplate' },
                    {field: 'title', title: '代练标题', width: 230, templet:'#titleTemplate'},
                    {field: 'game_info', title: '游戏/区/服', width: 140, templet:'#gameTemplate'},
                    {field: 'game_role', title: '角色名称', width: 100},
                    {field: 'account_password', title: '账号/密码', width: 120, templet:'#accountPasswordTemplate'},
                    {field: 'amount', title: '代练价格', width: 100},
                    {field: 'security_deposit', title: '安全保证金', width: 120},
                    {field: 'efficiency_deposit', title: '效率保证金', width: 120},
                    {field: 'created_at', title: '发单时间', width: 160},
                    {field: 'take_at', title: '接单时间', width: 160},
                    {title: '代练时间', width: 100, templet:'#dayHours'},
                    {field: 'remaining_time', title: '剩余时间', width: 165},
                    {field: 'payer_phone', title: '号主电话', width: 100},
                    {field: 'income_amount', title: '获得金额', width: 80},
                    {field: 'expend_amount', title: '支付金额', width: 80},
                    {field: 'poundage', title: '手续费', width: 80},
                    {field: 'profit', title: '利润', width: 80},
                    {field: 'button', title: '操作',width:195, fixed: 'right', style:"height: 40px;line-height: 20px;", toolbar: '#operation'}
                ]],
                height: 'full-245',
                size: 'sm',
                page: {
                    layout: [ 'count', 'prev', 'page', 'next', 'skip'],
                    groups: 10,
                    prev: '«',
                    next: '»',
                    limit:50
                },
                done: function(res, curr, count){
                    changeStyle(layui.table.index);
                    setStatusNumber();
                }
            });
            // 订单表格重载
            function reloadOrderList(parameter) {
                var condition = {};
                if (parameter == undefined) {
                    var formCondition = $('form').serializeArray();
                    $.each(formCondition, function() {
                        condition[this.name] = this.value;
                    });
                } else {
                    condition = parameter;
                }
                //执行重载
                table.reload('order-list', {
                    where: condition,
                    height: 'full-245',
                    page: {
                        curr: 1
                    },
                    done: function(res, curr, count){
                        changeStyle(layui.table.index);
                        setStatusNumber();
                        layui.form.render();
                    }
                });
            }
            // 重新渲染后重写样式
            function changeStyle(index) {
                var getTpl = changeStyleTemplate.innerHTML, view = $('body');
                layTpl(getTpl).render(index, function(html){
                    view.append(html);
                });
            }
            // 设置订单状态数
            function setStatusNumber() {
                var condition = {};
                var parameter;
                var formCondition = $('form').serializeArray();
                $.each(formCondition, function() {
                    condition[this.name] = this.value;
                });
                {{--$.post('{{ route('frontend.workbench.leveling.order-status-count') }}', condition, function (result) {--}}
                    {{--parameter = result.content;--}}
                    {{--if (parameter.length == 0){--}}
                        {{--$('.qs-badge').addClass('layui-hide');--}}
                    {{--}--}}
                    {{--$('.qs-badge').addClass('layui-hide');--}}
                    {{--$.each(parameter, function(key, val) {--}}
                        {{--var name = 'quantity-'  +  key;--}}
                        {{--if ($('span').hasClass(name) && val > 0) {--}}
                            {{--$('.' + name).html(val).removeClass('layui-hide');--}}
                        {{--}--}}
                    {{--});--}}
                {{--}, 'json');--}}
            }
        });
    </script>
@endsection

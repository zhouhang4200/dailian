@extends('front.layouts.app')

@section('title', '订单管理 - 发布订单')

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
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form" action="" lay-filter="component-form-group" id="form-order">

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">游戏</label>
                                    <div class="layui-input-block">
                                        <select name="game_id" lay-verify="required" lay-search="" show-name="x" display-name="游戏" lay-filter="game">
                                            <option value="" >请选择</option>
                                            @foreach($games as $item)
                                                <option value="{{ $item->id }}" >{{ $item->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">区</label>
                                    <div class="layui-input-block">
                                        <select name="region_id" id="region" lay-verify="required" lay-search="" show-name="x" display-name="区" lay-filter="region">

                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">服</label>
                                    <div class="layui-input-block">
                                        <select name="server_id" id="server" lay-verify="required" lay-search="" show-name="x" display-name="服">

                                        </select>
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">角色名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_role" lay-verify="required"  class="layui-input"   value="">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">账号</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_account" lay-verify="required"  class="layui-input"   value="">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">密码</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="game_password" lay-verify="required"  class="layui-input"   value="">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练类型</label>
                                    <div class="layui-input-block">
                                        <select name="game_leveling_type_id" id="game-leveling-types" lay-verify="required" lay-search="" show-name="x" display-name="区">

                                        </select>
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练标题</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="title" lay-verify="required"  class="layui-input"   value="">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item layui-form-text">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练说明</label>
                                    <div class="layui-input-block">
                                        <textarea name="explain" lay-verify="required"  class="layui-textarea" ></textarea>
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练要求</label>
                                    <div class="layui-input-block">
                                        <textarea name="requirement" lay-verify="required"  class="layui-textarea"  ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="amount" lay-verify="required" class="layui-input"   value="">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">安全保证金</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="security_deposit" lay-verify="required"  class="layui-input"   value="">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">效率保证金</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="efficiency_deposit" lay-verify="required"  class="layui-input"   value="">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练天数</label>
                                    <div class="layui-input-block">
                                        <select name="day"  lay-verify="required" lay-search="" show-name="x" display-name="区" lay-filter="region">
                                            @for($i=1;$i<=90;$i++)
                                                <option value="{{ $i }}">{{ $i }}天</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">代练小时</label>
                                    <div class="layui-input-block">
                                        <select name="hour"  lay-verify="required" lay-search="" show-name="x" display-name="区" lay-filter="region">
                                            @for($i=1;$i<=24;$i++)
                                                <option value="{{ $i }}">{{ $i }}小时</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">玩家电话</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="player_phone" lay-verify="required"  class="layui-input" value="">
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">商户QQ</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="user_qq"  lay-verify="required" class="layui-input"   value="">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <div class="layui-footer" style="left: 0;">

                                        <button class="qs-btn qs-btn-sm" style="width: 80px;" lay-submit  lay-filter="store">确定下单</button>

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
            var form = layui.form, layer = layui.layer, layTpl = layui.laytpl, element = layui.element, carousel = layui.carousel;

            @include('front.order-operation.operation', ['type' => 'detail'])

            // 切换游戏时加载游戏的区
            form.on('select(game)', function (data) {
                if (data.value) {
                    $.post('{{ route('game.regions') }}', {game_id:data.value}, function (result) {
                        var region = '<option value="">请选择</option>';
                        $(result.content).each(function (index, value) {
                            region += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#region').html(region);
                        layui.form.render();
                    }, 'json');

                    $.post('{{ route('game.leveling-types') }}', {game_id:data.value}, function (result) {
                        var gameLevelingTypes = '';
                        $(result.content).each(function (index, value) {
                            gameLevelingTypes += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#game-leveling-types').html(gameLevelingTypes);
                        layui.form.render();
                    }, 'json');

                    $('#server').html('');
                    layui.form.render();
                }
            });
            // 切换游戏区时加载游戏的服
            form.on('select(region)', function (data) {
                $.post('{{ route('game.servers') }}', {region_id:data.value}, function (result) {
                    var server = '';
                    $(result.content).each(function (index, value) {
                        server += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    $('#server').html(server);
                    layui.form.render();
                }, 'json');
            });

            // 下单
            form.on('submit(store)', function (data) {
                console.log(data);
                $.post("{{ route('order.store') }}", data.field, function (data) {
                    if (data.status === 1) {
                        layer.msg(data.message, {icon: 1});
                        $('#form-order')[0].reset();
                        $('#server').html('');
                        $('#game-leveling-types').html('');
                    } else {
                        layer.msg(data.message, {icon: 5});
                        return false;
                    }
                }, 'json');
                return false;
            });

        });
    </script>
@endsection

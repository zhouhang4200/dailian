@extends('back.layouts.app')

@section('title', ' | 封号列表')

@section('css')
    <style>
        .layui-laypage-skip .layui-input{
            height: 26px !important;
        }
        .layui-laypage .layui-laypage-curr .layui-laypage-em {
            background-color:#198cff;
        }
        .layui-table-view .layui-table[lay-size=sm] .layui-table-cell {
            height: 32px;
            line-height: 32px;
        }
        .layui-table-body{
            height: auto !important;
        }
        .layui-table-view{
            box-sizing: content-box;
        }
    </style>
@endsection

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">
                        封号列表
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form" id="search-flow" lay-filter="tab">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="nameOrId"  placeholder="昵称或ID" value="">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="start_time" name="start_time"  placeholder="开始时间" value="">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="end_time" name="end_time"  placeholder="结束时间" value="">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" lay-submit="" lay-filter="search">搜索</button>
                                    <button type="button" class="layui-btn layui-btn-normal create" >添加</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="layui-tab layui-tab-brief layui-form" lay-filter="order-list">
                    <ul class="layui-tab-title">
                        <li id="all" class="layui-this" lay-id="0">全部</li>
                        <li id="blockading" class="" lay-id="1">封号中</li>
                        <li id="unblockade" class="" lay-id="3">封号结束</li>
                        <li id="blockaded" class="" lay-id="2">永久封号</li>
                    </ul>
                </div>
                <table id="blockade" lay-filter="blockade" ></table>
            </div>
        </div>
    </div>

    <div class="layui-tab-content" id="update-time" style="display: none">
        <div class="layui-tab-item layui-show">
            <form class="layui-form" id="search-flow" lay-filter="tab">
                <div class="layui-form-item">
                    <label class="layui-form-label">*封号开始时间</label>
                    <div class="layui-input-inline">
                        <input type="text" style="width: 270px" lay-verify="required" class="form-control" id="start-times" name="start_time" value="">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">封号结束时间</label>
                    <div class="layui-input-inline">
                        <input type="text" style="width: 180px" class="form-control" id="end-times" placeholder="" name="end_time" value="">
                    </div>
                    <input class="form-control"  style="position:relative;float: left;line-height: 34px" type="checkbox" name="type" value="2">永久
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button class="btn btn-success" lay-submit="" lay-filter="update-time">确认</button>
                        <button type="button" class="layui-btn layui-btn-normal cancel" >取消</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/html" id="operation">
        @{{# if (d.type != 3) { }}
        <button class="layui-btn layui-btn-normal layui-btn-xs" style="width: 80px;padding: 0" data-user-id="@{{ d.user_id }}" data-id="@{{ d.id }}" lay-submit lay-filter="unblockade">解封账号</button>
        <button class="layui-btn layui-btn-normal layui-btn-xs" style="width: 80px;padding: 0" data-id="@{{ d.id }}" lay-submit data-start-time="@{{ d.start_time }}" data-end-time="@{{ d.end_time }}" data-type="@{{ d.type }}" lay-filter="change-time">调整时间</button>
        @{{# } }}
    </script>
    <script>
        layui.use(['form', 'laytpl', 'element', 'table'], function(){
            var form = layui.form, layer = layui.layer, table = layui.table,element = layui.element;
            var laydate = layui.laydate;
            //日期时间范围选择
            laydate.render({
                elem: '#start_time'
                ,type: 'datetime'
            });

            //日期时间范围选择
            laydate.render({
                elem: '#end_time'
                ,type: 'datetime'
            });

            //日期时间范围选择
            laydate.render({
                elem: '#start-times'
                ,type: 'datetime'
            });

            //日期时间范围选择
            laydate.render({
                elem: '#end-times'
                ,type: 'datetime'
            });
            // 加载数据
            table.render({
                elem: '#blockade',
                url: '{{ route('admin.blockade-account.table') }}',
                method:'post',
                cols: [[
                    {field: 'id', title: '流水号', width: 70},
                    {field: 'nameOrId', title: '昵称/ID', width: 100},
                    {field: 'reason', title: '封号原因'},
                    {field: 'remark', edit:'text',title: '备注', width: 300},
                    {field: 'start_time', title: '开始时间', width: 170},
                    {field: 'end_time', title: '结束时间', width: 170},
                    {field: 'left_time', title: '剩余时间', width: 170},
                    {field: 'button', title: '操作',width: 200, fixed: 'right', toolbar: '#operation'}
                ]],

                size: 'sm',
                width:1470,
                page: {
                    layout: [ 'count', 'prev', 'page', 'next', 'skip'],
                    groups: 10,
                    prev: '«',
                    next: '»',
                    limit:10
                },
                done: function(res, curr, count){
                    getCounts({});
                    form.render();
                }

            });
            // 监听修改
            table.on('edit(blockade)', function(obj){
                $.post("{{ route('admin.blockade-account.update') }}", {data:obj.data}, function (result) {
                    layer.msg(result.message, {time:1000})
                });
            });
                // 添加
            $('.create').click(function () {
                window.location.href="{{ route('admin.blockade-account.create') }}";
            });

            // 状态切换
            element.on('tab(order-list)', function () {
                form.render(null,'tab');
                $('form').prepend('<input name="type" type="hidden" value="' + this.getAttribute('lay-id')  + '">');
                reloadTable();
            });

            // 搜索
            form.on('submit(search)', function (data) {
                var nameOrId=data.field.nameOrId;
                var startTime=data.field.start_time;
                var endTime=data.field.end_time;
                reloadTable({nameOrId:nameOrId, startTime:startTime, endTime:endTime});
                return false;
            });

            // table重载
            function reloadTable(filters) {
                var data = {};
                if (filters == undefined) {
                    var nameOrId=$("input[name=nameOrId]").val();
                    var startTime=$("#start_time").val();
                    var endTime=$("#end_time").val();
                    var type=$("input[name=type]").val();
                    data={nameOrId:nameOrId,startTime:startTime,endTime:endTime,type:type};
                } else {
                    data = filters;
                }
                //执行重载
                table.reload('blockade', {
                    where: data,
                    page: {
                        curr: 1
                    },
                    done: function(res, curr, count){
                        getCounts(data);
                        form.render();
                    }
                });
            }

            // 获取每种状态的数量
            function getCounts(data)
            {
                $.post("{{ route('admin.blockade-account.count') }}", data, function (result) {
                    if(result.status == 1) {
                        $("#all").html("全部("+result.data.all+")");
                        $("#blockading").html("封号中("+result.data.blockading+")");
                        $("#unblockade").html("封号结束("+result.data.unblockade+")");
                        $("#blockaded").html("永久封号("+result.data.blockaded+")");
                    }
                });
            }

            // 解除封号
            form.on('submit(unblockade)', function(data){
                var id=this.getAttribute('data-id');
                layer.confirm('确认解封账号?', {icon: 3, title:'提示'}, function(index){
                    $.post("{{ route('admin.blockade-account.unblockade') }}", {
                        id:id
                    }, function(result){
                        layer.msg(result.message, {time:1000}, function () {
                            var nameOrId=$("input[name=nameOrId]").val();
                            var startTime=$("input[name=start_time]").val();
                            var endTime=$("input[name=end_time]").val();
                            data={nameOrId:nameOrId,startTime:startTime,endTime:endTime};
                            reloadTable(data);
                            form.render();
                        });
                    });
                    layer.closeAll();
                });
                return false;
            });

            // 调整时间
            form.on('submit(change-time)', function (data) {
                var id=this.getAttribute('data-id');
                var start_time=this.getAttribute('data-start-time');
                var end_time=this.getAttribute('data-end-time');
                var type=this.getAttribute('data-type');

                $("#start-times").val(start_time);
                if (end_time == '--'){
                    end_time = '';
                }
                $("#end-times").val(end_time);
                if (type == 2) {
                    $("input[name=type]").attr('checked', true);
                    form.render();
                }

                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '请设置封号时间段',
                    area: ['380px'],
                    content: $('#update-time')
                });
                // 取消按钮
                $('.cancel').click(function () {
                    layer.closeAll();
                });
                // 确定按钮
                form.on('submit(update-time)', function (data) {
                    layer.closeAll();
                    $.post("{{ route('admin.blockade-account.time') }}",{id:id, data:data.field}, function(result) {
                        layer.msg(result.message,{time:1000}, function () {
                            if(result.status == 1) {
                                reloadTable({});
                                form.render();
                            }
                        });
                    });
                    return false;
                });
                return false;
            });
        });
    </script>
@endsection

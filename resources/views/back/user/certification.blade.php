@extends('back.layouts.app')

@section('title', ' | 实名认证列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">
                        实名认证列表
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form" id="search-flow">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="name"  placeholder="用户名" value="{{ $name }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">状态</option>
                                            <option value="1" {{ 1 == $status ? 'selected' : '' }}>审核中</option>
                                            <option value="2" {{ 2 == $status ? 'selected' : '' }}>通过</option>
                                            <option value="3" {{ 3 == $status ? 'selected' : '' }}>拒绝</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="startDate" name="startDate"  placeholder="开始时间" value="{{ $startDate }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" id="endDate" name="endDate"  placeholder="结束时间" value="{{ $endDate }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" lay-submit="" lay-filter="search">搜索</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="certification">@include('back.user.certification-list', compact('certifications', 'name', 'status', 'startDate', 'endDate'))</div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'laytpl', 'laydate', 'element'], function(){
            var form = layui.form, layer = layui.layer;
            var laydate = layui.laydate;
            //日期时间范围选择
            laydate.render({
                elem: '#startDate'
                ,type: 'datetime'
            });

            //日期时间范围选择
            laydate.render({
                elem: '#endDate'
                ,type: 'datetime'
            });
            // 查找
            form.on('submit(search)', function (data) {
                var name=$("input[name=name]").val();
                var status=$("input[name=status]").val();
                var startDate=$("input[name=startDate]").val();
                var endDate=$("input[name=endDate]").val();
                var s = window.location.search;
                var page=s.getAddrVal('page');
                $.get("{{ route('admin.user.certification') }}", {status:status,name:name,startDate:startDate, endDate:endDate}, function (result) {
                    $('#certification').html(result);
                    form.render();
                });
                return false;
            }, 'json');

            String.prototype.getAddrVal = String.prototype.getAddrVal||function(name){
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var data = this.substr(1).match(reg);
                return data!=null?decodeURIComponent(data[2]):null;
            }
        });
    </script>
@endsection

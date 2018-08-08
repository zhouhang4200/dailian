@extends('front.layouts.home')

@section('title', ' - 接单中心')

@section('css')
    <link rel="stylesheet" href="/front/css/order-receiving.css">
@endsection

@section('main')
<div class="main">
    <div class="container">
        <div class="dl_nav f-cb">
            <p class="game choose">游戏：</p>
            <ul class="game_filter filter">
                <li>全部</li>
                <li class="islink">王者荣耀</li>
                <li>英雄联盟</li>
                <li>DNF</li>
                <li>QQ飞车</li>
                <li>决战平安京</li>
                <li>魔兽</li>
                <li>DOTA2</li>
                <li>全部</li>
                <li>王者荣耀</li>
                <li>英雄联盟</li>
                <li>决战平安京</li>
                <li>魔兽</li>
                <li>炫斗</li>
            </ul>
            <p class="price choose">价格：</p>
            <ul class="price_filter filter">
                <li>全部</li>
                <li>10元以下</li>
                <li>10元-100元（含）</li>
                <li class="islink">100元-200元（含）</li>
                <li>200元以上</li>
            </ul>
        </div>
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">区</label>
                    <div class="layui-input-inline">
                        <select name="qu" lay-filter="qu">
                            <option value="">请选择</option>
                            <option value="0">一区</option>
                            <option value="1">二区</option>
                            <option value="2">三区</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">服</label>
                    <div class="layui-input-inline">
                        <select name="fu" lay-filter="fu">
                            <option value="">请选择</option>
                            <option value="0">写作</option>
                            <option value="1" selected="">阅读</option>
                            <option value="2">游戏</option>
                            <option value="3">音乐</option>
                            <option value="4">旅行</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">代练类型</label>
                    <div class="layui-input-inline">
                        <select name="dl_type" lay-filter="dl_type">
                            <option value="">请选择</option>
                            <option value="0">写作</option>
                            <option value="1" selected="">阅读</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">其他</label>
                    <div class="layui-input-inline">
                        <input type="text" name="orther" placeholder="订单号/发布人/标题" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline query f-fr">
                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="query">查询</button>
                </div>
            </div>
        </form>
        <table class="layui-table" lay-skin="line">
            <colgroup>
                <col>
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="100">
            </colgroup>
            <thead>
            <tr>
                <th>代练标题/订单号</th>
                <th>游戏区服</th>
                <th>代练价格</th>
                <th>效率保证金</th>
                <th>安全保证金</th>
                <th>代练时间</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $item)
            <tr>
                <td>
                    <p class="item-title">【{{ $item->game_name }}】{{ $item->title }}
                        <span class="hot f-ff1">热</span>
                        <span class="top f-ff1">顶</span>
                    </p>
                    <p class="order_number">订单号：{{ $item->trade_no }}</p>

                </td>
                <td>{{ $item->region_name }}/{{ $item->server_name }}</td>
                <td class="price red">¥{{ $item->amount }}</td>
                <td class="price">¥{{ $item->efficiency_deposit }}</td>
                <td class="price">￥{{ $item->security_deposit }}</td>
                <td>72小时2小时</td>
                <td>
                    <button class="layui-btn layui-btn-primary layui-btn-sm">接单</button>
                </td>
            </tr>
            @empty

            @endforelse
            </tbody>
        </table>
        <div id="page"></div>
    </div>
</div>
@endsection

@section('js')
    <script>
        layui.use(['form','laypage'], function () {
            var form = layui.form,
                laypage =layui.laypage,
                layer = layui.layer;

            form.on('submit(query)', function(data){
                layer.alert(JSON.stringify(data.field), {
                    title: '最终的提交信息'
                })
                return false;
            });
            laypage.render({
                elem: 'page'
                ,count: 70 //数据总数，从服务端得到
                ,limit:10
                ,theme: '#198cff'
                ,jump: function(obj, first){
                    //obj包含了当前分页的所有参数，比如：
                    console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                    console.log(obj.limit); //得到每页显示的条数

                    //首次不执行
                    if(!first){
                        //do something
                    }
                }
            });
        })
    </script>
@endsection
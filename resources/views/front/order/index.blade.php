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
                <li @if(request('game_id') == 0) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['game_id' => 0], request()->except('game_id'))) }}">全部</a>
                </li>
                @foreach($games as $item)
                <li @if(request('game_id') == $item->id) class="islink" @endif>
                    <a href="{{ route('order', array_merge(['game_id' => $item->id], request()->except('game_id'))) }}">{{ $item->name }}</a>
                </li>
                @endforeach
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
                <td class="price red">¥ {{ $item->amount }}</td>
                <td class="price">¥ {{ $item->efficiency_deposit }}</td>
                <td class="price">￥{{ $item->security_deposit }}</td>
                <td>{{ $item->day ? $item->day . '天' : '' }}{{ $item->hour }}小时</td>
                <td>
                    <button class="layui-btn layui-btn-primary layui-btn-sm"
                            lay-submit lay-filter="take"
                            data-trade_no="{{ $item->trade_no }}"
                            data-take_password="{{ ! empty($item->take_password) ? true : false }}"
                            data-guest="{{ $guest }}"
                    >接单</button>
                </td>
            </tr>
            @empty

            @endforelse
            </tbody>
        </table>
        {{ $orders->appends(request()->all())->links('front.pagination.default') }}
    </div>
</div>
@endsection

@section('pop')
    <div id="take-pop" style="padding: 24px 0 15px 15px;display: none">
        <form class="layui-form" action="">
            <input type="hidden" name="trade_no">
            <div class="layui-form-item">
                <label class="layui-form-label">接单密码</label>
                <div class="layui-input-inline">
                    <input type="take_password" name="take_password" required  lay-verify="required" placeholder="请输入接单密码" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">支付密码</label>
                <div class="layui-input-inline">
                    <input type="pay_password" name="payment_password" required lay-verify="required" placeholder="请输入支付密码" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">忘记密码</div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="confirm-take">确定</button>
                    <button type="reset" class="layui-btn layui-btn-primary">取消</button>
                </div>
            </div>
        </form>
    </div>
    <div id="take-no-password-pop" style="padding: 24px 0 15px 15px;display: none">
        <form class="layui-form" action="">
            <input type="hidden" name="trade_no">
            <div class="layui-form-item">
                <label class="layui-form-label">支付密码</label>
                <div class="layui-input-inline">
                    <input type="pay_password" name="payment_password" required lay-verify="required" placeholder="请输入支付密码" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">忘记密码</div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="confirm-take">确定</button>
                    <button type="reset" class="layui-btn layui-btn-primary">取消</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'laydate', 'element'], function () {
            var form = layui.form ,layer = layui.layer, element = layui.element, laydate = layui.laydate;

            form.on('submit(take)', function (data) {

                if ($(data.elem).attr('data-guest')) {
                    layer.msg('请先登录');
                    return false;
                }

                $('input[name=trade_no]').val($(data.elem).attr('data-trade_no'));

                if ($(data.elem).attr('data-trade_password')) {
                    layer.open({
                        type: 1,
                        shade: 0.2,
                        title: '接单验证',
                        area: ['440px'],
                        content: $('#take-pop')
                    });
                } else {
                    layer.open({
                        type: 1,
                        shade: 0.2,
                        title: '接单验证',
                        area: ['440px'],
                        content: $('#take-no-password-pop')
                    });
                }
                return false;
            });
            // 确认接单
            form.on('submit(confirm-take)', function (data) {
                $.post('{{ route('order.operation.take') }}', {
                    trade_no:data.trade_no,
                    pay_password:encrypt($.trim(data.pay_password)),
                    trade_password:encrypt($.trim(data.trade_password))
                }, function (result) {
                    if (result.status == 0) {
                        layer.msg(result.message);
                    }
                }, 'json');
                return false;
            });
        });
    </script>
@endsection



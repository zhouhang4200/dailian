@extends('front.layouts.app')

@section('title', '账号 - 实名认证')

@section('css')
    <style>
        .layui-form-item .layui-input-inline {
            float: left;
            width: 120px;
            margin-right: 10px;
        }
        .layui-form-label {
            width:60px;
        }
    </style>
    <script>
        (function () {
            var socket=io(window.location.hostname);
            var user_id="{{ auth()->user()->parent_id }}";
            socket.on("certification:"+user_id, function (message) {
                $("#status").html(message);
            });
        })(window);
    </script>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <div class="layui-tab-item layui-show">
                <table class="layui-table"  lay-size="sm">
                    @if (! empty($realNameCertification))
                        <thead>
                        <tr>
                            <th>真实姓名</th>
                            <th>支付宝账号</th>
                            {{--<th>开户银行卡号</th>--}}
                            {{--<th>开户银行名称</th>--}}
                            <th>身份证号</th>
                            <th>申请认证时间</th>
                            <th>审核状态</th>
                            @if($realNameCertification->status == 3)
                                <th>原因</th>
                            @endif
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php $status = ['1' => '审核中', '2' => '通过', '3' => '拒绝']; ?>
                            <td>{{ $realNameCertification->real_name }}</td>
                            <td>{{ $realNameCertification->alipay_account }}</td>
                            {{--<td>{{ $realNameCertification->bank_card }}</td>--}}
                            {{--<td>{{ $realNameCertification->bank_name }}</td>--}}
                            <td>{{ $realNameCertification->identity_card }}</td>
                            <td>{{ $realNameCertification->created_at }}</td>
                            <td id="status">{{ $status[$realNameCertification->status] }}</td>
                                @if($realNameCertification->status == 3)
                                    <td>{{ $realNameCertification->remark }}</td>
                                @endif
                            @if ($realNameCertification->status == 2)
                                <td>完成</td>
                            @else
                                <td style="text-align: center"><button lay-id="{{ $realNameCertification->id }}" class="qs-btn layui-btn-normal layui-btn-small" id="edit">编缉</button></td>
                            @endif
                        </tr>
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        layui.use('form', function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;

            $("#edit").click(function () {
                var id = this.getAttribute('lay-id');
                window.location.href="{{ route('real-name-certification.edit') }}?id="+id;
            })
        });
    </script>
@endsection
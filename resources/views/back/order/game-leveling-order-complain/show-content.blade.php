@if(in_array(optional($order->complain)->status, [1, 3]))
    <div class="layui-form">
        <table class="layui-table">
            <tbody>
            <tr>
                <td>投诉订单 : {{ $order->trade_no }}</td>
                <td>申请时间 : {{ $order->complain->created_at }}</td>
                <td>处理时间 : {{ $order->complain->reason }}</td>
            </tr>
            <tr>
                <td>投诉方 : {{ $order->complain->complaint() }}</td>
                <td>被投诉方 : {{ $order->complain->beComplaint() }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">申请原因 : {{ $order->complain->reason }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="layui-row layui-col-space15" style="border: 1px solid #e6e6e6;box-sizing: border-box;margin:0;">
        <div class="row">
            <div class="col-xs-12">
                <div id="gallery-photos-lightbox">
                    <ul class="clearfix gallery-photos">
                        @foreach($order->complain->image as $img)
                            <li class="col-md-2">
                                <span href="{{ $img->path or '' }}" class="photo-box image-link photo" data-img="{{ $img->path or '' }}"
                                   style="background-image: url('{{ $img->path or '' }}');"></span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-form" style="margin-bottom: 18px;" id="message-list">
        <table class="layui-table">
            <colgroup>
                <col width="120">
                <col>
                <col width="180">
                <col width="80">
            </colgroup>
            <thead>
            <tr>
                <th>留言方</th>
                <th>留言说明</th>
                <th>留言时间</th>
                <th>留言证据</th>
            </tr>
            </thead>
            <tbody>
            @forelse($order->message as $msg)
                <tr>
                    <td>

                        @if($msg->from_user_id == $order->complain->parent_user_id)
                            投诉方
                        @elseif($msg->from_user_id != 0)
                            被投诉方
                        @endif

                        @if($msg->initiator == 1)
                            (发单)
                        @elseif($msg->initiator == 2)
                            (接单)
                        @else
                            客服
                        @endif
                    </td>
                    <td>{{ $msg->content }}</td>
                    <td>{{ $msg->created_at }}</td>
                    <td class="">
                        @foreach($msg->image as $img)
                            <button class="btn btn-primary photo" data-img="{{ $img->path }}">
                                <i class="fa fa-eye"></i>
                            </button>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="20" style="text-align: center">暂时没有数据</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($order->status != 9)

        <div class="form-horizontal layui-form">
            <div class="form-group">
                <label class="col-lg-1 control-label">留言说明</label>
                <div class="col-lg-6">
                    <textarea  name='content' style="width: 100%;min-height: 80px;" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-1 control-label">备注</label>
                <div class="col-lg-6">
                    <input type="text" name="remark" class="layui-input">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-1">
                    <button class="btn btn-primary" lay-submit="" lay-filter="send-complain-message"  lay-id="" lay-no="">提交</button>
                </div>
                <div class="col-lg-1">
                    <button class="btn btn-danger" lay-submit="" lay-filter="arbitration-pop"  lay-id="" lay-no="">仲裁</button>
                </div>
            </div>
        </div>

    @endif

@else
    暂时没有仲裁信息
@endif
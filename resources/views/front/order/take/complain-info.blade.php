@if(in_array(optional($order->complain)->status, [1, 3]))
<div class="layui-form">
    <table class="layui-table">
        <colgroup>
            <col width="150">
            <col width="150">
            <col width="200">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>申请仲裁</th>
            <th>申请时间</th>
            <th>申请原因</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $order->complain->parent_user_id == request()->user()->parent_id ? '您' : '对方' }}</td>
            <td>{{ $order->complain->created_at }}</td>
            <td>{{ $order->complain->reason }}</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="layui-row layui-col-space15" style="padding: 18px;border: 1px solid #e6e6e6;border-top:0;box-sizing: border-box;margin:0;margin-bottom: 18px;">
    @foreach($order->complain->image as $img)
        <div class="layui-col-xs12 layui-col-sm6 layui-col-md4 " style="height: 184px">
            <img data-img="{{ $img->path }}" class="photo" src="{{ $img->path }}" style="width: 100%;height: 100%">
        </div>
    @endforeach
</div>

<div class="layui-form" style="margin-bottom: 18px;" id="message-list">
    <table class="layui-table">
        <colgroup>
            <col width="90">
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
                <td>{{ $msg->from_user_id == request()->user()->parent_id ? '您' : '对方'}}</td>
                <td>{{ $msg->content }}</td>
                <td>{{ $msg->created_at }}</td>
                <td class="">
                    @foreach($msg->image as $img)
                        <button class="qs-btn photo" style="width: 42px; padding:0;" data-img="{{ $img->path }}">
                            <i class="iconfont icon-visible"></i>
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
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label" style="text-align: left;padding-left: 0;">留言说明</label>
            <div class="layui-input-block" style="max-width: auto;margin-left: 90px;">
                <textarea  name='content' style="width: 100%;min-height: 80px;" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="text-align: left;padding-left: 0;">上传证据</label>
            <div class="layui-input-block" style="max-width: auto;margin-left: 90px;">
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail pic-add" style="width: 100px;height: 100px;"></div>
                        <div style="height: 0;">
                    <span class=" btn-file" style="padding: 0;">
                        <span class="fileinput-new"></span>
                        <span class="fileinput-exists"></span>
                        <input type="file" name="pic1" id="picID" accept="image/gif,image/jpeg,image/x-png" />
                    </span>
                            <a href="javascript:;" class="fileinput-exists" data-dismiss="fileinput" style="padding: 0;">
                                <i class="iconfont icon-shanchu4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block" style="margin-left: 90px;">
                <button class="qs-btn" lay-submit="" lay-filter="send-complain-message"  lay-id="" lay-no="">立即提交</button>
            </div>
        </div>
    </form>
@endif
<script>
    layer.photos({
        anim: -1,
        photos: '#layer-photos-demo'
    });
</script>
@else
暂时没有仲裁信息
@endif

@extends('front.layouts.app')

@section('title', '个人资料')

@section('css')
    <style>
        .user-info{
            /*width: 970px;*/
            /*width: 1370px;*/
            height: 200px;
            border: 1px solid #ddd;
            display: flex;
            margin: auto;
            font-size: 13px;
        }
        .info-img{
            width: 80px;
            height: 80px;
            margin: 20px 0 20px 20px;
            border: 1px solid #ddd;
            background-size: 102%;
            background-image: url('/frontend/images/3.png');
        }
        .info-left{
            flex: 3;
            margin-top: 15px;
        }
        .layui-form-item{
            margin-bottom: 0;
            margin: 5;
        }
        .info-left .layui-form-item  .layui-inline .layui-input-inline{
            width: auto;
            text-indent: 20px;
        }
        .info-left .layui-form-item .layui-form-label{
            width: 85px;
            padding: 0;
            height: 25px;
            line-height: 30px;
        }
        .info-left .layui-form-item  .layui-inline{
            width: 250px;
            height: 25px;
            line-height: 30px;
        }
        .info-balance{
            flex: 1.2;
            height: 100px;
            margin: 8px 0 0 30px;
            position: relative;
        }
        .info-balance .available-balance{
            height: 33px;
            line-height: 34px;
        }
        .info-balance .blocked-balances{
            height: 33px;
            line-height: 33px;
        }
        .info-balance::before{
            content: "";
            position: absolute;
            left: -20px;
            top:20px;
            width: 1px;
            height: 70px;
            background-color: #ddd;
        }
        .icon{
            margin-left: 34px;
        }
        .icon > span + span{
            margin-left: 10px;
        }
        .info-left .layui-form-item .layui-inline {
            line-height: 17px;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <div class="user-info" style="height: 250px;">
                <div id="user-img" class="info-img fl"
                      style="float:left;width:80px;height:100px;background-image:url('{{ $user->avatar }}');background-size: cover !important;background-position: center !important;margin-bottom:3px;">
                    <button class="qs-btn layui-btn-normal layui-btn-mini" id="avatar-edit"
                            style="width:100%;padding:0;margin-top:105px;">修改头像
                    </button>
                    @if(Auth::user()->isParent())
                        <button class="qs-btn layui-btn-normal layui-btn-mini" id="profit-edit"
                           style="width:100%;margin-top:5px;margin-left:0; padding:0" onclick="profileEdit()">修改资料
                        </button>
                    @endif
                </div>
                <div class="info-left">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">昵称 ：</label>
                            <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                {{ $user->name }}
                            </label>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">主账号ID ：</label>
                            <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                {{ $user->parent_id }}
                            </label>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">类型 ：</label>
                            <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                @if ($user->isParent())
                                    主账号
                                @else
                                    子账号
                                @endif
                            </label>
                        </div>
                        <div class="layui-inline" style="width:270px;">
                            <label class="layui-form-label">最后登录 ：</label>
                            <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                {{ $user->last_login_at }}
                            </label>
                        </div>
                    </div>
                    @if ($user->isParent())
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">电话 ：</label>
                                <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                    {{ $user->phone }}
                                    </label>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">实名认证 ：</label>
                                <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                    @if(! empty($realNameCertification))
                                        @if($realNameCertification->status == 1)
                                            认证中
                                        @elseif($realNameCertification->status == 2)
                                            已认证
                                        @elseif($realNameCertification->status == 3)
                                            认证未通过
                                        @endif
                                    @else
                                        <a href="{{ route('real-name-certification.create') }}" style="color:#707070">去认证</a>
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">QQ ：</label>
                                <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                    {{ $user->qq }}
                                </label>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">微信 ：</label>
                                <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                    {{ $user->wechat }}
                                </label>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">年龄 ：</label>
                                <label class="layui-form-label" style="text-align: left;padding-left: 5px">
                                    {{ $user->age }}
                                </label>
                            </div>

                        </div>
                    @endif
                </div>
                <div class="info-balance">
                    <div class="available-balance">可用余额：
                        <span class="balance">{{ $user->userAsset ? $user->userAsset->balance + 0 : 0 }}</span>
                    </div>
                    <div class="blocked-balances">冻结金额：
                        {{ $user->userAsset ? $user->userAsset->frozen + 0 : 0 }}
                    </div>
                    <div class="blocked-balances">累计加款：
                        0
                    </div>
                    <div class="blocked-balances">累计提现：
                        0
                    </div>
                    <div class="blocked-balances">累计收入：
                        0
                    </div>
                    <div class="blocked-balances">累计支出：
                        0
                    </div>
                    <button class="qs-btn layui-btn-normal layui-btn-custom-mini charge" lay-filter="charge" lay-submit="">余额充值</button>
                    <button id="withdraw" class="qs-btn qs-btn-normal qs-btn-custom-mini" type="button" >余额提现</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pop')
    <div id="profile-edit" style="display: none; padding: 10px">
        <form class="layui-form"  action="">
            <div>
                <div class="layui-form-item">
                    <label class="layui-form-label">昵称:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" lay-verify="required|length" value="{{ $parentUser->name }}" autocomplete="off" placeholder="请输入账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">年龄:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="age" lay-verify="required|length" value="{{ $parentUser->name }}" autocomplete="off" placeholder="请输入账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">QQ:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="qq" lay-verify="required|length" value="{{ $parentUser->name }}" autocomplete="off" placeholder="请输入账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">微信:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="wechat" lay-verify="required|length" value="{{ $parentUser->name }}" autocomplete="off" placeholder="请输入账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <button type="hidden" class="qs-btn layui-btn-normal" lay-submit="" lay-filter="profile-update" style="margin-left: 180px">提交</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="avatar-update" style="display: none; padding: 10px">
        <form class="layui-form"  action="">
            <div class="layui-upload">
                <button type="button" class="qs-btn layui-btn-normal" id="test1">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo1" style="width:200px;height:200px">
                    <p id="demoText"></p>
                </div>
            </div>
            <input type="hidden" name="avatar" id="avatar" value="">
            <div class="layui-form-item">
                <div class="layui-input-inline">
                    <button type="hidden" class="qs-btn layui-btn-normal" lay-submit="" lay-filter="avatar">提交</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'table', 'upload'], function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;
            var upload = layui.upload;
            // 验证
            form.verify({
                length: [
                    /^\S{1,30}$/
                    ,'长度超出允许范围'
                ]
                ,pass: [
                    /^[\S]{6,12}$/
                    ,'密码必须6到12位，且不能出现空格'
                ]
            });
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#test1'
                ,url: "{{ route('profile.avatar-show') }}"
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#demo1').attr('src', result); //图片链接（base64）
                    });
                }
                ,done: function(res){
                    if(res.code == 2){
                        return layer.msg('上传失败');
                    }
                    $('#avatar').val(res.message);
                }
            });

            // 修改头像
            $('#avatar-edit').on('click', function () {
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '修改头像',
                    area: ['250px', '370px'],
                    content: $('#avatar-update')
                });
            });

            form.on('submit(avatar)', function(data) {
                $.post("{{ route('profile.avatar-update') }}", {data:data.field}, function (result) {
                    if (result.status == 1) {
                        layer.msg('更新成功', {
                            time:1500,
                            icon:6
                        });
                        var styles="url('"+result.path+"')";
                        $('#user-img').css('background-image', styles);
                    } else {
                        layer.msg('更新失败', {
                            time:1500,
                            icon:5
                        })
                    }
                });
                layer.closeAll();
                return false;
            });
        });

        function profileEdit()
        {
            window.location.href="{{ route('profile.edit') }}"
        }
    </script>
@endsection
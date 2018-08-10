<div class="consult-pop" style="display: none; padding:  0 20px">
    <div class="layui-tab-content">
        <span style="color:red;margin-right:15px;">双方友好协商撤单，若有分歧可以在订单中留言或申请客服介入；若申请成功，此单将被锁定，若双方取消撤单会退回至原有状态。<br/></span>
        <form class="layui-form" method="POST" action="">
            <input type="hidden" name="trade_no">
            {!! csrf_field() !!}
            <div  id="info">
                <div class="layui-form-item">
                    <label class="layui-form-label">*需要对方支付代练费（元）</label>
                    <div class="layui-input-block">
                        <input type="text" name="amount" lay-verify="required|number"  autocomplete="off"
                               placeholder="请输入代练费" class="layui-input" style="width:400px">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">对方已支付代练费（元）</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_amount" id="order_amount" lay-verify=""
                               autocomplete="off" placeholder="" class="layui-input order_amount" style="width:400px" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">*我原意赔付保证金（元）</label>
                    <div class="layui-input-block">
                        <input type="text" name="deposit" lay-verify="required|number"
                               autocomplete="off"
                               placeholder="请输入保证金" class="layui-input" style="width:400px">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">我已预付安全保证金（元）</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_security_deposit"  lay-verify=""  autocomplete="off"
                               placeholder="" class="layui-input safe" style="width:400px" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">我已预付效率保证金（元）</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_efficiency_deposit"  lay-verify=""  autocomplete="off"
                               placeholder="" class="layui-input effect" style="width:400px" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">*撤销理由</label>
                    <div class="layui-input-block">
                            <textarea placeholder="请输入撤销理由" name="reason" lay-verify="required"
                                      class="layui-textarea" style="width:400px"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="qs-btn  layui-btn-normal" lay-submit lay-filter="confirm-apply-consult">立即提交</button>
                        <span cancel class="qs-btn  layui-btn-normal cancel">取消</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="complain-pop" style="display: none; padding: 20px">
    <form class="layui-form">
        <input type="hidden" name="trade_no">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">证据截图</label>
            <div class="layui-input-block">
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail complain-image-1" style="width: 100px;height: 100px;"></div>
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
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail complain-image-2" style="width: 100px;height: 100px;"></div>
                        <div>
                                <span class="btn-file" style="padding: 0;">
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
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail complain-image-3" style="width: 100px;height: 100px;"></div>
                        <div>
                               <span class="btn-file" style="padding: 0;">
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
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">仲裁理由</label>
            <div class="layui-input-block">
                <textarea placeholder="请输入申请仲裁理由" name="reason"  class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="qs-btn layui-btn-normal" id="submit" lay-submit lay-filter="confirm-apply-complain">确认
                </button>
                <span cancel class="qs-btn  layui-btn-normal cancel">取消</span>
            </div>
        </div>
    </form>
</div>

<div class="apply-complete-pop" style="display: none; padding: 20px">
    <form class="layui-form">
        <input type="hidden" name="trade_no">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">完成截图</label>
            <div class="layui-input-block">
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail apply-complete-image-1" style="width: 100px;height: 100px;"></div>
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
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail apply-complete-image-2" style="width: 100px;height: 100px;"></div>
                        <div>
                                <span class="btn-file" style="padding: 0;">
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
                <div class="fileinput-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" id="exampleInputUpload">
                        <div class="fileinput-new thumbnail" style="width: 100px;height: 100px;">
                            <img id='picImg' style="width: 60px;height:60px;margin:auto;margin-top:20px;" src="/front/images/upload-btn-bg.png" alt="" />
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail apply-complete-image-3" style="width: 100px;height: 100px;"></div>
                        <div>
                               <span class="btn-file" style="padding: 0;">
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
            <div class="layui-input-block">
                <button class="qs-btn layui-btn-normal" id="submit" lay-submit lay-filter="confirm-apply-complete">确认
                </button>
                <span cancel class="qs-btn  layui-btn-normal cancel">取消</span>
            </div>
        </div>
    </form>
</div>
<div class="layui-boxx" id="im-pop" style="display: none">
    <div class="layui-layer-titlee" style="cursor: move;">
        <div class="layui-unselect layim-chat-title">
        </div>
    </div>
    <div id="layui-layim-chat" class="layui-layer-contentt">
        <ul class="layui-unselect layim-chat-list">
            <li class="layim-friend1008612 layim-chatlist-friend1008612 layim-this" layim-event="tabChat">
                <img src="lib/css/res/touxiang.jpg">
                <span>小闲</span>
                <i class="layui-icon" layim-event="closeChat">ဇ</i>
            </li>
        </ul>
        <div class="layim-chat-box layui-form">
            <div class="layim-chat layim-chat-friend layui-show">
                <div class="layim-chat-main">

                </div>
                <div class="layim-chat-footer">
                    <div class="layim-chat-textarea">
                        <textarea name="content"></textarea>
                    </div>
                    <div class="layim-chat-bottom">
                        <div class="layim-chat-send">
                            <span class="qs-btn opt-btn cancel" lay-submit="" lay-filter="cancel">关闭</span>
                            <span class="qs-btn opt-btn layim-send-btn" lay-submit="" lay-filter="send-message">发送</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span class="layui-layer-setwin">
        <a class="layui-layer-ico layui-layer-close layui-layer-close1" href="javascript:;"></a>
    </span>
</div>
<div class="layui-carousel" id="carousel" style="display: none"></div>

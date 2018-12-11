@extends('back.layouts.app')

@section('title', ' | 系统设置')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">系统设置</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-tab layui-tab-brief layui-form" lay-filter="key">
                            <ul class="layui-tab-title">
                                <li @if($key == 'withdraw') class="layui-this" @endif lay-id="withdraw">提现设置
                                </li>
                                <li @if($key == 'mini-program') class="layui-this" @endif lay-id="mini-program">小程序环境
                                </li>
                                <li @if($key == 'spread') class="layui-this" @endif lay-id="spread">推广设置
                                </li>
                            </ul>
                        </div>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item @if(request('key') == 'spread') layui-show @endif">
                                <form class="layui-form layui-form-pane" action="{{ route('admin.setting.update', ['key' => 'spread']) }}" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="key" value="spread">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">推广设置</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="spread" placeholder="推广比例，请填写0.1,0.2,0.3..." value="{{ \Unisharp\Setting\SettingFacade::get('spread')['spread'] ?? ''  }}" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <button class="btn btn-success" lay-submit="" lay-filter="demo2">保存设置</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('#export').click(function () {
            var url = "?export=1&" + $('#search-flow').serialize();
            window.location.href = url;
        });

        layui.use(['form', 'layedit', 'laydate', 'element'], function() {
            var form = layui.form, layer = layui.layer, element = layui.element, laydate = layui.laydate;

            element.on('tab(key)', function(){
                window.location.href="{{ route('admin.setting') }}?key=" + this.getAttribute('lay-id');
            });
        });
    </script>
@endsection

@extends('back.layouts.app')

@section('title', ' | 用户资料')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class=""><span>首页</span></li>
                <li class=""><a href="{{ route('admin.user') }}"><span>商户列表</span></a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive">
                        <div class="layui-tab layui-tab-brief" lay-filter="detail">
                            <ul class="layui-tab-title">
                                <li  class="layui-this"  lay-id="detail"><a href="{{ route('admin.user.show', ['id' => Route::input('id')])  }}">用户资料</a></li>
                                <li lay-id="authentication"><a href="{{ route('admin.user.certification', ['id' => Route::input('id')])  }}">实名认证</a></li>
                                <li lay-id="authentication"><a href="{{ route('admin.user.show', ['id' => Route::input('id')])  }}">转账信息</a></li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="layui-tab-item layui-show detail">
                                    <form class="layui-form" action="">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">ID</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="id" required disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->id }}">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">名字</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="name" disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">电话</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="phone" disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->phone }}">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">邮箱</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="email" disabled readonly  lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">QQ</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="qq" disabled readonly  autocomplete="off" class="layui-input" value="{{ $user->qq }}">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">微信</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="wecaht" disabled readonly   lay-verify="" autocomplete="off" class="layui-input" value="{{ $user->wechat }}">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="layui-tab-item authentication"></div>
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
        //Demo
        layui.use('form', function(){
            var form = layui.form;
        });
    </script>
@endsection
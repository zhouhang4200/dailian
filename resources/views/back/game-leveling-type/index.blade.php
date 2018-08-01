@extends('back.layouts.app')

@section('title', ' | 游戏代练类型列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">游戏代练类型列表</li>
                </ul>

                <div class="layui-tab-content">

                    @if(Session::has('success'))
                        <div class="col-lg-12">
                            <div class="alert alert-block alert-success fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4>{{ \Session::get('success', 'default') }}</h4>
                            </div>
                        </div>
                    @endif

                    @if(Session::has('fail'))
                        <div class="col-lg-12">
                            <div class="alert alert-block alert-danger fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4>{{ \Session::get('fail', 'default') }}</h4>
                            </div>
                        </div>
                    @endif

                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">

                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="name"  placeholder="游戏代练类型名" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="search">搜索</button>
                                    <a href="{{ route('admin.game-leveling-type.create') }}" class="layui-btn layui-btn-normal" type="button" id="create" >新增</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <table class="layui-table" lay-size="sm">
                            <thead>
                            <tr>
                                <th>所属游戏</th>
                                <th>类型名</th>
                                <th>手续费</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($gameLevelingTypes as $item)
                                <tr>

                                    <td>{{ optional($item->game)->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->poundage + 0 }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.game-leveling-type.update', ['id' => $item->id]) }}" class="layui-btn layui-btn-normal layui-btn-mini complete" data-id="">修改</a>
                                        <button type="button" class="layui-btn layui-btn-mini layui-btn-danger refuse" data-url="{{ route('admin.game-leveling-type.delete', ['id' => $item->id]) }}" lay-submit lay-filter="delete">删除</button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $gameLevelingTypes->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
@endsection

<?php
$currentRouteName = Route::currentRouteName();
$currentOneLevelMenu = explode('.', Route::currentRouteName())[0];

?>
<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="{{ url('admin') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>首页</span>
                        </a>
                    </li>

                    <li @if(in_array($currentRouteName, [
                        'admin.order',
                        'admin.order.show',
                        'admin.order.log',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>订单</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.order') }}" @if(in_array($currentRouteName, [
                                    'admin.order',
                                    'admin.order.show',
                                    'admin.order.log',
                                ])) class="active" @endif>
                                    订单管理
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li @if(in_array($currentRouteName, [
                        'admin.game',
                        'admin.region',
                        'admin.server',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa  fa-life-ring"></i>
                            <span>游戏/区/服配置</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.game') }}" @if($currentRouteName == 'admin.game') class="active" @endif>
                                    游戏管理
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.region') }}" @if($currentRouteName == 'admin.region') class="active" @endif>
                                    游戏区管理
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.server') }}" @if($currentRouteName == 'admin.server') class="active" @endif>
                                    游戏服管理
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li @if(in_array($currentRouteName, [
                        'admin.balance-withdraw',
                        'admin.platform-finance-report-day',
                        'admin.user-finance-report-day',
                        'admin.user-asset-flow',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa  fa-money"></i>
                            <span>财务</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.balance-withdraw') }}" @if($currentRouteName == 'admin.balance-withdraw') class="active" @endif>
                                    用户提现管理
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.platform-finance-report-day') }}" @if($currentRouteName == 'admin.platform-finance-report-day') class="active" @endif>
                                    平台资金日报
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.user-finance-report-day') }}" @if($currentRouteName == 'admin.user-finance-report-day') class="active" @endif>
                                    用户资金日报
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.user-asset-flow') }}" @if($currentRouteName == 'admin.user-asset-flow') class="active" @endif>
                                    用户资金明细
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li @if(in_array($currentRouteName, [
                        'admin.user',
                        'admin.user.show',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-users"></i>
                            <span>用户管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.user') }}" @if($currentRouteName == 'admin.user') class="active" @endif>
                                    用户列表
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li @if(in_array($currentRouteName, [
                        'admin.article.notice',
                        'admin.article.notice-create',
                        'admin.article.notice-store',
                        'admin.article.notice-edit',
                        'admin.article.notice-update',
                        'admin.article.notice-delete',
                        'admin.article.category-notice',
                        'admin.article.category-notice-create',
                        'admin.article.category-notice-store',
                        'admin.article.category-notice-edit',
                        'admin.article.category-notice-update',
                        'admin.article.category-notice-delete',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-users"></i>
                            <span>公告管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.article.category-notice') }}" @if($currentRouteName == 'admin.article.category-notice') class="active" @endif>
                                    公告列表
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li @if(in_array($currentRouteName, [
                        'admin.article.help',
                        'admin.article.help-create',
                        'admin.article.help-store',
                        'admin.article.help-edit',
                        'admin.article.help-update',
                        'admin.article.help-delete',
                        'admin.article.category-help',
                        'admin.article.category-help-create',
                        'admin.article.category-help-store',
                        'admin.article.category-help-edit',
                        'admin.article.category-help-update',
                        'admin.article.category-help-delete',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-users"></i>
                            <span>帮助管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.article.category-help') }}" @if($currentRouteName == 'admin.article.category-help') class="active" @endif>
                                    帮助列表
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>

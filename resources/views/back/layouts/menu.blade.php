<?php
$currentRouteName = Route::currentRouteName();
$currentOneLevelMenu = explode('.', Route::currentRouteName())[0];

$orderRoute = [
    'admin.game-leveling-order',
    'admin.game-leveling-order.show',
    'admin.game-leveling-order.log',
    'admin.game-leveling-order-complain',
];

$financeRoute = [
    'admin.balance-withdraw',
    'admin.platform-finance-report-day',
    'admin.user-finance-report-day',
    'admin.user-asset-flow',
];

$gameRoute = [
    'admin.game',
    'admin.game.create',
    'admin.game.update',
    'admin.region',
    'admin.region.create',
    'admin.region.update',
    'admin.server',
    'admin.server.create',
    'admin.server.update',
    'admin.game-type',
    'admin.game-type.create',
    'admin.game-type.update',
    'admin.game-class',
    'admin.game-class.create',
    'admin.game-class.update',
    'admin.game-leveling-type',
    'admin.game-leveling-type.create',
    'admin.game-leveling-type.update',
];

$systemRoute = [
    'admin.admin-user',
    'admin.admin-user.create',
    'admin.admin-user.update',
    'admin.admin-role',
    'admin.admin-role.create',
    'admin.admin-role.update',
    'admin.admin-permission',
    'admin.admin-permission.create',
    'admin.admin-permission.update',
    'admin.admin-permission-group',
    'admin.admin-permission-group.create',
    'admin.admin-permission-group.update',
];


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

                    <li @if(in_array($currentRouteName, $orderRoute)) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>订单管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.game-leveling-order') }}" @if(in_array($currentRouteName, [
                                    'admin.game-leveling-order',
                                    'admin.game-leveling-order.show',
                                    'admin.game-leveling-order.log',
                                ])) class="active" @endif>
                                    订单列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.game-leveling-order-complain') }}" @if(in_array($currentRouteName, [
                                    'admin.game-leveling-order-complain',

                                ])) class="active" @endif>
                                    仲裁订单
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


                    <li @if(in_array($currentRouteName, $financeRoute)) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa  fa-money"></i>
                            <span>财务管理</span>
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
                            <i class="fa  fa-volume-up"></i>
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
                            <i class="fa fa-wrench"></i>
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

                    <li @if(in_array($currentRouteName, $gameRoute)) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa  fa-life-ring"></i>
                            <span>游戏管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.game') }}" @if(in_array($currentRouteName, [
                                    'admin.game',
                                    'admin.game.create',
                                    'admin.game.update',
                                ])) class="active" @endif>
                                    游戏列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.region') }}" @if(in_array($currentRouteName, [
                                    'admin.region',
                                    'admin.region.update',
                                    'admin.region.create',
                                ])) class="active" @endif>
                                    游戏区列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.server') }}" @if($currentRouteName == 'admin.server') class="active" @endif>
                                    游戏服列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.game-class') }}" @if($currentRouteName == 'admin.game-class') class="active" @endif>
                                    游戏分类列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.game-type') }}" @if($currentRouteName == 'admin.game-type') class="active" @endif>
                                    游戏类型列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.game-leveling-type') }}" @if($currentRouteName == 'admin.game-leveling-type') class="active" @endif>
                                    游戏代练类型列表
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li @if(in_array($currentRouteName, $systemRoute)) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-share-alt"></i>
                            <span>系统管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.admin-user') }}" @if($currentRouteName == 'admin.admin-user') class="active" @endif>
                                    管理员列表
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.admin-role') }}" @if($currentRouteName == 'admin.admin-role') class="active" @endif>
                                    角色管理
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.admin-permission') }}" @if($currentRouteName == 'admin.admin-permission') class="active" @endif>
                                    权限管理
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.admin-permission-group') }}" @if($currentRouteName == 'admin.admin-permission-group') class="active" @endif>
                                    权限组管理
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>

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
                            <span>后台权限</span>
                        </a>
                    </li>

                    <li @if(in_array($currentRouteName, [
                        'order.platform.index',
                        ])) class="open active" @endif>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>订单管理</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                                <li>
                                    <a href="" @if($currentRouteName == 'order.platform.index') class="active" @endif>
                                        权限列表
                                    </a>
                                </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>

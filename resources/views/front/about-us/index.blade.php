@extends('front.layouts.home')

@section('title', ' - 关于我们')

@section('css')
    <link rel="stylesheet" href="/front/css/about-us.css">
@endsection

@section('main')
    <div class="main f-cb">
        <div class="bg">
            <img src="/front/images/about_us.jpg" alt="">
        </div>
        <div class="container f-cb" style="height: 650px">
            <div class="content">
                <h1>公司介绍</h1>
                <p>Company introduction</p>
                <span>
                    一起游网络是国内目前手游产品在线销售的领导者，主营中国区大陆苹果礼品卡销售、steam卡、手游充值、手游代练等虚拟服务业务；电商平台代运营、手游厂商官方充值。 主要销售渠道：千手B2B供货平台；B2C电商平台（淘宝、京东等电商旗舰店）；API标准接口外放资源供货；微信H5充值频道。业务经营区域及用户规模：对接下游近2000
                    多家分布全国的手游分销商，服务过的终端用户人数总量超过1亿人。
                </span>
            </div>
            <div class="content">
                <h1>联系我们</h1>
                <p>Contact us</p>
                <div id="allmap"></div>
            </div>
            <div class="content">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-md3">
                        <div class="content-box">
                            <h1>
                                <i class="iconfont icon-HOME"></i>
                            </h1>
                            <span>公司</span>
                            <p>武汉一起游网络科技有限公司</p>
                        </div>

                    </div>
                    <div class="layui-col-md3">
                        <div class="content-box">
                            <h1>
                                <i class="iconfont icon-zuobiao"></i>
                            </h1>
                            <span>地址</span>
                            <p style="text-align: left;">武汉东湖新技术开发区关谷大道77号金融港B27栋16楼02室</p>
                        </div>
                    </div>
                    <div class="layui-col-md3">
                        <div class="content-box">
                            <h1>
                                <i class="iconfont icon-qq"></i>
                            </h1>
                            <span>QQ</span>
                            <p>530433534</p>
                        </div>
                    </div>
                    <div class="layui-col-md3">
                        <div class="content-box">
                            <h1>
                                <i class="iconfont icon-mail"></i>
                            </h1>
                            <span>邮箱</span>
                            <p>530433534@qq.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=87azcIUFufa2kZhGHZo0ER85x2SVvet4"></script>
    <script type="text/javascript">
        // 百度地图API功能
        var map = new BMap.Map("allmap");
        var point = new BMap.Point(114.436352, 30.460479);
        map.centerAndZoom(point, 18);

        var marker = new BMap.Marker(point); // 创建标注
        map.addOverlay(marker); // 将标注添加到地图中
        marker.disableDragging(); // 不可拖拽
    </script>
@endsection
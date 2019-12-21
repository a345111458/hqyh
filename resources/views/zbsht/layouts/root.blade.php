<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title','hqyh.test')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ URL::asset('layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ URL::asset('layuiadmin/style/admin.css') }}" media="all">
    <link rel="stylesheet" href="{{ URL::asset('layuiadmin/style/login.css') }}" media="all">

    @yield('styleCss')
</head>

<body>
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <!-- 主体内容 -->
        @yield('content')

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script src="{{ URL::asset('layuiadmin/layui/layui.js') }}"></script>

@yield('scriptJs')

<script>
    layui.config({
        base: '/layuiadmin/',
    }).extend({
        index: 'lib/index'
    }).use(['table','layer'],function() {
            var $ = layui.$
                , form = layui.form
                , layer = layui.layer
                , table = layui.table;
            form.render();

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                }
            });
    })
</script>
</body>
</html>
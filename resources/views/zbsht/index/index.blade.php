@extends('zbsht.layouts.root')

@section('title','后台首页')


@section('content')
    <!-- header 头部 -->
    @include('zbsht.layouts.header')

    <!-- 侧边菜单 -->
    @include('zbsht.layouts.leftMenu')

    <!-- 页面标签 -->
    @include('zbsht.layouts.pageTab')

    <!-- 主体内容 -->
    <div class="layui-body" id="LAY_app_body">
        <div class="layadmin-tabsbody-item layui-show">
            <iframe src="home/console.html" frameborder="0" class="layadmin-iframe"></iframe>
        </div>
    </div>
@stop

@section('scriptJs')
    <script>
        layui.config({
            base: '/layuiadmin/'
        }).extend({
            index: 'lib/index',
        }).use(['index','user','layer'],function(){
            var $ = layui.$
                ,form = layui.form;



        });
    </script>
@stop
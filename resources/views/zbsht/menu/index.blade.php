@extends('zbsht.layouts.root')
@section('title','菜单管理')


@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form layui-form-pane layui-card-header layuiadmin-card-header-auto">
                    <div class="layui-inline">
                        <label class="layui-form-label">登录名</label>
                        <div class="layui-input-block">
                            <input type="text" name="account" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">手机</label>
                        <div class="layui-input-block">
                            <input type="text" name="mobile" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            <input type="text" name="email" placeholder="请输入" autocomplete="off" class="layui-input">
                            @csrf
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">查询条数</label>
                        <div class="layui-input-block">
                            <select name="limits">
                                <option value="">查询条数</option>
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-admin" data-type="batchdel">删除</button>
                    <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加</button>
                </div>
                {{--<table id="LAY-user-back-manage"  lay-filter="LAY-user-back-manage"></table>--}}

            </div>

            <div class="layui-card-body">
                <div class="layui-collapse ">
                    <div class="layui-row">
                        @foreach($group as $k=>$v)
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title">
                                    {{ $groupTic[$k]['name'] }}
                                    <span class="button" lay-data="{{ $groupTic[$k]['id'] }}">[编辑]</span>
                                </h2>
                                <div class="layui-colla-content layui-show">
                                    @foreach($v as $vv)
                                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm button" lay-data="{{ $vv['id'] }}">{{ $vv['name'] }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop


@section('scriptJs')
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="getCheckData">获取选中行数据</button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
            <button class="layui-btn layui-btn-sm" lay-event="isAll">验证是否全选</button>
        </div>
    </script>

    <script>
        layui.config({
            base: '/layuiadmin/',
        }).extend({
            index: 'lib/index'
        }).use(['table','layer','element'],function(){
            var $ = layui.$
                ,element = layui.element
                ,form = layui.form
                ,layer = layui.layer
                ,table = layui.table;
            form.render();

            $('.button').on('click',function(e){
                var menuId = $(this).attr('lay-data');
                layer.open({
                    type: 2,
                    title: '编辑菜单',
                    shadeClose: true,
                    shade: 0.3,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['600px', '500px'],
                    content: "/zbsht/menu/"+ menuId,
                });
            });




            // 数据表格
            table.render({
                elem: '#LAY-user-back-manage'
                ,url: "{{ route('menu.menudata') }}"
                ,toolbar: '#toolbarDemo'
                ,where:{
                    _token:"{{ csrf_token() }}"
                }
                ,title: '会员列表'
                ,totalRow: true
                ,statrByZero:0
                ,cols: [[
                    {field:'tourPlayerId', title:'排序', width:80, fixed: 'left', unresize: true, sort: true, type:'numbers'}
                    ,{field:'name', title:'菜单名称', width:160, edit: 'text',templet:function(d){
                        return d.levelsign + d.name;
                        }}
                    ,{field:'url', title:'url', width:160, edit: 'text'}
                    ,{field:'pid', title:'上级', width:160,}
                    ,{field:'ip', title:'ip', width:160, edit: 'text'}
                    ,{field:'created_at', title:'创建时间', width:200, edit: 'text'}
                    ,{field:'updated_at', title:'修改时间', width:200,sort:true, edit: 'text'}
                    ,{field:'username', title:'操作', width:300, edit: 'text',templet:"#id"}
                ]]
                ,id:'demoReload'
                ,method:'POST'
                ,page: {
                    layout: ['count', 'page', 'prev', 'next','groups','last','groups','skip']
                }
                ,response: {
                    statusCode: 0 //重新规定成功的状态码为 200，table 组件默认为 0
                }
                ,parseData: function(res){ //将原始数据解析成 table 组件所规定的数据
                    layer.closeAll()
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.count, //解析数据长度
                        "data": res.data //解析数据列表
                    };
                }
            });

            //监听搜索
            form.on('submit(LAY-user-back-search)', function(data){
                var field = data.field;
                layer.load(0,{shade: [0.1, 'gray'],})

                //执行重载
                table.reload('demoReload', {
                    page: { // 查询时重载分页按钮条数
                        limit:field.limits
                        ,curr: 1 //设定初始在第 1 页
                    }
                    ,where: {
                        key: { // 条件
                            id: '1',
                            'account': field.account,
                            'mobile': field.mobile,
                            'email': field.email,
                            'limits': field.limits,
                        }
                        ,_token:field._token, // 条件
                    }
                }, 'data');
            });


            //添加菜单
            $('button[data-type="add"]').on('click',function(){
                layer.open({
                    type: 2,
                    title: '添加菜单',
                    shadeClose: true,
                    shade: 0.3,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['600px', '500px'],
                    content: '{{ route('menu.show') }}'
                });
            });


        });
    </script>
@stop
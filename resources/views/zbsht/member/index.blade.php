@extends('zbsht.layouts.root')

@section('title','会员列表')

@section('styleCss')
    <link rel="stylesheet" href="{{ URL::asset('css/laravel-page.css') }}">
@stop

@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form layui-form-pane layui-card-header layuiadmin-card-header-auto">
                    <div class="layui-inline">
                        <label class="layui-form-label">帐号</label>
                        <div class="layui-input-block">
                            <input type="text" name="email" placeholder="请输入" value="{{ old('email' , $param['email'] ?? '') }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">手机</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone" placeholder="请输入" value="{{ old('phone' , $param['phone'] ?? '') }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            {{--<input type="text" name="emails" placeholder="请输入" value="{{ old('emails' , $param['emails'] ?? '') }}" autocomplete="off" class="layui-input">--}}
                            @csrf
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">查询条数</label>
                        <div class="layui-input-block">
                            <select name="limits">
                                <option value="">查询条数</option>
                                <option value="1">1</option>
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
                    <button class="layui-btn layuiadmin-btn-admin" data-type="add" lay-event="add">添加</button>
                </div>

                <table id="LAY-user-back-manage" lay-filter="LAY-user-back-manage">

                </table>

            </div>

            {{--<div class="layui-card-body">--}}
                {{--<div class="layui-collapse ">--}}
                    {{--<div class="layui-row">--}}

                        {{--<table class="layui-table ">--}}
                            {{--<colgroup>--}}
                                {{--<col width="150">--}}
                                {{--<col width="200">--}}
                                {{--<col>--}}
                            {{--</colgroup>--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th>排序</th>--}}
                                {{--<th>ID</th>--}}
                                {{--<th>用户名</th>--}}
                                {{--<th>帐号</th>--}}
                                {{--<th>手机号</th>--}}
                                {{--<th>邮箱</th>--}}
                                {{--<th>推荐人</th>--}}
                                {{--<th>团队人数</th>--}}
                                {{--<th>注册时间</th>--}}
                                {{--<th>操作</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--@foreach($user as $k=>$vv)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $k+1 }}</td>--}}
                                {{--<td>{{ $vv['id'] }}</td>--}}
                                {{--<td>{{ $vv['name'] }}</td>--}}
                                {{--<td>{{ $vv['email'] }}</td>--}}
                                {{--<td>{{ $vv['phone'] }}</td>--}}
                                {{--<td>{{ $vv['email'] }}</td>--}}
                                {{--<td>{{ $vv['pid'] }}</td>--}}
                                {{--<td>{{ $vv['pid'] }}</td>--}}
                                {{--<td>{{ $vv['created_at'] }}</td>--}}
                                {{--<td>编辑</td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}

                            {{--</tbody>--}}
                        {{--</table>--}}
                        {{--{!! $user->appends(Request::except('page'))->render() !!}--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

    <script type="text/html" id="operation">
        <a class="layui-btn layui-btn-xs" lay-event="edit" data-href="@{{ d.id }}">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
@stop


@section('scriptJs')
    <script>
        layui.config({
            base: '/layuiadmin/',
        }).extend({
            index: 'lib/index'
        }).use(['table','laytpl'],function(){
            var $ = layui.$
                ,form = layui.form
                ,table = layui.table
                ,laytpl = layui.laytpl;
            form.render();

            $('button[lay-event=add]').on('click',function(){
                layer.open({
                    type: 2,
                    title: '编辑菜单',
                    shadeClose: true,
                    shade: 0.3,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['600px', '500px'],
                    content: "{{ route('member.create') }}"
                });
            });


           // 操作里的按钮
            table.on('tool()',function(obj){
                let field = obj.data;

                if (obj.event == 'del'){
                    layer.confirm('确定要删除吗？',{
                        btn:['确定','取消']
                    },function(){
                        $.ajax({
                            url: '/zbsht/member/' + field.id,
                            type:'DELETE',
                            success:function(res){
                                if (res.status == 0) {
                                    layer.msg(res.msg,{icon:5,time:1000},function(){});
                                }else{
                                    layer.msg(res.msg,{icon:6,time:1000},function(){
                                        if ($(obj.tr).siblings().length / 2 <= 0){
                                            location.reload();
                                        }else{
                                            obj.del();
                                        }
                                    });
                                }
                            },error:function(r){
                                var parseJSON = r.responseJSON.errors;
                                if (parseJSON){
                                    var html = '';
                                    $.each(parseJSON , function(k,v){
                                        html += v + '<br>';
                                    });
                                    layer.msg(html , {icon:5,time:1000});
                                }else{
                                    layer.msg(r.responseJSON.message ,{icon:5,time:1000},function(){});
                                }
                            }
                        })
                    });
                }else if (obj.event == 'edit'){
                    layer.open({
                        type: 2,
                        title: '编辑菜单',
                        shadeClose: true,
                        shade: 0.3,
                        maxmin: true, //开启最大化最小化按钮
                        area: ['600px', '500px'],
                        content:"/zbsht/member/" + field.id,
                    });
                }
            });

            // 数据表格
            table.render({
                elem: '#LAY-user-back-manage'
                ,url: "{{ route('member.userIndex') }}"
                ,toolbar: true
                ,where:{
                    _token:"{{ csrf_token() }}"
                }
                ,title: '会员列表'
                ,totalRow: true
                ,statrByZero:0
                ,cols: [[
                    {field:'tourPlayerId', title:'排序', width:80, fixed: 'left', unresize: true, sort: true, type:'numbers'}
                    ,{field:'id', title:'ID', width:80, fixed: 'left', unresize: true, sort: true, totalRowText: '合计行'}
                    ,{field:'name', title:'姓名', width:160, edit: 'text'}
                    ,{field:'pid_name', title:'推荐人', width:120,}
                    ,{field:'email', title:'账号', width:180, edit: 'text'}
                    ,{field:'phone', title:'手机号', width:120, edit: 'text'}
                    ,{field:'pid', title:'学车套餐', width:120,}
                    ,{field:'to_examine', title:'审核状态', width:120}
                    ,{field:'created_at', title:'注册时间', width:180,sort:true, edit: 'text'}
                    ,{field:'team_ztui', title:'直推人数', width:100}
                    ,{field:'team_zon', title:'团队人数', width:100}
                    ,{field:'username', title:'操作', templet:"#operation" , unresize: true}
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

                //执行重载
                table.reload('demoReload', {
                    page: { // 查询时重载分页按钮条数
                        limit:field.limits
                        ,curr: 1 //设定初始在第 1 页
                    }
                    ,where: {
                        key: { // 条件
                            id: '1',
                            'email': field.email,
                            'phone': field.phone,
                        }
                        ,_token:field._token, // 条件
                    }
                }, 'data');
            });
        });
    </script>
@stop
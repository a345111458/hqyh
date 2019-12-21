@extends('zbsht.layouts.root')

@section('title','会员列表')

@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form layui-form-pane layui-card-header layuiadmin-card-header-auto">
                    <div class="layui-inline">
                        <input type="text" name="account" placeholder="账号 | mt4号 | 姓名 | 手机号 | email" autocomplete="off" class="layui-input" style="width:256px;">
                    </div>
                    <div class="layui-inline">
                        <select name="money_type">
                            <option value="">提现类型</option>
                            <option value="1">理财收益</option>
                            <option value="4">佣金收益</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <select name="status">
                            <option value="">--提现状态--</option>
                            <option value="1">申请中</option>
                            <option value="2">提现成功</option>
                            <option value="3">提现失败</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <select name="limits">
                            <option value="">查询条数</option>
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                    <br>
                    <div class="layui-inline">
                        <select name="screen">
                            <option value="">--数据合并--</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                            <input type="text" name="bank_opening" placeholder="所属银行" autocomplete="off" class="layui-input">
                    </div>

                    <div class="layui-inline">
                        <input type="text" name="accountwo" placeholder="团队成员：帐号查询" autocomplete="off" class="layui-input">
                        @csrf
                    </div>

                    <div class="layui-inline">
                        <input type="search" class="layui-input" name="add_time" placeholder="开始日" id="demo" value="" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
                    </div>

                    <div class="layui-inline">
                        <input type="search" class="layui-input" name="out_time" placeholder="结束日" id="demo1" value="" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
                    </div>

                    {{--<div class="layui-form-item">--}}
                        <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-admin" lay-submit="" lay-filter="LAY-user-back-search" style="">
                                <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                            </button>
                        </div>
                    {{--</div>--}}
                </div>
            </div>

            <div class="layui-card-body">
                <table id="LAY-user-back-manage" lay-filter="LAY-user-back-manage"></table>
            </div>

        </div>
    </div>

@stop


@section('scriptJs')
    <script>
        layui.config({
            base: '/layuiadmin/',
        }).extend({
            index: 'lib/index'
        }).use(['table','layedit', 'laydate'],function(){
            var $ = layui.$
                ,form = layui.form
                ,table = layui.table
                ,laydate = layui.laydate;
            form.render();

            //日期
            laydate.render({
                elem: '#demo'
            });
            laydate.render({
                elem: '#demo1'
            });

            // 数据表格
            table.render({
                elem: '#LAY-user-back-manage'
                ,url: "{{ route('bnak.senddatalist') }}"
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
                    ,{field:'account', title:'会员帐号', width:90, edit: 'text',templet:function(d){
                        return d.user.account;
                    }}
                    ,{field:'mt4_login', title:'MT4号', width:80, edit: 'text',templet:function(d){
                        return d.user.mt4_login;
                    }}
                    ,{field:'money_type', title:'提现类型', width:90,templet:function(d){
                        if (d.money_type == 1){
                            return '理财收益';
                        }else{
                            return '<b style="color:red;">佣金收益</b>';
                        }
                    }}
                    ,{field:'create_time', title:'申请时间', width:130, edit: 'text'}
                    ,{field:'money', title:'提现金额', width:90,sort:true, edit: 'text'}
                    ,{field:'poundage', title:'手续费', width:75, edit: 'text'}
                    ,{field:'price', title:'到账金额', width:100, edit: 'text'}
                    ,{field:'bank_name', title:'开户名', width:80, edit: 'text'}
                    ,{field:'bank_opening', title:'开户行', width:90, edit: 'text'}
                    ,{field:'u_bank_name', title:'开户行名', width:90, edit: 'text'}
                    ,{field:'bank_address', title:'分行支行', width:130, edit: 'text'}
                    ,{field:'bank_account', title:'银行帐号', width:130, edit: 'text'}
                    ,{field:'card_account', title:'身份证号', width:130, edit: 'text',templet:function(d){
                            return d.user.card_account;
                    }}
                    ,{field:'remark', title:'备注', width:90, edit: 'text'}
                    ,{field:'status', title:'状态', width:90, edit: 'text',templet:function(d){
                        if (d.status == 1){
                            return '申请中';
                        }else if (d.status == 2){
                            return '提现成功';
                        }else if (d.status == 3){
                            return '提现失败原因:'+ d.refuse;
                        }
                    }}
                    ,{field:'username', title:'操作', width:120,fixed:'right', edit: 'text',templet:"#id"}
                ]]
                ,id:'demoReload'
                ,method:'POST'
                ,height:600
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
                        account: field.account,
                        accountwo:field.accountwo,
                        limits: field.limits,
                        add_time:field.add_time,
                        out_time:field.out_time,
                        key: { // 条件
                            'mobile': field.mobile,
                            'bank_opening':field.bank_opening,
                            'email': field.email,
                            'money_type':field.money_type,
                            'status':field.status,

                        }
                        ,_token:field._token, // 条件
                        id: '1',
                    }
                }, 'data');
            });
        });
    </script>
@stop
@extends('zbsht.layouts.root')

@section('title','添加菜单')


@section('content')

    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        @if(!isset($user))
        <div class="layui-form-item">
            <label class="layui-form-label">推荐人</label>
            <div class="layui-input-block">
                <input type="text" name="pid" placeholder="推荐人为：帐号" value="{{ old('email' , $user['email'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>
        @endif

        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                @if(isset($user))
                <input type="hidden" name="id" value="{{ old('id' , $user['id'] ?? '') }}" class="layui-input">
                @endif
                <input type="text" name="name" placeholder="请输入 姓名" value="{{ old('name' , $user['name'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">帐号</label>
            <div class="layui-input-block">
                <input type="text" name="email" placeholder="请输入 帐号 | email" value="{{ old('email' , $user['email'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" placeholder="@if(isset($user))可为空@else请输入 密码@endif" value="" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" name="phone" placeholder="请输入 手机号" value="{{ old('phone' , $user['phone'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        @if(isset($user))
        <div class="layui-form-item">
            <label class="layui-form-label">审核状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="to_examine" value="0" title="未审核"
                           @if($user['is_to_examine'] == 0) checked @endif >
                    <input type="radio" name="to_examine" value="1" title="已审核"
                           @if($user['is_to_examine'] == 1) checked @endif >
                    <input type="radio" name="to_examine" value="2" title="锁定用户"
                           @if($user['is_to_examine'] == 2) checked @endif >
                </div>
        </div>
        @endif

        <div class="layui-form-item">
            <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-reg-submit">添 加</button>
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
        }).use(['table','layer'],function(){
            var $ = layui.$
                ,form = layui.form
                ,layer = layui.layer
                ,table = layui.table;
            form.render();

            form.on('submit(LAY-user-reg-submit)',function(data){
                var field = data.field;

                $.ajax({
                    @if(isset($user))
                        url: "/zbsht/member/" + field.id ,
                        type:"PATCH",
                    @else
                        url: "{{ route('member.store') }}",
                        type:"POST",
                    @endif
                    data:field,
                    success:function(res){
                        if (res.status == 0) {
                            layer.msg(res.msg,{icon:5,time:1000},function(){});
                        }else{
                            layer.msg(res.msg,{icon:6,time:1500},function(){
                                parent.layer.closeAll();
                                parent.location.reload();
                            });
                        }
                    },
                    error:function(e){
                        var parseJSON = e.responseJSON.errors;
                        if (parseJSON){
                            var html = '';
                            $.each(parseJSON ,function(k , v){
                                html += v +'<br>';
                            });
                            layer.msg(html ,{icon:5,time:1000},function(){});
                        }else{
                            layer.msg(e.responseJSON.message ,{icon:5,time:1000},function(){});
                        }
                    }
                });

            });


            //监听搜索
            // form.on('submit(LAY-user-back-search)', function(data){
            //     var field = data.field;
            //
            //     //执行重载
            //     table.reload('demoReload', {
            //         page: { // 查询时重载分页按钮条数
            //             limit:field.limits
            //             ,curr: 1 //设定初始在第 1 页
            //         }
            //         ,where: {
            //             key: { // 条件
            //                 id: '1',
            //                 'account': field.account,
            //                 'mobile': field.mobile,
            //                 'email': field.email,
            //                 'limits': field.limits,
            //             }
            //             ,_token:field._token, // 条件
            //         }
            //     }, 'data');
            // });
        });
    </script>
@stop


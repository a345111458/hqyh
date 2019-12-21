@extends('zbsht.layouts.root')

@section('title','添加菜单')


@section('content')

    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <div class="layui-form-item">
            <label class="layadmin-user-login-icon layui-icon layui-icon-cellphone" for="LAY-user-login-cellphone"></label>
            <input type="text" name="name" id="LAY-user-login-cellphone" placeholder="菜单名" class="layui-input">
        </div>

        <div class="layui-form-item">
            <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
            <input type="text" name="url" id="LAY-user-login-password" lay-verify="pass" placeholder="URL" class="layui-input">
        </div>
        <div class="layui-card layui-form" lay-filter="component-form-element">
            <div class="layui-card-body layui-row layui-col-space10">
                <div class="layui-col-md6">
                    <select name="pid" lay-verify="">
                        <option value="0_0">--顶级菜单--</option>
                        @foreach($menus as $v)
                            <option value={{ $v['id'] }}_{{ $v['pid'] }}>{{ levelhtml($v['levelsign']) }}{{ $v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">是否显示</label>
            <div class="layui-input-block">
                <input type="checkbox" name="is_show" lay-skin="switch" lay-text="ON|OFF">
            </div>
        </div>

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


            //
            form.on('submit(LAY-user-reg-submit)',function(data){
                var field = data.field;

                $.ajax({
                    url: "{{ route('menu.addMenu') }}",
                    data:field,
                    type:"POST",
                    success:function(res){
                        if (res.status == 0) {
                            layer.msg(res.msg,{icon:5},function(){});
                        }else{
                            layer.msg(res.msg,{icon:6},function(){
                                parent.layer.closeAll();
                                parent.location.reload();
                            });
                        }
                    },
                    error:function(e){
                        var parseJSON = e.responseJSON.errors;
                        var html = '';
                        $.each(parseJSON ,function(k , v){
                            html += v +'<br>';
                        });
                        layer.msg(html ,{icon:5},function(){

                        });
                    }
                },'json');

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


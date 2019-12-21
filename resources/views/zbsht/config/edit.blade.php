@extends('zbsht.layouts.root')

@section('title','添加配置')


@section('content')

    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">字段名</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="字段名:key" value="{{ old('name' , $config['name'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">字段值</label>
            <div class="layui-input-block">
                @if(isset($config))
                <input type="hidden" name="id" value="{{ old('id' , $config['id'] ?? '') }}" class="layui-input">
                @endif
                <input type="text" name="value" placeholder="字段值:value" value="{{ old('value' , $config['value'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">字段类型</label>
            <div class="layui-input-block">
                <input type="text" name="inc_type" placeholder="字段类型：inc_type" value="{{ old('inc_type' , $config['inc_type'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <input type="text" name="describe" placeholder="描述" value="{{ old('describe', $config['describe'] ?? '') }}" autocomplete="off" class="layui-input">
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
                    @if(isset($config))
                        url: "/zbsht/config/" + field.id ,
                        type:"PATCH",
                    @else
                        url: "{{ route('config.store') }}",
                        type:"POST",
                    @endif
                    data:field,
                    success:function(res){
                        if (res.status == 0) {
                            layer.msg(res.msg,{icon:5,time:1000},function(){});
                        }else{
                            layer.msg(res.msg,{icon:6,time:1000},function(){
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


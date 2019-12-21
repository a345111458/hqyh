@extends('zbsht.layouts.root')

@section('title','提成配置')


@section('content')

    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">提成等级</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="提成等级" value="{{ old('name' , $config['name'] ?? '') }}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">奖金</label>
            <div class="layui-input-block">
                @if(isset($config))
                <input type="hidden" name="id" value="{{ old('id' , $config['id'] ?? '') }}" class="layui-input">
                @endif
                <input type="text" name="price" placeholder="奖金" value="{{ old('price' , $config['price'] ?? '') }}" autocomplete="off" class="layui-input">
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
                        url: "/zbsht/bonus/" + field.id ,
                        type:"PATCH",
                    @else
                        url: "{{ route('bonus.store') }}",
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
        });
    </script>
@stop


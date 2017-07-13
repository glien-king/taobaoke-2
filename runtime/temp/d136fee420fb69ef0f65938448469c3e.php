<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\wamp\www\taobaoke\public/../application/admin\view\cate\index.html";i:1497680843;s:63:"D:\wamp\www\taobaoke\public/../application/admin\view\base.html";i:1497357983;}*/ ?>
<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="Bookmark" href="__STATIC__/admin/favicon.ico">
    <link rel="Shortcut Icon" href="__STATIC__/admin/favicon.ico"/>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__STATIC__/admin/lib/html5shiv.js"></script>
    <script type="text/javascript" src="__STATIC__/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/static/h-ui/css/H-ui.min.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/static/h-ui.admin/css/H-ui.admin.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/ui/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/lib/x0popup/x0popup.min.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/lib/Hui-iconfont/1.0.8/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/fonts/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/static/h-ui.admin/skin/default/skin.css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/static/h-ui.admin/css/style.css"/>


    <!--[if IE 6]>
    <script type="text/javascript" src="__STATIC__/admin/lib/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <!--/meta 作为公共模版分离出去-->
    
<title>分类管理</title>
<style>
    .mt-top{
        margin-top: 0px;
    }
    .layui-table td,.layui-table th{
        text-align: center;
    }
    .iconfont{
        font-size: 30px;
        -webkit-transition: font-size 0.25s ease-out 0s;
        -moz-transition: font-size 0.25s ease-out 0s;
        transition: font-size 0.25s ease-out 0s;
    }
    .iconfont:hover{
        cursor: pointer;
        font-size: 50px;
    }
</style>
<script type="text/javascript">
    var add_url ='<?php echo url("cate/add"); ?>';
    var update_url ='<?php echo url("cate/update"); ?>';
    var cate_status = '<?php echo url("cate/cateShow"); ?>';
</script>

</head>
<body>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 分类管理 <span class="c-gray en">&gt;</span> 分类列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="cl pd-5 bg-1 bk-gray mt-20 mt-top"> <span class="l"><a id="data-del" href="javascript:;" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" onclick="category_set(this,'添加分类',add_url,0)" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加分类</a></span> <span class="r" style="line-height: 38px;">共有数据：<strong><?php echo $pageInfo['total']; ?></strong> 条</span> </div>
    <div class="mt-20">

        <table class="layui-table layui-form">
            <colgroup>
                <col width="5%">
                <col width="5%">
                <col width="15%">
                <col width="25%">
                <col width="10%">
                <col width="10%">
                <col width="15%">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th><input class="delete_all" name="delete_all" type="checkbox" value="" lay-filter="delete_all" lay-skin="primary"></th>
                <th>ID</th>
                <th>分类名称</th>
                <th>标签</th>
                <th>排序</th>
                <th>图标</th>
                <th>是否首页展示</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($cateList)): foreach($cateList as $vo): ?>
            <tr>
                <td><input class="delete_sub" name="<?php echo $vo['id']; ?>" type="checkbox" value="<?php echo $vo['id']; ?>" lay-skin="primary" lay-filter="delete_sub"></td>
                <td><?php echo $vo['id']; ?></td>
                <td><?php echo $vo['cate_name']; ?></td>
                <td><?php echo $vo['tags']; ?></td>
                <td><?php echo $vo['sort']; ?></td>
                <td><i class="iconfont <?php echo $vo['icon']; ?>"></i></td>
                <td>
                    <div class="layui-input-block" style="margin-left: 0">
                        <input type="checkbox" name="switch"  value="<?php echo $vo['status']; ?>" <?php if($vo['status'] == '1'): ?>checked<?php endif; ?> lay-skin="switch" lay-text="ON|OFF" lay-filter="switch" disabled/>
                    </div>
                </td>
                <td class="td-manage" data-status="<?php echo $vo['status']; ?>" data-id="<?php echo $vo['id']; ?>">
                    <a class="ad-show" style="text-decoration:none" title="<?php switch($vo['status']): case "1": ?>开启<?php break; default: ?>关闭<?php endswitch; ?>"><i class="Hui-iconfont"><?php switch($vo['status']): case "1": ?>&#xe6de;<?php break; default: ?>&#xe603;<?php endswitch; ?></i></a>
                    <a style="text-decoration:none" class="ml-5 ad-edit" href="javascript:void(0);" onclick="category_set(this,'修改分类',update_url,1)" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
                    <a style="text-decoration:none" class="ml-5 ad-delete" href="javascript:void(0);" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="9" height="50"><h1 style="font-size: 18px; text-align: center">暂时没有数据</h1></td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div id="page" ></div>
        <div id="msg" style="display: none"></div>
    </div>
</div>

<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="__STATIC__/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__STATIC__/ui/layui.js"></script>
<script type="text/javascript" src="__STATIC__/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/admin/lib/x0popup/x0popup.min.js"></script>

<!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript">
    layui.use(['layer','form'], function(){
        var layer = layui.layer;
        var form = layui.form();

        //实现全选与反选 父级checkbox选择事件
        form.on('checkbox(delete_all)', function(data){
            if(data.elem.checked ===true){
                $(":checkbox").prop('checked',true);
                $(":checkbox").siblings('.layui-form-checkbox').addClass('layui-form-checked');
            }else{

                $(":checkbox").prop('checked',false);

                $(":checkbox").siblings('.layui-form-checkbox').removeClass('layui-form-checked');

            }

        });
        //子级checkbox选择事件
        form.on('checkbox(delete_sub)', function(data) {
            if (data.elem.checked === false) {
                $(".delete_all").prop('checked', false);
                $(".delete_all").siblings('.layui-form-checkbox').removeClass('layui-form-checked');
                return;
            } else {
                if ($(".delete_sub:checked:checked").length === $(".delete_sub").length) {
                    $(".delete_all").prop('checked', true);
                    $(".delete_all").siblings('.layui-form-checkbox').addClass('layui-form-checked');

                }

            }
        });

    });

    /*图片-添加修改*/
    function category_set(obj,title,url,type){
        //一般直接写在一个js文件中
        var link ;
        link =url;
        if (parseInt(type) ===1){
            var id = $(obj).parent().attr('data-id');
            link = url+'?id='+id;
        }

        layui.use(['layer'], function(){
            var layer = layui.layer;
            var index = layer.open({
                type: 2,
                title: title,
                content: link,
                end:function () {
                    if($('#msg').text()==='修改成功'){
                        location.reload();
                    }
                }
            });
            layer.full(index);
        });
    }

    $(function () {
        //分类是否在首页展示
        $('.td-manage .ad-show').on('click',function (e) {
            e.preventDefault();
            var _this = $(this);
            var status = _this.parent().attr('data-status');
            var id = _this.parent().attr('data-id');
            var data = {status:status,id:id}
            var title = ((parseInt(status) === 1) ? '关闭':'开启');

            x0p({
                title: '是否要'+title+'该分类在首页显示?',
                icon: 'info',
                animationType: 'fadeIn',
                buttons: [
                    {
                        type: 'cancel',
                        text: '取消',
                    },
                    {
                        type: 'info',
                        text: '确定',
                        showLoading: true
                    }
                ]
            }, function(button) {

                if(button === 'info') {
                    $.ajax({
                        type:'post',
                        url:cate_status,
                        dataType:'json',
                        data:data,
                        //contentType: false,
                        //processData: false,
                        success:function (res) {

                            if (res.status ==='ok'){
                                x0p({
                                    title: '分类信息'+title+'成功',
                                    icon: 'ok',
                                    animationType: 'pop',
                                    buttons: [
                                        {
                                            type: 'ok',
                                            text: '确定'
                                        }
                                    ]
                                }, function(button) {
                                    if(button === 'ok') {
                                        location.reload();
                                    }
                                });

                            }else{
                                x0p('错误提示', res.errorMsg, 'error', false);
                            }

                        },
                        error:function (res) {
                            x0p('错误提示', '网络链接失败，请重新再试', 'error', false);

                        },
                        complete:function (res) {

                        }
                    })

                }
            });
        });
        //分类删除
        $('.ad-delete').on('click',function (e) {
            var _this = $(this);
            layui.use(['layer'], function(){
                var layer = layui.layer;
                layer.confirm('确认要删除吗？',function(index){

                    console.log(_this);
                    var id = _this.parent().attr('data-id');
                    console.log(id);
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo url("cate/delete"); ?>',
                        dataType: 'json',
                        data: {id:id},
                        success: function(res){
                            if(res.status ==='ok'){
                                _this.parents("tr").remove();
                                layer.msg('已删除!',{icon:1,time:1000});

                            }else{
                                layer.msg(res.errorMsg,{icon:1,time:1000});

                            }

                        },
                        error:function(data) {
                            layer.msg('删除失败请重试!',{icon:1,time:1000});
                        },
                    });
                });
            });


        });


        //批量删除
        $('#data-del').on('click',function () {

            var ids =[];
            $('.delete_sub:checked:checked').each(function (index,value) {
                ids.push($(this).val());
            });
            layui.use(['layer'], function(){
                var layer = layui.layer;
                if(ids.length ===0){
                    layer.msg('您啥也没选择')

                }else{
                    layer.confirm('确认要删除吗？',function(index){

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo url("cate/deleteAll"); ?>',
                            dataType: 'json',
                            data: {ids:ids},
                            success: function(res){
                                if(res.status ==='ok'){
                                    //_this.parents("tr").remove();
                                    layer.msg('已删除!',{icon:1,time:1000});
                                    $('.delete_sub:checked:checked').each(function (index,value) {
                                        $(this).parents('tr').remove();
                                    });

                                }else{
                                    layer.msg(res.errorMsg,{icon:1,time:1000});

                                }
                            },
                            error:function(data) {
                                layer.msg('删除失败请重试!',{icon:1,time:1000});
                            },
                        });
                    });

                }

            });

            console.log(JSON.stringify(ids));
        });

    });

</script>
<script type="text/javascript">
    $('.dataTables_info').css('display', 'none');
    layui.use(['laypage'], function(){
        //var layer = layui.layer;
        var  laypage = layui.laypage;
        //分页
        laypage({
            cont: 'page', //分页容器的id
            pages: <?php echo $pageInfo['page']; ?>, //总页数,
            curr:<?php echo $pageInfo['cur']; ?>,
            skin: '#5FB878', //自定义选中色值
            jump: function(obj, first){
                if(!first){
                    window.location = "<?php echo url('cate/index'); ?>?page="+ obj.curr;
                }
            }
        });
    });

</script>


</body>
</html>




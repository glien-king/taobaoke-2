<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"D:\wamp\www\taobaoke/application/admin\view\ad\index.html";i:1499945339;s:53:"D:\wamp\www\taobaoke/application/admin\view\base.html";i:1497357983;}*/ ?>
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
    
<title>广告管理</title>
<script type="text/javascript">
    var add_url ='<?php echo url("ad/add"); ?>';
    var ad_status = '<?php echo url("ad/adShow"); ?>';

</script>

</head>
<body>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 广告管理 <span class="c-gray en">&gt;</span> 广告列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <form id="form-ad" action="<?php echo url('ad/search'); ?>" method="get" enctype="multipart/form-data">
    <div class="text-c"> 日期范围：
        <input type="text" name="start_time" id="start_time" class="input-text Wdate" style="width:150px;">
        -
        <input type="text" name="end_time" id="end_time"  class="input-text Wdate" style="width:150px;">
        <input type="text" name="ad_name" id="" placeholder="广告名称" style="width:250px" class="input-text" autocomplete="off">
        <button id="search-ad" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜广告</button>
    </div>
    </form>
    <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a id="data-del" href="javascript:;" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" onclick="picture_add('添加图片',add_url)" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加图片</a></span> <span class="r" style="line-height: 38px;">共有数据：<strong><?php echo $pageInfo['total']; ?></strong> 条</span> </div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-hover table-sort layui-form">
            <thead>
            <tr class="text-c">
                <th width="40"><input lay-filter="delete_all" class="delete_all" name="" type="checkbox" value="" lay-skin="primary"></th>
                <th width="80">ID</th>
                <th width="100">广告名称</th>
                <th width="100">图片</th>
                <th>链接</th>
                <th width="150">广告位置</th>
                <th width="150">开始结束时间</th>
                <th width="60">发布状态</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!empty($adList)): foreach($adList as $vo): ?>
            <tr class="text-c">
                <td><input name="<?php echo $vo['id']; ?>" lay-filter="delete_sub" class="delete_sub" type="checkbox" value="<?php echo $vo['id']; ?>" lay-skin="primary"></td>
                <td><?php echo $vo['id']; ?></td>
                <td><?php echo $vo['ad_name']; ?></td>
                <td ><img width="210" class="picture-thumb" src="__UPLOADS__/<?php echo $vo['image_url']; ?>" style="max-height: 100px;overflow-y: hidden;width: auto"/></td>
                <td class="text-c"><a class="maincolor" href="<?php echo $vo['link_url']; ?>"><?php echo $vo['ad_name']; ?>链接地址</a></td>
                <td class="text-c"><?php switch($vo['type']): case "1": ?>PC750*300首页轮播位<?php break; case "2": ?>PC190*300侧栏轮播位<?php break; case "3": ?>PC240*300首页轮播位<?php break; case "4": ?>手机端750*300首页轮播位<?php break; endswitch; ?></td>
                <td><?php echo date("Y-m-d H:i",strtotime($vo['start_time'])); ?>----<?php echo date("Y-m-d H:i",strtotime($vo['end_time'])); ?></td>
                <td class="td-status"><span class="label <?php switch($vo['status']): case "1": ?>label-success<?php break; endswitch; ?> radius"><?php switch($vo['status']): case "1": ?>已发布<?php break; default: ?>没发布<?php endswitch; ?></span></td>
                <td class="td-manage" data-status="<?php echo $vo['status']; ?>" data-id="<?php echo $vo['id']; ?>">
                    <a class="ad-show" style="text-decoration:none" title="<?php switch($vo['status']): case "1": ?>下架<?php break; default: ?>发布<?php endswitch; ?>"><i class="Hui-iconfont"><?php switch($vo['status']): case "1": ?>&#xe6de;<?php break; default: ?>&#xe603;<?php endswitch; ?></i></a>
                    <a style="text-decoration:none" class="ml-5 ad-delete" href="javascript:void(0);" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
            </tr>
            <?php endforeach; else: ?>
            <tr class="text-c">
                <td colspan="9" height="50"><h1 style="font-size: 18px;">暂时没有数据</h1></td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div id="page" ></div>
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
    var layer,form,laypage,laydate;
    layui.use(['form','layer','laypage', 'laydate'], function(){
        form = layui.form();
        layer = layui.layer;
        laypage = layui.laypage;
        laydate = layui.laydate;
        form.on('select(test)', function(data){
            console.log(data);
        });
        //实现全选与反选 父级checkbox选择事件
        form.on('checkbox(delete_all)', function(data){
            if(data.elem.checked ===true){
                $(":checkbox").prop('checked',true);
                $(":checkbox").siblings('.layui-form-checkbox').addClass('layui-form-checked');
            }else{

                $(":checkbox").prop('checked',false);

                $(":checkbox").siblings('.layui-form-checkbox').removeClass('layui-form-checked');

            }
//            console.log(data.elem); //得到checkbox原始DOM对象
//            console.log(data.elem.checked); //是否被选中，true或者false
//            console.log(data.value); //复选框value值，也可以通过data.elem.value得到
//            console.log(data.othis); //得到美化后的DOM对象
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


    /*图片-添加*/
    function picture_add(title,url){
        //一般直接写在一个js文件中
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });

        layer.full(index);

    }


    $(function () {

        //广告位上下架发布
        $('.td-manage .ad-show').on('click',function (e) {
            e.preventDefault();
            var _this = $(this);
            var status = _this.parent().attr('data-status');
            var id = _this.parent().attr('data-id');
            var data = {status:status,id:id}
            var title = ((parseInt(status) === 1) ? '下架':'上架');

            x0p({
                title: '是否要'+title+'该广告?',
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
                        url:ad_status,
                        dataType:'json',
                        data:data,
                        //contentType: false,
                        //processData: false,
                        success:function (res) {

                            if (res.status ==='ok'){
                                x0p({
                                    title: '信息'+title+'成功',
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
        //广告位删除
        $('.ad-delete').on('click',function (e) {
            var _this = $(this);
            layer.confirm('确认要删除吗？',function(index){

                console.log(_this);
                var id = _this.parent().attr('data-id');
                console.log(id);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url("ad/delete"); ?>',
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
        //批量删除
        $('#data-del').on('click',function () {

            var ids =[];
            $('.delete_sub:checked:checked').each(function (index,value) {
                ids.push($(this).val());
            });
            if(ids.length ===0){
                layer.msg('您啥也没选择')

            }else{
                layer.confirm('确认要删除吗？',function(index){

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo url("ad/deleteAll"); ?>',
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
            console.log(JSON.stringify(ids));
        });

    });

</script>
<script type="text/javascript">
    layui.use(['laypage', 'laydate'], function(){
        //var layer = layui.layer;
        var  laypage = layui.laypage;
        var laydate = layui.laydate;

        var start = {
            min: laydate.now(-365),
            max: '2019-06-16 23:59:59',
            istime: true,
            format: 'YYYY-MM-DD', //日期格式
            istoday: false ,
            choose: function (datas) {
                end.min = datas; //开始日选好后，重置结束日的最小日期
                end.start = datas //将结束日的初始值设定为开始日
            }
        };

        var end = {
            min: laydate.now(-365),
            max: '2019-06-16 23:59:59',
            istime: true,
            format: 'YYYY-MM-DD', //日期格式
            istoday: false ,
            choose: function (datas) {
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };
        document.getElementById('start_time').onclick = function(){
            start.elem = this
            laydate(start);
        }
        document.getElementById('end_time').onclick = function(){
            end.elem = this
            laydate(end);
        }

        //分页
        laypage({
            cont: 'page', //分页容器的id
            pages: <?php echo $pageInfo['page']; ?>, //总页数,
            curr:<?php echo $pageInfo['cur']; ?>,
            skin: '#5FB878', //自定义选中色值
            jump: function(obj, first){
                if(!first){
                   switch("<?php echo strtolower(request()->action()); ?>"){
                       case 'search':
                          var start_time = "<?php echo isset($search['start_time']) ? $search['start_time'] : ''; ?>";
                          var end_time = "<?php echo isset($search['end_time']) ? $search['end_time'] : ''; ?>";
                          var ad_name = "<?php echo isset($search['ad_name']) ? $search['ad_name'] : ''; ?>";
                           window.location = "<?php echo url('ad/search'); ?>?page="+ obj.curr+"&start_time="+start_time+"&end_time="+end_time+"&ad_name="+ad_name;

                       break;

                       case 'index':
                       window.location = "<?php echo url('ad/index'); ?>?page="+ obj.curr;
                       break;
                   }
                }
            }
        });
    });

</script>


</body>
</html>




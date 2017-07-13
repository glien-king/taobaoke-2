<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"D:\wamp\www\taobaoke\public/../application/admin\view\login\index.html";i:1499692874;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="Bookmark" href="__STATIC__/admin/favicon.ico">
    <link rel="Shortcut Icon" href="__STATIC__/admin/favicon.ico"/>
    <link rel="stylesheet" href="__STATIC__/home/vender/css/reset.css">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__STATIC__/admin/lib/html5shiv.js"></script>
    <script type="text/javascript" src="__STATIC__/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="__STATIC__/ui/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/fonts/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/admin/css/login.css"/>
    <style>
        .layui-layer-hui{
            z-index:9999;
        }
    </style>
    <title>后台登录</title>
</head>
<body>
<div class="container-fluid">
        <div class="wrapper">
                <div class="col-md-4">
                    <form id="login-submit" class="form-horizontal">
                        <span class="heading">管理员登录</span>
                        <div class="form-group">
                            <input type="text" name="user" class="form-control" id="inputUser" autocomplete="off"
                                   placeholder="用户名或电子邮件" required/>
                            <i class="iconfont icon-yonghu"></i>
                        </div>

                        <div class="form-group help">
                            <input type="password" name="password" class="form-control" id="inputPassword"
                                   autocomplete="off" placeholder="密　码" required/>
                            <i class="iconfont icon-mimatubiao"></i>
                            <a data-show="1" class="iconfont icon-visible icon-help"></a>
                        </div>
                        <div class="form-group">
                            <input type="text" name="verify" class="form-control" id="input-verify" autocomplete="off"
                                   placeholder="请输入验证码" required/>
                            <img class="verify-image" src="<?php echo url('login/createVerify'); ?>" title="点击刷新"/>
                            <i class="iconfont icon-mimatubiao"></i>
                        </div>
                        <button type="submit" class="btn btn-default btn-login">登录</button>
                    </form>
                </div>
        </div>

</div>

<script type="text/javascript" src="__STATIC__/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__STATIC__/ui/layui.js"></script>
<script>
    $(function () {
        //验证码刷新
        var verifyUrl="<?php echo url('login/createVerify'); ?>";
        $('.verify-image').click(function () {

            this.src = verifyUrl+"?"+Math.random();

        });
        //密码显示隐藏
        $('.icon-help').click(function () {
            var attr = $(this).attr('data-show');
            if (parseInt(attr) === 1) {
                $(this).attr('data-show', '2');
                $(this).parent().find('input').attr('type', 'text');
                $(this).removeClass('icon-visible').addClass('icon-mimayincang');

            }
            if (parseInt(attr) === 2) {
                $(this).attr('data-show', '1');
                $(this).parent().find('input').attr('type', 'password');
                $(this).removeClass('icon-mimayincang').addClass('icon-visible');

            }

        });
        //表单提交
        $('#login-submit').submit(function () {
            var data = $('#login-submit').serialize();
            $.ajax({
                type:'post',
                url:"<?php echo url('login/login'); ?>",
                dataType: "json",
                data:data,
                beforeSend:function () {

                },
                success:function (res) {
                    if(res.state == 1){
                        var html = '<div id="welcome" class="form-horizontal" style="display: none">'+
                            '<span class="thumb-img">'+
                            '<img src="__STATIC__/admin/images/avtar.png" />' +
                            '</span>'+
                            '<span class="heading">欢迎您，'+res.msg.username+'</span>'+
                            '<h4 style="font-weight: bold;"><span id="countdown">3</span>秒后系统将自动返回<a href="<?php echo url('index/index'); ?>">首页</a></h4>'+
                            '<h3 style="font-weight: bolder">我们已经恭候多时了！</h3>'+
                            '</div>';
                        var wait=3;
                        var timeOut =function () {

                            if(wait==0){
                                window.location.href="<?php echo url('index/index'); ?>";

                            }else{
                                setTimeout(function(){
                                    wait--;
                                    $('#countdown').text(wait);
                                    timeOut();
                                },1000)
                            }
                        };


                        $('.wrapper #login-submit').fadeOut(800,function () {
                            $(this).remove();
                            $('.wrapper .col-md-4').append(html);
                            $('.wrapper #welcome').fadeIn(1500,function () {
                                timeOut();
                            });
                        });

                    }
                    if(res.state != 1){
                        layui.use('layer', function(){
                            var layer = layui.layer;

                            layer.msg(res.msg);
                        });

                    }
                    if (res.state == 3) {
                        $('.verify-image').attr('src',verifyUrl+"?"+Math.random());
                    }
                },
                error:function (res) {
                    x0p('数据连接失败，请重新刷新', null, 'error', false);
                }
            });
            return false;

        });


    });
</script>

</body>
</html>
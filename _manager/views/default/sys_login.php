<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo setting('manager_name');  ?></title>
	<base href="<?php echo base_url().'templates/manager/'; ?>" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/material-dashboard.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/font.css" rel='stylesheet' type='text/css'>
    <!--   Core JS Files   -->
    <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/material.min.js" type="text/javascript"></script>
    <script src="js/arrive.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <!-- Material Dashboard javascript methods -->

    <script src="js/material-dashboard.js"></script>
    
</head>

<body>
    <div class="wrapper">
			<div class="content" style="padding: 100px 0;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                    			<?php echo form_open('login/submit'); ?>
                                <div class="card card-login">
                                    <div class="card-header text-center" data-background-color="purple">
                                        <h4 class="card-title">后台登录</h4>
                                    </div>                                    <div class="card-content">
                                        <div class="input-group">
                                            <b style="color:red"><?php echo $this->session->flashdata('error'); ?></b>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">face</i>
                                            </span>
                                            <div class="form-group label-floating is-empty">
                                                <label class="control-label">用户名</label>
                                                <input type="text" name="username" autocomplete="off" class="form-control">
                                            <span class="material-input"></span></div>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                            <div class="form-group label-floating is-empty">
                                                <label class="control-label">密码</label>
                                                <input type="password" name="password" class="form-control">
                                            <span class="material-input"></span></div>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">fingerprint</i>
                                            </span>
                                            <div class="form-group label-floating is-empty">
                                                <label class="control-label">验证码</label>
                                                <input type="captcha" name="captcha" class="form-control">
                                                <img class="captcha" src="/<?php echo $this->config->item('index_page') ?>/login/captcha" width="80" height="30" alt="">
                                            <span class="material-input"></span></div>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-primary btn-simple btn-wd btn-lg">登录</button>
                                        <p> </p>
                                    </div>
                                </div>
							<?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright pull-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <span>管理面板</span>
                    </p>
                </div>
            </footer>
    </div>
</body>

</html>



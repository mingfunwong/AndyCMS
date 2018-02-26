<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo setting('manager_name'); ?></title>
	<base href="<?php echo base_url().'templates/manager/'; ?>" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/material-dashboard.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/font.css" rel='stylesheet' type='text/css'>
    <link href="css/selectize.bootstrap3.css" rel='stylesheet' type='text/css'>
    <link href="js/laydate/theme/default/laydate.css" rel='stylesheet' type='text/css'>
    <!--   Core JS Files   -->
    <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/material.min.js" type="text/javascript"></script>
    <script src="js/arrive.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/selectize.js"></script>
    <script src="js/laydate/laydate.js"></script>
    <!-- Material Dashboard javascript methods -->

    <script src="js/material-dashboard.js"></script>
    
</head>

<body>
    <div class="wrapper">
        <?php $this->load->view("_side"); ?>

        <div class="main-panel">
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <span class="navbar-brand" > <?php echo @$bread ?> </span>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="/" target="_blank" class="text-center">
                                    <i class="fa fa-eye" aria-hidden="true"></i>　<span>查看网站</span>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle text-center" data-toggle="dropdown">
                                    <i class="fa fa-user" aria-hidden="true"></i>　<span><?php echo $this->_admin->name; ?> <?php echo $this->_admin->username; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo backend_url('password/edit'); ?>">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>　<span>修改密码</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo backend_url('login/quit'); ?>">
                                            <i class="fa fa-sign-out" aria-hidden="true"></i>　<span>退出管理</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid">

                    <?php if($this->uri->rsegment(1) != 'module'): ?>
                    <?php $this->load->view(isset($tpl) && $tpl ? $tpl : 'sys_default'); ?>
                    <?php else: ?>
                    <?php if(!isset($msg)){echo $content;}else{$this->load->view($tpl);} ?>
                    <?php endif; ?>

                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright pull-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <span><?php echo setting('manager_name'); ?></span>
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>

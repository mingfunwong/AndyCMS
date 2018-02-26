<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" lang="zh-cmn-Hans"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" lang="zh-cmn-Hans"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" lang="zh-cmn-Hans"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" lang="zh-cmn-Hans"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="zh-cmn-Hans"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="renderer" content="webkit" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <title><?php echo $title ?></title>
        <meta name="keywords" content="<?php echo $keywords ?>">
        <meta name="description" content="<?php echo $description ?>">
        <base href="<?php echo base_url(); ?>" />
    </head>
    <body>
        <div class="page-container">
        	<<?php $this->load->view("{$theme}/{$tpl}") ?>
        </div>
    </body>
</html>

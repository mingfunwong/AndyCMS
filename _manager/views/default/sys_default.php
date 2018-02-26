<div class="row">
	<div class="col-lg-6 col-md-12">
	    <div class="card">
	        <div class="card-header" data-background-color="orange">
	            <h4 class="title">网站信息</h4>
	        </div>
	        <div class="card-content table-responsive">
	            <table class="table">
	                <tbody>
	                    <tr>
	                        <td><b>网站名称</b></td>
	                        <td><?php echo setting('name'); ?></td>
	                    </tr>
	                    <tr>
	                        <td><b>网站域名</b></td>
	                        <td><?php echo $_SERVER['SERVER_NAME']; ?></td>
	                    </tr>
	                    <tr>
	                        <td><b>上传限制</b></td>
	                        <td><?php echo @ini_get('upload_max_filesize'); ?></td>
	                    </tr>
	                    <tr>
	                        <td><b>当前时区</b></td>
	                        <td><?php echo date_default_timezone_get(); ?></td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>


	<div class="col-lg-6 col-md-12">
	    <div class="card">
	        <div class="card-header" data-background-color="red">
	            <h4 class="title">管理员</h4>
	        </div>
	        <div class="card-content table-responsive">
	            <table class="table">
	                <tbody>
	                    <tr>
	                        <td><b>当前帐号</b></td>
	                        <td><?php echo $this->_admin->username; ?></td>
	                    </tr>
	                    <tr>
	                        <td><b>所属用户组</b></td>
	                        <td><?php echo $this->_admin->name; ?></td>
	                    </tr>
	                    <tr>
	                        <td><b>登录IP</b></td>
	                        <td><?php echo $this->input->ip_address(); ?></td>
	                    </tr>
	                    <tr>
	                        <td><b>操作</b></td>
	                        <td><a href="<?php echo backend_url('password/edit'); ?>">修改密码</a></td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>
	        </div>
	    </div>
</div>

<div class="card">
	<div class="card-header" data-background-color="green">
		<h4 class="title"> 快捷链接 </h4>
	</div>
	<div class="card-content">
		<div class="row">
		    <?php foreach($this->acl->left_menus() as $key => $value) : ?>
		   	 <?php foreach($value['sub'] as $key => $value) : ?>

			    <div class="col-sm-3">
			        <a class="card btn btn-round " href="<?php echo $value['url'] ?>" style="padding: 20px 0;">
			            <i class="fa <?php echo $value['icon'] ?>" aria-hidden="true"></i> <?php echo $value['name'] ?>
			        </a>
			    </div>
		 	   <?php endforeach ?>
		    <?php endforeach ?>
		</div>
	</div>
</div>
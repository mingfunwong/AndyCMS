
<div class="card">
    <?php echo form_open('user/edit/'.$user->uid, array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <div class="row">
                <label class="col-sm-2 label-on-left"> 用户名称 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('username','input','',$user->username); ?>
                        <label class="control-label"> 2-16位用户名称。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('username'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 用户密码 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                    	<input class="form-control" type="password" maxlength="16" name="password" />
                        <label class="control-label"> 6-16位用户密码，不修改请留空。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('password'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 重复用户密码 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                    	<input class="form-control" type="password" maxlength="16" name="confirm_password" />
                        <label class="control-label"> 6-16位用户密码，不修改请留空。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('confirm_password'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 备注 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                    	<?php $this->form->show('memo','input','',$user->memo); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small> <?php echo form_error('memo'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 用户组 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                    	<?php $this->form->show('role','select',$roles,$user->role); ?>
                        <label class="control-label"> 设置用户组。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('role'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 帐号状态 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                    	<?php $this->form->show('status','select',array(1 => '正常', 2 => '冻结'),$user->status); ?>
                        <label class="control-label"> 当用户状态设为冻结用户将不可登录。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('status'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary"> 保存 </button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
</div>


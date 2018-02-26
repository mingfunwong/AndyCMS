
<div class="card">
    <?php echo form_open('password/edit', array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <div class="row">
                <label class="col-sm-2 label-on-left"> 旧密码 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="password" class="form-control" name="old_pass">
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('old_pass'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 新密码 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="password" class="form-control" name="new_pass">
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('new_pass'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 确认新密码  </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="password" class="form-control" name="new_pass_confirm">
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('new_pass_confirm'); ?></small>
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

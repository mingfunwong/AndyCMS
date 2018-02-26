
<div class="card">
    <?php echo form_open('role/edit/'.$role->id, array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <div class="row">
                <label class="col-sm-2 label-on-left"> 用户组名称 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('name','input','',$role->name); ?>
                        <label class="control-label"> 2-20位用户组标识。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('name'); ?></small>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 label-on-left"> 允许的权限 </label>
                <div class="col-sm-4">
                    <div class="form-group">


						<?php
						 $role->rights = explode(',',$role->rights);
						 foreach($rights as $key=>$v): ?>
							<div class="checkbox"><label><input name="right[]" type="checkbox" value="<?php echo $key; ?>" <?php echo in_array($key,$role->rights) ? 'checked="checked"' : ''; ?> /><?php echo $v; ?></label></div>
						<?php endforeach; ?>
						<hr>
						<?php 
						 $role->models = explode(',',$role->models);
						foreach($models as $key=>$v): ?>
							<div class="checkbox"><label><input name="model[]" type="checkbox" value="<?php echo $key; ?>" <?php echo in_array($key,$role->models) ? 'checked="checked"' : ''; ?> /><?php echo $v; ?></label></div>
						<?php endforeach; ?>
                    </div>
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


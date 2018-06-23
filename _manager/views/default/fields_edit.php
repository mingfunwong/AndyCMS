
<div class="card">
    <?php echo form_open('model/edit_field/'.$model->name.'/'.$field->name, array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <div class="row">
                <label class="col-sm-2 label-on-left"> 字段标识 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('name','input','',$field->name); ?>
                        <label class="control-label"> 字段名称，2-20位字母。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('name'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 字段名称 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('description','input','',$field->description); ?>
                        <label class="control-label"> 有意义的名称，最大40个字符。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('description'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 字段类型 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('type','select',array_merge(setting('fieldtypes'),setting('extra_fieldtypes')),$field->type); ?>
                        <label class="control-label"> 选择一个适当的字段类型。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('type'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 数据源 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('values','input','',$field->values); ?>
                        <label class="control-label"> 可以为某些字段类型提供数据源或者默认值。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small> <?php echo form_error('values'); ?></small>
                </div>
            </div>
            
            <div class="row">
                <label class="col-sm-2 label-on-left"> 验证规则 </label>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php $this->form->show('rules','checkbox',array('required'=>'必填'),$field->rules); ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <small> <?php echo form_error('rules'); ?></small>
                </div>
            </div>
            
            <div class="row">
                <label class="col-sm-2 label-on-left"> 规则说明 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('ruledescription','input','',$field->ruledescription); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small> <?php echo form_error('ruledescription'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 管理选项 </label>
                <div class="col-sm-4">
                    <div class="form-group">
				    	<?php $this->form->show('searchable','checkbox','是否加入搜索',$field->searchable);?>
						<?php $this->form->show('listable','checkbox','是否列表显示',$field->listable); ?>
				        <?php $this->form->show('editable','checkbox','是否允许编辑',$field->editable); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 显示顺序 </label>
                <div class="col-sm-4">
                    <div class="form-group">
				    	<?php $this->form->show('order','input','',$field->order); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small> <?php echo form_error('order'); ?></small>
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

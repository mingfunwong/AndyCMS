<script src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/config.js"></script>
<script type="text/javascript">
    CKEDITOR.editorConfig = function( config ) {
        config.language = 'zh-cn';
        config.image_previewText = ' ';
        config.filebrowserImageUploadUrl = "<?php echo backend_url('attachment/save'); ?>?";
        config.imageUploadURL = "<?php echo backend_url('attachment/save'); ?>?field=file&responseType=json";
        config.removePlugins = 'elementspath';
        config.resize_enabled = false;
        config.extraPlugins = 'simage';
        config.allowedContent = true;
        config.dataParser = function(data) {return data.url};

    };
</script>

<div class="card">
    <?php echo form_open_multipart('content/save?model='.$model['name'].'&id='.(isset($content['id']) ? $content['id'] : ''), array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <?php foreach( $model['fields'] as $v) :  ?>
            <?php if($v['editable']): ?>
            <div class="row">
                <label class="col-sm-2 label-on-left"> <?php echo $v['description'];?> </label>
                <div class="col-sm-8">
                        <div class="form-group">
                            <?php $this->field_behavior->on_form($v , isset($content[$v['name']]) ? $content[$v['name']] : '', TRUE); ?>
                        </div>
                </div>
                <div class="col-sm-2">
                    <small><?php echo $v['rules'] == 'required' ? "*" : "";?> <?php echo form_error($v['name']); ?> </small>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php $this->plugin_manager->trigger('rendered', $content); ?>

            <div class="row">
                <label class="col-sm-2"></label>
                <div class="col-sm-10">
                    <?php $this->form->show_hidden('parentid', $parentid ,true); ?>
                    <button type="submit" class="btn btn-primary"> 保存 </button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
</div>


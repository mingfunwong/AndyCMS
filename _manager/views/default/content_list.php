<style>
    #excelUpdateForm, #excelUpdateForm input {    position: absolute;
    opacity: 0;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
}
</style>

<div class="card">
    <div class="card-header" data-background-color="blue">
        <h4 class="title"> <?php echo $bread ?> 

            <?php 
                $a_link = ($model['level']) ? backend_url('content/form','model='.$model['name']."&parentid=".$this->input->get("parentid")) : backend_url('content/form','model='.$model['name']);
            ?>

            <a href="<?php echo $a_link ?>" class="btn btn-warning btn-xs">
                <i class="material-icons">add</i>
                <span>增加</span>
            </a>

            <a href="javascript:void(0)" onclick="multi_delete();" class="btn btn-danger btn-xs">
                <i class="material-icons">close</i>
                <span>批量删除</span>
            </a>


        <?php if($model['searchable']) : ?>
            <a href="javascript:void(0)" onclick="$('#content_search_form').slideToggle('slow');" class="btn btn-primary btn-xs">
                <i class="fa fa-search"></i>
                <span>筛选</span>
            </a>
        <?php endif; ?>

        <?php if ($model['import']) : ?>
            <a href="javascript:void(0)" id="excelUpdate" class="btn btn-success btn-xs">
                <i class="fa fa-cloud-upload"></i>
                <span>导入</span>
                <form method="post" enctype="multipart/form-data" action="<?php echo backend_url('excel/upload','model='.$model['name']); ?>" id="excelUpdateForm">
                    <input type="file" name="file" accept="application/vnd.ms-excel" onchange="$('#excelUpdateForm').submit()">
                </form>
            </a>
        <?php endif ?>

        <?php if ($model['export']) : ?>
            <a href="<?php echo backend_url('excel/download','model='.$model['name']); ?>" class="btn btn-success btn-xs">
                <i class="fa fa-cloud-download"></i>
                <span>导出</span>
            </a>
        <?php endif ?>
        
        <?php $this->plugin_manager->trigger('buttons'); ?>

        </h4>
    </div>
    <div class="card-content table-responsive">
            <div id="content_search_form" style="display: none">
                <?php echo form_open('content/view?model='.$model['name'], array("class" => 'form-horizontal')); ?>
                    <div class="row">
                        <?php foreach($model['searchable'] as $v): ?>
                        <div class="row">
                            <label class="col-sm-2 label-on-left"> <?php echo $model['fields'][$v]['description']; ?> </label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php $this->field_behavior->on_search($model['fields'][$v],(isset($provider['where'][$model['fields'][$v]['name']]) ? $provider['where'][$model['fields'][$v]['name']] : '' )); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <div class="row">
                            <label class="col-sm-2"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"> 搜索 </button>
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>

        <table class="table table-hover table-condensed">
            <thead class="text-warning">
                <tr>
                    <th width="50">
                        <div class="checkbox"><label><input type="checkbox" onclick="selectAll('id[]', this);" /></label></div>
                    </th>
                    <th>序号</th>
                    <th>创建时间</th>
                    <?php foreach($model['listable'] as $v): ?>
                    <th><?php echo $model['fields'][$v]['description']; ?></th>
                    <?php endforeach; ?>
                    <th>操作选项</th>
                </tr>
            </thead>
            <tbody>

        <?php echo form_open('content/del?model='.$model['name'], array('id' => 'content_list_form')); ?>
                <?php foreach($provider['list'] as $v) : ?>
                    <tr data-id="<?php echo $v->id; ?>">
                        <td>
                            <div class="checkbox"><label><input name="id[]" type="checkbox" value="<?php echo $v->id; ?>" /></label></div>
                        </td>
                        <td><?php echo $v->id; ?></td>
                        <td><?php echo date('Y-m-d H:i', $v->create_time); ?></td>
                        <?php foreach($model['listable'] as $vt): ?>
                        <td>
                        <?php echo $this->field_behavior->on_list($model['fields'][$vt],$v); ?>
                        </td>
                     <?php endforeach; ?>
                        <td>

                            <?php if ($model['level']) : ?>
                            <a class="btn btn-info btn-simple btn-xs" href="<?php echo backend_url('content/view','model='.$model['name'].'&parentid='.$v->id); ?>">
                                <i class="fa fa-th-list"></i>
                                <span>子分类</span>
                            </a>
                            <?php endif ?>

                            <a class="btn btn-primary btn-simple btn-xs" href="<?php echo backend_url('content/form/','model='.$model['name'].'&id='.$v->id); ?>">
                                <i class="material-icons">edit</i>
                                <span>编辑</span>
                            </a>
                            <a class="btn btn-danger btn-simple btn-xs confirm_delete" href="<?php echo backend_url('content/del','model='.$model['name'].'&id='.$v->id); ?>">
                                <i class="material-icons">close</i>
                                <span>删除</span>
                            </a>
                            <?php $this->plugin_manager->trigger('row_buttons', $v); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
        <?php echo form_close(); ?>

            </tbody>
        </table>
    </div>
</div>


<div class="pages_bar pagination"><?php echo $provider['pagination']; ?></div>
<script language="javascript">
	var confirm_str = '是否要删除所选信息？';
	$('a.confirm_delete').click(function(){
		return confirm(confirm_str);	
	});
	function multi_delete()
	{
		if($(":checkbox[name='id[]']:checked").length  <= 0)
		{
				alert('请先选择要删除的信息!');
				return false;
		}
		else
		{
			if(confirm(confirm_str))
			{
				$('#content_list_form').submit();
			}
			else
			{
				return false;	
			}
		}
	}
    function selectAll(nameVal, obj)
    {
        $(":checkbox[name='"+nameVal+"']").prop("checked", $(obj).prop('checked'));
    }

</script>

<?php $this->plugin_manager->trigger('listed', $provider['list']); ?>

<div class="card">
    <div class="card-header" data-background-color="blue">
        <h4 class="title"> <?php echo $bread ?> 
            <a href="<?php echo backend_url($this->uri->rsegment(1).'/add_field/'.$model->id); ?>" class="btn btn-warning btn-xs">
                <i class="material-icons">add</i>
                <span>增加</span>
            </a>
        </h4>
    </div>
    <div class="card-content table-responsive">
        <table class="table table-hover table-condensed">
            <thead class="text-warning">
                <tr>
    				<th>显示顺序</th>
    				<th>字段标识</th>
                    <th>字段名称</th>
                    <th>字段类型</th>
                    <th>管理选项</th>
                </tr>
            </thead>
            <tbody>

            <?php $fieldtypes = array_merge(setting('fieldtypes'),setting('extra_fieldtypes')); ?>
            <?php foreach($list as $v) : ?>
            	<tr>
                	<td><?php echo $v->order; ?></td>
                    <td><?php echo $v->name; ?></td>
                    <td><?php echo $v->description; ?></td>
                    <td><?php echo isset($fieldtypes[$v->type]) ? $fieldtypes[$v->type] : '未知';?></td>
                    <td>
                    	<a class="btn btn-primary btn-simple btn-xs" href="<?php echo backend_url($this->uri->rsegment(1).'/edit_field/'.$v->id); ?>">
                            <i class="material-icons">edit</i>
                            <span>编辑</span>
                        </a>
                        <a class="btn btn-danger btn-simple btn-xs confirm_delete" href="<?php echo backend_url($this->uri->rsegment(1).'/del_field/'.$v->id); ?>">
                            <i class="material-icons">close</i>
                            <span>删除</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<script language="javascript">
	$('a.confirm_delete').click(function(){
		return confirm('是否要删除所选字段？');	
	});
</script>
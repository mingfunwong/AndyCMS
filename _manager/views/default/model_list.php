<div class="card">
    <div class="card-header" data-background-color="blue">
        <h4 class="title"> <?php echo $bread ?> 
            <a href="<?php echo backend_url('model/add'); ?>" class="btn btn-warning btn-xs">
                <i class="material-icons">add</i>
                <span>增加</span>
            </a>
        </h4>
    </div>
    <div class="card-content table-responsive">
        <table class="table table-hover table-condensed">
            <thead class="text-warning">
                <tr>
                    <th> 显示顺序 </th>
                    <th> 内容模型标识 </th>
                    <th> 内容模型名称 </th>
                    <th> 操作选项 </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($list as $v) : ?>
            	<tr>
                    <td><?php echo $v->order; ?></td>
                    <td><?php echo $v->name; ?></td>
                    <td><i class="fa <?php echo $v->icon; ?>" aria-hidden="true"></i> <?php echo $v->description; ?></td>
                    <td>
                        <a class="btn btn-info btn-simple btn-xs" href="<?php echo backend_url('model/fields/'.$v->name); ?>">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                            <span>字段管理</span>
                        </a>
                    	<a class="btn btn-primary btn-simple btn-xs" href="<?php echo backend_url('model/edit/'.$v->name); ?>">
                            <i class="material-icons">edit</i>
                            <span>编辑</span>
                        </a>
                        <a class="btn btn-danger btn-simple btn-xs confirm_delete" href="<?php echo backend_url('model/del/'.$v->name); ?>">
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
		return confirm('是否要删除所选内容模型？');	
	});
</script>
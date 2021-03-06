<div class="card">
    <div class="card-header" data-background-color="blue">
        <h4 class="title"> <?php echo $bread ?> 
            <a href="<?php echo backend_url('role/add'); ?>" class="btn btn-warning btn-xs">
                <i class="material-icons">add</i>
                <span>增加</span>
            </a>
        </h4>
    </div>
    <div class="card-content table-responsive">
        <table class="table table-hover table-condensed">
            <thead class="text-warning">
            	<tr>
					<th>用户组名称</th>
	                <th>操作选项</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($list as $v) : ?>
            	<tr>
                	<td><?php echo $v->name; ?></td>
                    <td>
                    	<a class="btn btn-primary btn-simple btn-xs" href="<?php echo backend_url('role/edit/'.$v->id); ?>">
                            <i class="material-icons">edit</i>
                            <span>编辑</span>
                        </a>
                        <a class="btn btn-danger btn-simple btn-xs confirm_delete" href="<?php echo backend_url('role/del/'.$v->id); ?>">
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
		return confirm('是否要删除所选用户组？');	
	});
</script>

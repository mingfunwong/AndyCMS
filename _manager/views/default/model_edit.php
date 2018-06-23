
<div class="card">
    <?php echo form_open('model/edit/'.$model->name, array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <div class="row">
                <label class="col-sm-2 label-on-left"> 内容模型标识 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('name','input','',$model->name); ?>
                        <label class="control-label"> 2-20位的仅包含字母数字以及下划线破折号的字符，将用作数据库表名。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('name'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 内容模型名称 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('description','input','',$model->description); ?>
                        <label class="control-label"> 有意义的名称，最大40个字符。 </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <small>* <?php echo form_error('description'); ?></small>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 管理选项 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('import','checkbox','允许导入',$model->import);?>
                        <?php $this->form->show('export','checkbox','允许导出',$model->export); ?>
                        <?php $this->form->show('single','checkbox','显示单页',$model->single); ?>
                        <?php $this->form->show('level','checkbox','模型层级',$model->level); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 菜单图标 </label>
                <div class="col-sm-4">
                    <select type="text" id="icon" name="icon" >
                        <option selected value="<?php echo $model->icon ?>" data-data='<?php echo json_encode(array("name" => $model->icon, "icon" => $model->icon)) ?>'><?php echo $model->icon ?></option>
                        </select>
                    </select>
                </div>
                <div class="col-sm-6">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 label-on-left"> 菜单排序 </label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php $this->form->show('order','input','',$model->order); ?>
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


<script type="text/javascript">
    $('#icon').selectize({
        valueField: 'icon',
        labelField: 'name',
        searchField: 'name',
        create: false,
        render: {
            option: function(item, escape) {
                return '<div><i class="fa ' + escape(item.icon) + '"></i> ' + escape(item.name) + '</div>';
            }
        },
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: 'css/font-awesome.json',
                type: 'GET',
                dataType: 'text',
                cache: true,
                error: function() {
                    callback();
                },
                success: function(res) {
                    res = res.split(",")
                    var icons = [];
                    for (var i in res) {
                        icons.push({name: res[i], icon: res[i]})
                    }
                    callback(icons);
                }
            });
        }
    });
</script>
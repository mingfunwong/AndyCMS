<div class="card">
    <?php echo form_open('model/add_field/'.$model->id, array("class" => 'form-horizontal')); ?>
        <div class="card-header card-header-text" data-background-color="purple">
            <h4 class="title"> <?php echo $bread ?> </h4>
        </div>
        <div class="card-content">

            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 字段标识 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('name','input',''); ?>
                                <label class="control-label"> 字段名称，2-20位字母。 </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small>* <?php echo form_error('name'); ?></small>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 字段名称 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('description','input',''); ?>
                                <label class="control-label"> 有意义的名称，最大40个字符。 </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small>* <?php echo form_error('description'); ?></small>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 字段类型 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('type','select',array_merge(setting('fieldtypes'),setting('extra_fieldtypes'))); ?>
                                <label class="control-label"> 选择一个适当的字段类型。 </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small>* <?php echo form_error('type'); ?></small>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 数据源 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('values','input',''); ?>
                                <label class="control-label"> 可以为某些字段类型提供数据源或者默认值。 </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small> <?php echo form_error('values'); ?></small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 验证规则 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('rules','checkbox',array('required'=>'必填')); ?>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small> <?php echo form_error('rules'); ?></small>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 规则说明 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('ruledescription','input',''); ?>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small> <?php echo form_error('ruledescription'); ?></small>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 管理选项 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('searchable','checkbox','是否加入搜索',1);?>
                                <?php $this->form->show('listable','checkbox','是否列表显示',1); ?>
                                <?php $this->form->show('editable','checkbox','是否允许编辑',1); ?>
                            </div>
                        </div>
                        <div class="col-sm-5">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 label-on-left"> 显示顺序 </label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <?php $this->form->show('order','input','', 1); ?>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <small> <?php echo form_error('order'); ?></small>
                        </div>
                    </div>


                    <div class="row">
                        <label class="col-sm-2"></label>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary"> 保存 </button>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2">字段数据源使用说明</label>
                        <div class="col-sm-10">
	                        <pre>
整形 (INT)
可不用设置，设置后可作为表单的默认值

浮点型 (FLOAT)
可不用设置，设置后可作为表单的默认值

单行文本框 (VARCHAR)
可不用设置，设置后可作为表单的默认值

文本区域 (VARCHAR)
可不用设置，设置后可作为表单的默认值

下拉菜单 (VARCHAR)
必须填写，格式为key=value,使用|作为分割符，如 1=是|0=否，存储的时候存储key

下拉菜单 模型数据 (INT)
必须填写，格式为"分类模型标识|作为显示文本的字段标识"

单选按钮 (VARCHAR)
必须填写，格式为key=value,使用|作为分割符，如 1=是|0=否，存储的时候存储key

单选按钮 模型数据 (INT)
必须填写，格式为"分类模型标识|作为显示文本的字段标识"

复选框 (VARCHAR)
必须填写，格式为key=value,使用|作为分割符，如 1=是|0=否，存储的时候存储key

复选框 模型数据 (VARCHAR)
必须填写，格式为"分类模型标识|作为显示文本的字段标识"

编辑器 (TEXT)
可不用设置，设置后可作为表单的默认值

日期时间 (VARCHAR)
可不用设置
year    年选择器    只提供年列表选择
month   年月选择器   只提供年、月选择
date    日期选择器   可选择：年、月、日。默认值，一般可不填
time    时间选择器   只提供时、分、秒选择
datetime    日期时间选择器 可选择：年、月、日、时、分、秒

文件上传 (VARCHAR)
可不用设置，设置后作为允许上传文件类型，使用|作为分割符，如 jpg|gif|png

图片集 (TEXT)
仅有上传图片功能的编辑器，可不用设置，设置后可作为表单的默认值</pre>
                    	</div>
                    </div>

                </div>
                <div class="col-sm-4">
                    <div class="fast-add">
                        <h3>快速添加</h3>
                        <ul>
                            <li><a href="javascript:;" data-data="name,名称,input,,1,,1,1,1,10">名称</a></li>
                            <li><a href="javascript:;" data-data="type,类型,radio,,,,1,1,1,15">类型</a></li>
                            <li><a href="javascript:;" data-data="intro,简介,textarea,,,,1,1,1,20">简介</a></li>
                            <li><a href="javascript:;" data-data="content,内容,wysiwyg,,,,1,,1,30">内容</a></li>
                            <li><a href="javascript:;" data-data="image,上传图片,file,,,图片尺寸：100x100 px,,,1,40">上传图片</a></li>
                            <li><a href="javascript:;" data-data="images,图片集,images,,,,,,1,50">图片集</a></li>
                            <li><a href="javascript:;" data-data="link,超链接,input,,,,1,1,1,60">超链接</a></li>
                            <li><a href="javascript:;" data-data="class,分类,radio_from_model,,,,1,1,1,80">分类</a></li>
                            <li><a href="javascript:;" data-data="parentid,层级,radio_from_model,,,,,,,90">层级</a></li>
                            <li><a href="javascript:;" data-data="order,排序,int,100,,按数字从小到大排序,1,,1,100">排序</a></li>
                            <li><a href="javascript:;" data-data="keywords,页面关键字,input,,,,,,1,110">页面关键字</a></li>
                            <li><a href="javascript:;" data-data="description,页面描述,input,,,,,,1,120">页面描述</a></li>
                            <li><a href="javascript:;" data-data="click_count,点击次数,int,0,,,1,1,,130">点击次数</a></li>
                            <li><a href="javascript:;" data-data="datetime,添加时间,datetime,datetime,,,1,1,1,140">添加时间</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
</div>

<style>
.fast-add{font-size: 16px;}
.fast-add li{line-height: 2em;}
.fast-add a{padding: 0 10px; display: block;}
</style>


<script>
$(".fast-add a").on("click", function (){
    var data = $(this).data('data').split(",");
    for(var i=0; i<data.length; i++)
    {
        $("form input, form select").eq(i).filter("[type=text]").val(data[i]);
        $("form input, form select").eq(i).filter("select").val(data[i]);
        $("form input, form select").eq(i).filter("[type=checkbox]").prop("checked", (data[i]));
    }

});
</script>


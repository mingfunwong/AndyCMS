/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

// CKEDITOR.editorConfig = function( config ) {
// 	// Define changes to default configuration here. For example:
// 	config.language = 'zh-cn';
// 	config.image_previewText = ' ';
// 	// config.filebrowserImageUploadUrl= "";
// 	config.removePlugins = 'elementspath';
// 	config.resize_enabled = false;
// };

var ckeditor_setting = {toolbar :
        [
            //  撤销   重做    加粗     斜体，     下划线      穿过线      下标字        上标字
            ['Undo', 'Redo', 'Bold','Italic','Underline','Strike','Subscript','Superscript'],
            // 数字列表          实体列表            减小缩进    增大缩进
            ['NumberedList','BulletedList','-','Outdent','Indent'],
            //左对 齐             居中对齐          右对齐          两端对齐
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            //超链接  取消超链接 锚点
            ['Link','Unlink','Anchor'],
            //图片    flash   视频  表格       水平线         特殊字符        框架
            ['SImage', 'Image', 'Html5video', 'Flash','Table','HorizontalRule','SpecialChar','Iframe'],
            '/',
            // 样式       格式      字体    字体大小  行高
            ['Styles','Format','Font','FontSize', 'lineheight'],
            //文本颜色     背景颜色
            ['TextColor','BGColor'],
            //源代码     全屏               
            ['Source','Maximize']
        ],height:400
}

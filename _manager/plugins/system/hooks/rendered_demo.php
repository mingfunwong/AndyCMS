<script language="javascript">

	// 位置
	togglePosition();
	$("[name=position]").change(togglePosition);
	// 类型
	toggleType();
	$("[name=type]").change(toggleType);

	// 位置
	function togglePosition(){
	    var val = $("[name=position]:checked").parent().text();
	    var $class = $("#chk_class_1").parents("tr");
	    $class.toggle(val == "内页右侧");
	}
	// 类型
	function toggleType(){
	    var val = $("[name=type]:checked").parent().text();
	    var $link = $("[name=link]").parents("tr");
	    var $video = $("[name=video]").parents("tr");
	    $link.toggle(val == "文章");
	    $video.toggle(val == "视频");
	}

</script>
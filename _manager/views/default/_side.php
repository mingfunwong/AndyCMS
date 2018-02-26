<div class="sidebar" data-color="blue">
    <div class="logo">
        <a class="simple-text">
            管理面板
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <?php 
	            $left_menus = $this->acl->left_menus();
	            // 如果内容管理超过6个，那么首页首页就缩起来
	            if (count(from(from($left_menus, 1), 'sub')) > 6) :
		            $value = $left_menus[0]['sub'][0];
	            	unset($left_menus[0]);
            ?>
            <li class="<?php echo $value['active'] ? "active" : "" ?>">
                <a href="<?php echo $value['url'] ?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <p><?php echo $value['name'] ?></p>
                </a>
            </li>
            <?php endif; foreach($left_menus as $key => $value) :  ?>
                    <li>
                        <a data-toggle="collapse" href="#<?php echo $key ?>" class="" aria-expanded="true">
                            <i class="fa <?php echo $value['icon'] ?>" aria-hidden="true"></i>
                            <p> <?php echo $value['name'] ?> <b class="caret"></b> </p>
                        </a>
                        <div class="collapse in" id="<?php echo $key ?>" aria-expanded="true">
                            <ul class="nav">
                            <?php foreach($value['sub'] as $key => $value) : ?>
                            <li class="<?php echo $value['active'] ? "active" : "" ?>">
                                <a href="<?php echo $value['url'] ?>">
                                    <i class="fa <?php echo $value['icon'] ?>" aria-hidden="true"></i>
                                    <p><?php echo $value['name'] ?></p>
                                </a>
                            </li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="sidebar-background" style="background-image: url(img/sidebar-<?php echo date("d") % 4 + 1 ?>.jpg"></div>
</div>
<?php if(!class_exists('raintpl')){exit;}?><ul class="list-unstyled list-menu">
    <?php $counter1=-1; if( isset($list_category_menu) && is_array($list_category_menu) && sizeof($list_category_menu) ) foreach( $list_category_menu as $key1 => $value1 ){ $counter1++; ?>
    <li class="list-item item-cat-parent">
        <label class="item-name" onclick="HomeScript.view.collapse('<?php echo $value1["cat_id"];?>')">
            <i class="fa fa-plus-square-o"></i>
            <?php echo $value1["cat_name"];?> (<?php echo $value1["count_menu"];?>)
        </label>
        <ul class="list-cat-child list-unstyled" data-collapse-id="<?php echo $value1["cat_id"];?>" style="display: none;">
            <?php $counter2=-1; if( isset($value1["list_cat_child"]) && is_array($value1["list_cat_child"]) && sizeof($value1["list_cat_child"]) ) foreach( $value1["list_cat_child"] as $key2 => $value2 ){ $counter2++; ?>
            <li class="list-item item-cat-child">
                <label class="item-name" onclick="HomeScript.view.collapse('<?php echo $value2["cat_id"];?>')">
                    <i class="fa fa-caret-right"></i>
                    <?php echo $value2["cat_name"];?> (<?php echo $value2["count_menu"];?>)
                </label>
                <ul class="list-menu-child list-unstyled" data-collapse-id="<?php echo $value2["cat_id"];?>"  style="display: none;">
                    <?php $counter3=-1; if( isset($value2["list_menu_child"]) && is_array($value2["list_menu_child"]) && sizeof($value2["list_menu_child"]) ) foreach( $value2["list_menu_child"] as $key3 => $value3 ){ $counter3++; ?>
                    <li class="list-item item-menu">
                        <label class="item-name" ondblclick="HomeScript.addMenuToDesk(<?php echo $value3["men_id"];?>)">
                            - <?php echo $value3["men_name"];?>
                        </label>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php $counter2=-1; if( isset($value1["list_menu_child"]) && is_array($value1["list_menu_child"]) && sizeof($value1["list_menu_child"]) ) foreach( $value1["list_menu_child"] as $key2 => $value2 ){ $counter2++; ?>
            <li class="list-item item-menu">
                <label class="item-name" ondblclick="HomeScript.addMenuToDesk(<?php echo $value2["men_id"];?>)">
                    - <?php echo $value2["men_name"];?>
                </label>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
</ul>
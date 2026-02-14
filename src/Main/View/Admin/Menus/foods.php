<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("food_view", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "foods") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "foods") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-mug-hot"></i>
            <p>
                <?php echo ucfirst($translate->translate('Alimentos', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "foods") ? "block" : "none"; ?>;">
            <li class="nav-item">
                <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/foods" class="nav-link  <?php echo ($submenu_active === "foods") ? "active" : ""; ?>">
                    <i class="nav-icon fas fa-utensils"></i>
                    <p><?php echo ucfirst($translate->translate('Todos', $_SESSION['user_lang'])); ?></p>
                </a>
            </li>
            <?php if (in_array("brand_view", $privilege_types)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/brands" class="nav-link  <?php echo ($submenu_active === "brands") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-registered"></i>
                        <p><?php echo ucfirst($translate->translate('Marcas', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php } if (in_array("food_group_view", $privilege_types)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/foodGroups" class="nav-link  <?php echo ($submenu_active === "groups") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p><?php echo ucfirst($translate->translate('Grupo de Alimentos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>            
            <?php } if (in_array("food_table_view", $privilege_types)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/foodTables" class="nav-link  <?php echo ($submenu_active === "tables") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p><?php echo ucfirst($translate->translate('Tabela de Alimentos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>            
            <?php } ?>
        </ul>
    </li>


<?php } ?>
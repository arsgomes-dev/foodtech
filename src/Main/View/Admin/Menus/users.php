<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("user_view", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "users") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "users") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
                <?php echo ucfirst($translate->translate('Usuários', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "users") ? "block" : "none"; ?>;">
         
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/users" class="nav-link  <?php echo ($submenu_active === "users") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p><?php echo ucfirst($translate->translate('Todos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php if (in_array("department_view", $privilege_types)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/departments" class="nav-link  <?php echo ($submenu_active === "departments") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p><?php echo ucfirst($translate->translate('Departamentos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>


<?php } ?>
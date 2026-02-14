<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("customer_view", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "customers") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "customers") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-user"></i>
            <p>
                <?php echo ucfirst($translate->translate('Clientes', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "customers") ? "block" : "none"; ?>;">
            <li class="nav-item">
                <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/customers" class="nav-link  <?php echo ($submenu_active === "customers") ? "active" : ""; ?>">
                    <i class="nav-icon fas fa-user"></i>
                    <p><?php echo ucfirst($translate->translate('Todos', $_SESSION['user_lang'])); ?></p>
                </a>
            </li>
        </ul>
    </li>


<?php } ?>
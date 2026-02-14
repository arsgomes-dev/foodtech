<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("ticket_view", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "tickets") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "tickets") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-ticket"></i>
            <p>
                <?php echo ucfirst($translate->translate('Tickets', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "tickets") ? "block" : "none"; ?>;">
         
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/tickets" class="nav-link  <?php echo ($submenu_active === "tickets") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p><?php echo ucfirst($translate->translate('Todos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php if (in_array("ticket_department_view", $privilege_types)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/tickets/departments" class="nav-link <?php echo ($submenu_active === "ticket_departaments") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p><?php echo ucfirst($translate->translate('Departamentos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>


<?php } ?>
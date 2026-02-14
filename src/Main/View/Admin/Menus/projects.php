<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("project_view", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "projects") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "projects") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-user"></i>
            <p>
                <?php echo ucfirst($translate->translate('Projetos', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "projects") ? "block" : "none"; ?>;">
            <li class="nav-item">
                <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/projects" class="nav-link  <?php echo ($submenu_active === "projects") ? "active" : ""; ?>">
                    <i class="nav-icon fas fa-user"></i>
                    <p><?php echo ucfirst($translate->translate('Todos', $_SESSION['user_lang'])); ?></p>
                </a>
            </li>
        </ul>
    </li>


<?php } ?>
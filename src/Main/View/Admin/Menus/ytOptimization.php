<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("yt_optimization_list", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "users") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "ytOptimization") ? "active" : ""; ?>">
            <i class="nav-icon fa-brands fa-youtube"></i>
            <p>
                <?php echo ucfirst($translate->translate('Optimização de Vídeos', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "ytOptimization") ? "block" : "none"; ?>;">
         
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/ytOptimization" class="nav-link  <?php echo ($submenu_active === "ytOptimization") ? "active" : ""; ?>">
                        <i class="nav-icon fa-brands fa-youtube"></i>
                        <p><?php echo ucfirst($translate->translate('Buscar vídeos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
        </ul>
    </li>


<?php } ?>
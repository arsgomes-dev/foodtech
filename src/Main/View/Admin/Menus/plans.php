<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("access_plans_view", $privilege_types)) {
    ?>
    <li class="nav-item <?php echo ($menu_active === "access_plans") ? "menu-is-opening menu-open" : ""; ?>">
        <a href="#" class="nav-link <?php echo ($menu_active === "access_plans") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-key"></i>
            <p>
                <?php echo ucfirst($translate->translate('Planos de Acesso', $_SESSION['user_lang'])); ?>
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "access_plans") ? "block" : "none"; ?>;">
         
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/accessPlans" class="nav-link  <?php echo ($submenu_active === "access_plans") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p><?php echo ucfirst($translate->translate('Todos', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php if (in_array("access_plans_coupons_view", $privilege_types)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/coupons" class="nav-link <?php echo ($submenu_active === "coupons") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-gifts"></i>
                        <p><?php echo ucfirst($translate->translate('Cupons de desconto', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>


<?php } ?>
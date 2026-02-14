<?php
$privilege_types = $_SESSION['user_type'];
if (in_array("customer_signatures", $privilege_types)) {
    ?>
    <li class="nav-item">
        <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/signatures" class="nav-link <?php echo ($menu_active === "signatures") ? "active" : ""; ?>">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p><?php echo ucfirst($translate->translate('Assinaturas', $_SESSION['user_lang'])); ?></p>
        </a>
    </li>
<?php } ?>
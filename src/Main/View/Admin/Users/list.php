<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\User;

$translate = new Translate();

$config = new McConfig();
$userSearch = new User;
$users = new User;
$privilege_types = $_SESSION['user_type'];
if (in_array("user_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];

    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $userSearch->setName($_POST['description']);
        }
    }
    if (!empty($_POST['cpf'])) {
        if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
            $userSearch->setCpf($_POST['cpf']);
        }
    }
    if (!empty($_POST['email'])) {
        if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
            $userSearch->setEmail($_POST['email']);
        }
    }
    if (!empty($_POST['department'])) {
        if ($_POST['department'] !== "" && $_POST['department'] !== null && $_POST['department'] !== "") {
            $userSearch->setDepartment_id($_POST['department']);
        }
    }
    if (isset($_POST['status'])) {
        if ($_POST['status'] !== "" && $_POST['status'] !== null && $_POST['status'] !== "") {
            if ($_POST['status'] === "1") {
                $userSearch->setStatus(1);
            } else {
                $userSearch->setStatus(0);
            }
        }
    }

    $offset = 0;
    if (isset($page, $limit)) {
        $page = $page;
        $limitConfig = $limit;
    } else {
        $page = 1;
        $limitConfig = 20;
    }
    if ($page == 1) {
        $offset = 0;
    } else {
        $offset = ($limitConfig * $page) - $limitConfig;
    }
    $order_by = "name ASC";
    if ($order == 1) {
        $order_by = "name ASC";
    } else if ($order == 2) {
        $order_by = "name DESC";
    } else if ($order == 3) {
        $order_by = "created_at DESC";
    } else if ($order == 4) {
        $order_by = "created_at ASC";
    }
    $users = $userSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $usersCount = count($users);
    ?>
    <table class="table table-striped table-user-list">
        <thead>
            <tr>
                <th>

                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Login', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($usersCount > 0) {
                $user = new User();
                for ($i = 0; $i < $usersCount; $i++) {
                    $user = $users[$i];
                    $img = "";
                    $profile_img = "/" . $user->getGcid() . "/photo/" . $user->getPhoto();
                    $profile_model = "/model/user_model.png";
                    $img = ($user->getPhoto() !== null) ? $profile_img : $profile_model;
                    $date_hour_session = ($user->getSession_date()) ? date('d-m-Y H:i', strtotime($user->getSession_date())) : "";
                    $date_hour = "";
                    if ($date_hour_session !== "") {
                        $date_hour_temp = explode(" ", $date_hour_session);
                        $date_hour = $translate->translateDate($date_hour_temp[0], $_SESSION['user_lang']) . " às " . $date_hour_temp[1] . "h";
                    }
                    $statusText = "";
                    if ($user->getStatus() === 1) {
                        $statusText = "Ativo";
                    } else if ($user->getStatus() === 2) {
                        $statusText = "Bloqueado";
                    } else if ($user->getStatus() === 0) {
                        $statusText = "Inativo";
                    }
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'>";
                    echo "<ul class='list-inline'>";
                    echo "<li class='list-inline-item'>";
                    echo "<img alt='Avatar' class='img-circle elevation-2' src='" . $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $img . "' style='width: 35px; margin: auto; display: block;'>";
                    echo "</li>";
                    echo "</ul>";
                    echo "</td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $user->getName() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $user->getEmail() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_hour . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>";

                    echo $statusText;
                    echo "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/user/" . $user->getGcid() . "'><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                    //echo "<td style='vertical-align:middle !important'><a href='#' onclick='loadUser(".$user->getId().")'><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhum usuário encontrado!', $_SESSION['user_lang']) . "</b></center>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>


    <?php
} else {
    ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="alert alert-warning alert-dismissible">
                <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                <?php
                echo $translate->translate('Você não tem permissão para visualizar esta página!', $_SESSION['user_lang']);
                ?>
                </font>
            </div>
        </div>
    </div>
    <?php
}
        
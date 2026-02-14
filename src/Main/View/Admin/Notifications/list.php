<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Notification;

$config = new McConfig();
$baseHtml = new BaseHtml();
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();

if (in_array("notification_view", $privilege_types)) {
    $titleMenu = "";
    if (!empty($_POST['title'])) {
        if (isset($_POST['title'])) {
            $titleMenu = $_POST['title'];
        }
    }
    ?>

    <script src="/libs/v1/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="/libs/v1/admin/plugins/tinymce2/tinymce.min.js"></script>
    <script>
        function editText(e) {
            tinymce.init({
                language: 'pt_BR',
                menubar: false,
                mode: "exact",
                elements: e,
                height: 200,
                plugins: [
                    'advlist lists hr',
                    'searchreplace wordcount visualblocks visualchars',
                    'insertdatetime nonbreaking table contextmenu directionality',
                    'emoticons paste textcolor colorpicker textpattern toc'
                ],
                toolbar1: 'undo redo | insert | fontsize | bold italic | alignleft aligncenter alignright alignjustify',
                toolbar2: 'forecolor backcolor | bullist numlist outdent indent | styleselect formatselect fontselect fontsize fontsizeselect"',
                image_advtab: true,
                relative_urls: false
            });
        }
    </script>
    <div class="card card-border-radius">
        <div class="card-header">
            <h3 class="card-title"><?php echo $translate->translate("Notificações", $_SESSION['user_lang']); ?> - <?php echo $titleMenu; ?></h3>
        </div>
        <div class="card-body p-0 list">
            <?php
            $code = $_POST['code'];
            $page = 1;
            $limit = 50;
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
            $notifications = new Notification();
            $notifications->setType($code);
            $notificationAll = new Notification();
            $notificationAll = $notifications->getQuery(limit: $limitConfig, offset: $offset);
            if (count($notificationAll) > 0) {
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <?php if (in_array("notification_edit", $privilege_types)) { ?>
                                <th style="width: 10px"><font notificationEditstyle="vertical-align: inherit;"></font></th>
                            <?php } ?>
                            <th><font style="vertical-align: inherit;"><?php echo $translate->translate('Título', $_SESSION['user_lang']); ?></font></th>
                            <th><font style="vertical-align: inherit;"><center><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></center></font></th>
                    <th><font style="vertical-align: inherit;"><?php echo $translate->translate('Atualização', $_SESSION['user_lang']); ?></font></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($notificationAll); $i++) {
                            $notification = new Notification();
                            $notification = $notificationAll[$i];
                            ?>

                            <tr>             
                                <?php if (in_array("notification_edit", $privilege_types)) { ?>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#modal-notification-edit-<?php echo $notification->getId(); ?>"><i class="fas fa-edit" title="<?php echo $translate->translate('Editar', $_SESSION['user_lang']); ?>"></i></a>
                                        <div class="modal fade" id="modal-notification-edit-<?php echo $notification->getId(); ?>" data-backdrop="static">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content modal-custom">
                                                    <div class="modal-header modal-header-custom" style="border-bottom: 1px solid !important;">
                                                        <h4 class="modal-title modal_title_photo"><?php echo $notification->getTitle_type(); ?></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form role="form" name="form_notification_<?php echo $notification->getId(); ?>" id="form_notification_<?php echo $notification->getId(); ?>" autocomplete="off" style="padding: 20px;">
                                                            <input type="hidden" class="form-control" id="notification"  name="notification" value="<?php echo $notification->getId(); ?>">

                                                            <div class="form-group to_validation">
                                                                <label>
                                                                    <?php echo $translate->translate('Título', $_SESSION['user_lang']); ?>
                                                                </label>
                                                                <input type="text" class="form-control" id="title_<?php echo $notification->getId(); ?>"  name="title" placeholder="<?php echo $translate->translate('Título', $_SESSION['user_lang']); ?>"
                                                                       value="<?php echo $notification->getTitle(); ?>">
                                                                <div id="to_validation_blank_title_<?php echo $notification->getId(); ?>" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>
                                                                    <?php echo $translate->translate('Status', $_SESSION['user_lang']); ?>
                                                                </label>
                                                                <?php
                                                                $checkedNot = "";
                                                                if ($notification->getStatus() == 1) {
                                                                    $checkedNot = "checked";
                                                                }
                                                                ?>
                                                                <br>
                                                                <input data-toggle="switch" data-on-color="success" type="checkbox" <?php echo $checkedNot; ?> class="form-control" name="sts" id="sts__<?php echo $notification->getId(); ?>" value="1" >          
                                                                <div id="validation_sts_<?php echo $notification->getId(); ?>" name="validation_sts_<?php echo $notification->getId(); ?>"></div>
                                                               <div id="to_validation_blank_sts_<?php echo $notification->getId(); ?>" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                            </div>

                                                            <div class="form-group to_validation">
                                                                <label>
                                                                    <?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?>
                                                                </label>
                                                                <textarea class="form-control" id="description_not_<?php echo $notification->getId(); ?>"  name="description_not" placeholder="<?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?>" ><?php echo $notification->getDescription(); ?></textarea>
                                                                <textarea class=" to_validations" style="display: none;" id="description_<?php echo $notification->getId(); ?>"  name="description"><?php echo $notification->getDescription(); ?></textarea>
                                                               <div id="to_validation_blank_description_<?php echo $notification->getId(); ?>" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                            </div>   

                                                        </form>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default btn-register" onclick="notificationEdit(form_notification_<?php echo $notification->getId(); ?>, <?php echo $notification->getId(); ?>);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><?php echo $translate->translate('Fechar', $_SESSION['user_lang']); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                        <script>
                                            $(function () {
                                                editText('description_not_<?php echo $notification->getId(); ?>');
                                            });
                                        </script>
                                    </td>
                                <?php } ?>
                                <td class="mailbox-name"><?php echo $notification->getTitle_type(); ?></td>
                                <td class="mailbox-attachment"><center><?php
                            $checked = "";
                            if ($notification->getStatus() == 1) {
                                $checked = "checked";
                            }
                            ?>
                            <input data-toggle="switch" data-on-color="success" type="checkbox" class="form-control" <?php echo $checked; ?> onchange="changeSts(<?php echo $code . ",'" . $titleMenu . "'," . $notification->getId(); ?>);"  name="notification_<?php echo $notification->getId(); ?>" id="notification_<?php echo $notification->getId(); ?>" value="1" >          
                        </center></td>
                        <td class="mailbox-date">
                            <?php
                            if (!empty($notification->getUpdated_at())) {
                                $date_hour = explode(" ", $notification->getUpdated_at());
                                $date = explode("-", $date_hour[0]);
                                $hour = explode(":", $date_hour[1]);
                                echo $date[2] . "/" . $date[1] . "/" . $date[0] . " " . $translate->translate('às', $_SESSION['user_lang']) . " " . $hour[0] . ":" . $hour[1] . "h";
                            }
                            ?>
                        </td>
                        </tr> 
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
        <script>$('[data-toggle="switch"]').bootstrapSwitch();</script>
        <?php
    } else {
        ?>
        <div class="content-header">
            <div class="container-fluid">
                <div class="alert alert-warning alert-dismissible" style="margin: 10px;">
                    <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                    <?php
                    echo $translate->translate('Nenhuma notificação encontrada', $_SESSION['user_lang']);
                    ?>!
                    </font>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
<?php } else {
    ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="alert alert-warning alert-dismissible">
                <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                <?php
                echo $translate->translate('Você não tem permissão para visualizar esta página!');
                ?>
                </font></div></div></div><?php } ?>
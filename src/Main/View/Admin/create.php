<?php

use Microfw\Src\Main\Common\Helpers\Admin\Create\CreateClass;
//tabela de projetos
$nameClass = 'Project';
$tableDb = 'project';
$likeDb = false;
$idPrimary = 'id';
$gcid = true;
$fields = 'int->id, int->customer_id, int->budget_id, string->title, string->description, int->status, string->start_date, string->end_date, int->user_id_created, int->user_id_updated';
$create = new CreateClass();
//$create->createEntity($nameClass, $tableDb, $likeDb, $idPrimary, $fields, $gcid);


//atribuir o usuario ao projeto
$nameClass = 'ProjectUser';
$tableDb = 'project_users';
$likeDb = false;
$idPrimary = 'id';
$gcid = false;
$fields = 'int->id, int->project_id, int->user_id, int->role_id, string->assigned_at, int->user_id_created, int->user_id_updated';
$create = new CreateClass();
//$create->createEntity($nameClass, $tableDb, $likeDb, $idPrimary, $fields, $gcid);

//historico de mudança de status
$nameClass = 'ProjectStatusHistory';
$tableDb = 'project_status_history';
$likeDb = false;
$idPrimary = 'id';
$gcid = false;
$fields = 'int->id, int->project_id, int->status, string->started_at, string->ended_at, int->user_id_created, int->user_id_updated';
$create = new CreateClass();
//$create->createEntity($nameClass, $tableDb, $likeDb, $idPrimary, $fields, $gcid);

//especifica quantos dias pode ficar em cada status
$nameClass = 'ProjectStatusRules';
$tableDb = 'project_status_rules';
$likeDb = false;
$idPrimary = 'id';
$gcid = false;
$fields = 'int->id, int->project_id, int->status, int->max_days, int->user_id_created, int->user_id_updated';
$create = new CreateClass();
//$create->createEntity($nameClass, $tableDb, $likeDb, $idPrimary, $fields, $gcid);

/*
 CREATE TABLE project_role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('visita_tecnica', 'execucao', 'controle', 'outro'),
    can_view_general TINYINT(1) DEFAULT 1,
    can_view_budget TINYINT(1) DEFAULT 0,
    can_view_execution TINYINT(1) DEFAULT 0,
    can_add_files TINYINT(1) DEFAULT 0,
    can_checklist TINYINT(1) DEFAULT 0
);

Exemplo de permissões mínimas:

role	       pode ver geral	orçamento	execução	anexos	checklist
visita_tecnica	✅	            ❌	           ✅	            ✅	  ✅
execucao	✅	            ❌	           ✅	          ✅	  ✅
controle	✅	            ✅	          ✅	         ✅	  ✅
outro	        ✅	             ❌	           ❌	          ❌	  ❌

 */
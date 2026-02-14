<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Settings;

/**
 * Description of StatusConfig
 *
 * @author Ricardo Gomes
 */
class StatusConfig {

    private $statusBudgets = [];

    public function __construct($translate, $lang) {
        $this->statusBudgets = [
            1 => [
                'value' => 1,
                'label' => $translate->translate('Rascunho', $lang),
                'icon' => 'icheck-info',
                'checked' => false
            ],
            2 => [
                'value' => 2,
                'label' => $translate->translate('Enviada', $lang),
                'icon' => 'icheck-carrot',
                'checked' => false
            ],
            3 => [
                'value' => 3,
                'label' => $translate->translate('Revisão solicitada', $lang),
                'icon' => 'icheck-warning',
                'checked' => false
            ],
            4 => [
                'value' => 4,
                'label' => $translate->translate('Revisada', $lang),
                'icon' => 'icheck-cyan',
                'checked' => false
            ],
            5 => [
                'value' => 5,
                'label' => $translate->translate('Aprovada', $lang),
                'icon' => 'icheck-success',
                'checked' => false
            ],
            6 => [
                'value' => 6,
                'label' => $translate->translate('Recusada', $lang),
                'icon' => 'icheck-danger',
                'checked' => false
            ],
            7 => [
                'value' => 7,
                'label' => $translate->translate('Expirada', $lang),
                'icon' => 'icheck-blue',
                'checked' => false
            ],
            8 => [
                'value' => 8,
                'label' => $translate->translate('Cancelada', $lang),
                'icon' => 'icheck-dark',
                'checked' => false
            ],
            9 => [
                'value' => '',
                'label' => $translate->translate('Todos', $lang),
                'icon' => 'icheck-default',
                'checked' => true
            ]
        ];
    }
       public function getStatusBudget() {
        return $this->statusBudgets;
    }
      // novo método para consultar label pelo value
    public function getLabelByValue($value) {
        foreach ($this->statusBudgets as $status) {
            if ((string)$status['value'] === (string)$value) {
                return $status['label'];
            }
        }
        return null; // se não encontrar
    }
}
<?php

namespace Microfw\Src\Main\Controller\Public\AccessPlans;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Public\ClientUsageCounter;

/**
 * Description of UsageServiceAtomic
 *
 * @author Windows 11
 */
class UsageAtomicService {

    public function getCurrentPeriod(): string {
        return date('Y-m'); // YYYY-MM
    }

    /**
     * Garante que exista a linha de uso para o cliente no mês atual
     */
    public function ensureUsageRow() {
        $customer_id = (int) $_SESSION['client_id'];
        $period = $this->getCurrentPeriod();
        $usage = new ClientUsageCounter();
        $usage = $this->getUsage();
        if (!$usage) {
            $usageModel = new ClientUsageCounter();
            $usageModel->setCustomer_id($customer_id);
            $usageModel->setMonth_year($period);
            $usageModel->setTokens_used(0);
            $usageModel->setScripts_used(0);
            $usageModel->setSaveQuery();
        }
        return $this->getUsage();
    }

    /**
     * Retorna a linha de uso do cliente no mês atual
     */
    public function getUsage() {
        $customer_id = (int) $_SESSION['client_id'];
        $period = $this->getCurrentPeriod();
        $usageModel = new ClientUsageCounter();
        $usageModel = $usageModel->getQuery(single: true, customWhere: [['column' => 'customer_id', 'value' => $customer_id], ['column' => 'month_year', 'value' => $period]]);
        return $usageModel ?? null;
    }

    /**
     * Incrementa tokens de forma atômica
     */
    public function addTokensUsed(int $amount) {
        $customer_id = (int) $_SESSION['client_id'];
        if ($amount <= 0)
            return;
        $usage = new ClientUsageCounter();
        $usage = $this->ensureUsageRow();
        $tokens_usage = $usage->getTokens_used();
        $usageModel = new ClientUsageCounter();
        $usageModel->setId($usage->getId());
        $usageModel->setTokens_used(((int) $amount + (int) $tokens_usage));
        $return = $usageModel->setSaveQuery();
        if ($return === 2) {
            $_SESSION['client_plan_tokens_usage'] = ((int) $amount + (int) $tokens_usage);
        }
    }

    /**
     * Incrementa scripts de forma atômica
     */
    public function addScriptUsage(int $amount = 1) {
        $customer_id = (int) $_SESSION['client_id'];
        if ($amount <= 0)
            return;

        $this->ensureUsageRow();
        $period = $this->getCurrentPeriod();
        $usageModel = new ClientUsageCounter();
        $usageModel->setTable_db_primaryKey('customer_id');
        $usageModel->setCustomer_id($customer_id);
        $usageModel->setMonth_year($period);
        $usageModel->setScripts_used($amount);
        $usageModel->setSaveQuery();
    }
}

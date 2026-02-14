<?php

namespace Microfw\Src\Main\Controller\Public\AccessPlans;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Public\ClientUsageCounter;

class UsageService {

    public function getCurrentPeriod(): string {
        return date('Y-m'); // YYYY-MM
    }

    public function getUsage() {
        $customer_id = (int) $_SESSION['client_id'];
        $period = $this->getCurrentPeriod();
        $usageModel = new ClientUsageCounter();
        return $usageModel->getQuery(single: true, customWhere: [['column' => 'customer_id', 'value' => $customer_id], ['column' => 'month_year', 'value' => $period]]);
    }

    public function ensureUsageRow() {
        $customer_id = (int) $_SESSION['client_id'];
        $usage = $this->getUsage($customer_id);

        if (!$usage) {
            $usageModel = new ClientUsageCounter();
            $usageModel->setCustomer_id($customer_id);
            $usageModel->setMonth_year($this->getCurrentPeriod());
            $usageModel->setTokens_used(0);
            $usageModel->setScripts_used(0);
            $usageModel->setSaveQuery();
        }
    }

    public function addTokensUsed(int $amount) {

        $customer_id = (int) $_SESSION['client_id'];
        $this->ensureUsageRow($customer_id);

        $usage = $this->getUsage($customer_id);
        $usage->setTokens_used($usage->getTokens_used() + $amount);
        $usage->setSaveQuery();
    }

    public function addScriptUsage(int $amount = 1) {
        $customer_id = (int) $_SESSION['client_id'];
        $this->ensureUsageRow($customer_id);

        $usage = $this->getUsage($customer_id);
        $usage->setScripts_used($usage->getScripts_used() + $amount);
        $usage->setSaveQuery();
    }
}

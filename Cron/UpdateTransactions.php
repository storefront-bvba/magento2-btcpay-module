<?php
/**
 * Created by PhpStorm.
 * User: wouter
 * Date: 20/05/2017
 * Time: 20:15
 */

namespace Storefront\JobQueue\Cron;

use Storefront\BTCPay\Helper\Data;

class UpdateTransactions {

    /**
     * @var Data
     */
    protected $helper;

    public function __construct(Data $helper) {
        $this->helper = $helper;
    }


    /**
     * Periodically polls BTC Pay Server for updates to transactions. This is no more that a safety net, because BTC Pay Server will push updates to Magento the moment they happen. If for some reason Magento cannot receive the pushed updates, this cronjob will still check for updated and allow the payment to be processed.
     * It is best not to rely on this cronjob.
     * You can use this for testing integrations if your BTCPay Server cannot reach your Magento DEV installation. You may be behind a firewall or not have port forwarding set up to your machine.
     */
    public function execute() {
        $this->helper->updateIncompleteInvoices();
    }
}

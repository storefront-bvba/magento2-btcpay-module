<?php
declare(strict_types=1);
declare(strict_types=1);
/**
 * Integrates BTCPay Server with Magento 2 for online payments
 * @copyright Copyright © 2019-2021 Storefront bv. All rights reserved.
 * @author    Wouter Samaey - wouter.samaey@storefront.be
 *
 * This file is part of Storefront/BTCPay.
 *
 * Storefront/BTCPay is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Storefront\BTCPay\Model\Config\Source\Order\Status;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Config\Source\Order\Status;
use Magento\Sales\Model\Order\Config;

/**
 * Order Status source model
 */
abstract class AbstractStatuses implements \Magento\Framework\Data\OptionSourceInterface {


    /**
     * @var Config
     */
    protected $_orderConfig;


    abstract protected function getState();

    /**
     * @param Config $orderConfig
     */
    public function __construct(Config $orderConfig) {
        $this->_orderConfig = $orderConfig;
    }


    /**
     * @return array
     */
    public function toOptionArray() {
        $state = $this->getState();
        $statuses = $this->_orderConfig->getStateStatuses([$state]);

        $options = [
            [
                'value' => '',
                'label' => __('Use Magento\'s default status')
            ]
        ];
        foreach ($statuses as $code => $label) {
            $options[] = [
                'value' => $code,
                'label' => $label
            ];
        }
        return $options;
    }
}

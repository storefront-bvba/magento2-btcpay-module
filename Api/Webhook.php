<?php
declare(strict_types=1);

namespace Storefront\BTCPay\Api;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use RuntimeException;
use Storefront\BTCPay\Model\BTCPay\BTCPayService;

class Webhook implements WebhookInterface
{


    /**
     * @var BTCPayService
     */
    private $btcPayService;

    public function __construct(BTCPayService $btcPayService)
    {
        $this->btcPayService = $btcPayService;
    }

    /**
     * @return bool
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function process(): bool
    {
        $postedString = file_get_contents('php://input');
        // We are reading the raw POSTed data, as getting an array as input is not working. This is the safest and easiest solution for now.
        if (!$postedString) {
            throw new RuntimeException('No data posted. Cannot process BTCPay Server Webhook.');
        }
        $data = \json_decode($postedString, true);

        // Event "Invoice created"
        // {
        //  "deliveryId": "string",
        //  "webhookId": "string",
        //  "originalDeliveryId": "string",
        //  "isRedelivery": true,
        //  "type": "InvoiceCreated",
        //  "timestamp": 1592312018,
        //  "storeId": "string",
        //  "invoiceId": "string"
        //}

        // Event "Invoice expired"
        // Same as "Invoice created", but contains an extra field: "partiallyPaid" true/false

        // Event "Payment received"
        // Same as "Invoice created", but contains an extra field: "afterExpiration" true/false

        // Event "Invoice processing"
        // Same as "Invoice created", but contains an extra field: "overPaid" true/false

        // Event "Invoice invalid"
        // Same as "Invoice created", but contains an extra field: "manuallyMarked" true/false

        // Event "Invoice settled"
        // Same as "Invoice created", but contains an extra field: "manuallyMarked" true/false

        // TODO support "partiallyPaid" true/false
        // TODO support "afterExpiration" true/false
        // TODO support "overPaid" true/false
        // TODO support "manuallyMarked" true/false

        $btcpayInvoiceId = $data['invoiceId'] ?? null;
        $btcpayStoreId = $data['storeId'] ?? null;

        // Only use the "id" field from the POSTed data and discard the rest. We are not trusting the other posted data.
        unset($data);
        // TODO support "secret" checking. This way we could trust the sender.

        if ($btcpayInvoiceId) {
            $this->btcPayService->updateInvoice($btcpayStoreId, $btcpayInvoiceId);
            return true;
        } else {
            throw new RuntimeException('Invalid data POSTed');
        }
    }
}

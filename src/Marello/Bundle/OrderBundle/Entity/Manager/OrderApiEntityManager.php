<?php

namespace Marello\Bundle\OrderBundle\Entity\Manager;

use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

class OrderApiEntityManager extends ApiEntityManager
{

    /**
     * {@inheritdoc}
     */
    protected function getSerializationConfig()
    {
        $addressConfig = [
            'exclusion_policy' => 'all',
            'fields'           => [
                'namePrefix'   => [],
                'firstName'    => [],
                'middleName'   => [],
                'lastName'     => [],
                'nameSuffix'   => [],
                'street'       => [],
                'street2'      => [],
                'city'         => [],
                'country'      => [],
                'region'       => [],
                'organization' => [],
                'postalCode'   => [],
                'phone'        => [],
            ],
        ];

        $itemConfig = [
            'exclusion_policy'      => 'all',
            'fields'                => [
                'id'                => [],
                'productName'       => [],
                'productSku'        => [],
                'quantity'          => [],
                'price'             => [],
                'originalPrice'     => [],
                'purchasePriceIncl' => [],
                'tax'               => [],
                'taxPercent'        => [],
                'rowTotal'          => [],
            ],
        ];

        $config = [
            'exclusion_policy' => 'all',
            'fields'           => [
                'id'              => [],
                'orderNumber'     => [],
                'orderReference'  => [],
                'subtotal'        => [],
                'totalTax'        => [],
                'grandTotal'      => [],
                'paymentMethod'   => [],
                'paymentDetails'  => [],
                'shippingMethod'  => [],
                'shippingAmount'  => [],
                'salesChannel'    => [
                    'exclusion_policy' => 'all',
                    'fields'           => [
                        'code' => [],
                    ],
                ],
                'workflowItem'    => [
                    'exclusion_policy' => 'all',
                    'fields'           => [
                        'id' => [],
                        'entityId' => [],
                    ],
                ],
                'workflowStep'    => [
                    'exclusion_policy' => 'all',
                    'fields'           => [
                        'name' => [],
                    ],
                ],
                'items'           => $itemConfig,
                'billingAddress'  => $addressConfig,
                'shippingAddress' => $addressConfig,
            ],
        ];

        return $config;
    }
}

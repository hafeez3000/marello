<?php

namespace Marello\Bundle\DemoDataBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marello\Bundle\AddressBundle\Entity\Address;
use Marello\Bundle\OrderBundle\Entity\Order;
use Marello\Bundle\OrderBundle\Entity\OrderItem;

class LoadOrderData extends AbstractFixture implements DependentFixtureInterface
{

    /**
     * {@inheritdoc}
     */
    function getDependencies()
    {
        return [
            'Marello\Bundle\DemoDataBundle\Migrations\Data\Demo\ORM\LoadSalesData',
            'Marello\Bundle\DemoDataBundle\Migrations\Data\Demo\ORM\LoadProductData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $orders = $this->loadOrders($manager);
        $this->loadOrderItems($manager, $orders);

        $manager->flush();
    }

    public function loadOrders(ObjectManager $manager)
    {
        $orders = [
            [
                'OrderReference' => 123456,
                'Subtotal'       => 100,
                'TotalTax'       => 20,
            ],
            [
                'Subtotal' => 100,
                'TotalTax' => 20,
            ],
            [
                'Subtotal' => 1000,
                'TotalTax' => 100,
            ],
            [
                'OrderReference' => 5674321,
                'Subtotal'       => 799,
                'TotalTax'       => 199,
            ],
        ];

        $orderEntities = [];

        $chNo = 0;
        foreach ($orders as $order) {
            $billing = new Address();
            $billing->setFirstName('Falco');
            $billing->setLastName('van der Maden');
            $billing->setStreet('Torenallee 20');
            $billing->setPostalCode('5617 BC');
            $billing->setCity('Eindhoven');
            $billing->setCountry(
                $manager->getRepository('OroAddressBundle:Country')->find('NL')
            );
            $billing->setPhone('+31 40 7074808');
            $billing->setEmail('falco@madia.nl');

            $shipping = clone $billing;

            $orderEntity = new Order($billing, $shipping);
            $orderEntity->setSalesChannel($this->getReference('marello_sales_channel_' . $chNo));
            $chNo  = (int)!$chNo;
            $total = 0;

            foreach ($order as $attribute => $value) {
                call_user_func([$orderEntity, 'set' . $attribute], $value);
                if ($attribute === 'Subtotal' | $attribute === 'TotalTax') {
                    $total += $value;
                }
            }
            $orderEntities[] = $orderEntity->setGrandTotal($total);
        }

        return $orderEntities;
    }

    /**
     * @param ObjectManager $manager
     * @param Order[]       $orders
     */
    private function loadOrderItems(ObjectManager $manager, array $orders)
    {
        $items = [
            [
                'Product' => $this->getReference('marello-product-1'),
                'Quantity'   => 10,
                'Price'      => 29.99,
                'Tax'        => 5,
                'TotalPrice' => '34.99',
            ],
            [
                'Product' => $this->getReference('marello-product-2'),
                'Quantity'   => 2,
                'Price'      => 29.99,
                'Tax'        => 5,
                'TotalPrice' => '34.99',
            ],
            [
                'Product' => $this->getReference('marello-product-3'),
                'Quantity'   => 5,
                'Price'      => 21.18,
                'Tax'        => 5,
                'TotalPrice' => '36.18',
            ],
            [
                'Product' => $this->getReference('marello-product-4'),
                'Quantity'   => 7,
                'Price'      => 29.99,
                'Tax'        => 5,
                'TotalPrice' => '34.99',
            ],
            [
                'Product' => $this->getReference('marello-product-5'),
                'Quantity'   => 2,
                'Price'      => 36.99,
                'Tax'        => 5,
                'TotalPrice' => '41.99',
            ],
            [
                'Product' => $this->getReference('marello-product-6'),
                'Quantity'   => 13,
                'Price'      => 34.49,
                'Tax'        => 5,
                'TotalPrice' => '39.99',
            ],
        ];

        foreach ($orders as $order) {
            $subtotal = 0;
            $tax      = 0;
            $total    = 0;

            foreach ($items as $item) {

                $itemEntity = new OrderItem();

                foreach ($item as $attribute => $value) {
                    call_user_func([$itemEntity, 'set' . $attribute], $value);
                    $order->addItem($itemEntity);
                }

                $subtotal += $itemEntity->getPrice();
                $tax += $itemEntity->getTax();
                $total += $itemEntity->getTotalPrice();
            }
            $order
                ->setSubtotal($subtotal)
                ->setTotalTax($tax)
                ->setGrandTotal($total);

            $manager->persist($order);
        }
    }
}
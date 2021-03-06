<?php

namespace Marello\Bundle\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation as Oro;
use Marello\Bundle\SalesBundle\Entity\SalesChannel;
use Marello\Bundle\ProductBundle\Entity\Product;

/**
 * Represents a Marello ProductPrice
 *
 * @ORM\Entity(repositoryClass="Marello\Bundle\PricingBundle\Entity\Repository\ProductChannelPriceRepository")
 * @ORM\Table(
 *      name="marello_product_channel_price",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="marello_product_channel_price_uidx",
 *              columns={"product_id", "channel_id", "currency"}
 *          )
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Config(
 *  defaultValues={
 *      "entity"={"icon"="icon-usd"},
 *      "security"={
 *          "type"="ACL",
 *          "group_name"=""
 *      }
 *  }
 * )
 */
class ProductChannelPrice extends BasePrice
{
    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Marello\Bundle\ProductBundle\Entity\Product", inversedBy="channelPrices")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     **/
    protected $product;

    /**
     * @var SalesChannel
     *
     * @ORM\ManyToOne(targetEntity="Marello\Bundle\SalesBundle\Entity\SalesChannel")
     * @ORM\JoinColumn(name="channel_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $channel;

    /**
     * @return SalesChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param SalesChannel $channel
     *
     * @return $this
     */
    public function setChannel(SalesChannel $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product    = $product;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}

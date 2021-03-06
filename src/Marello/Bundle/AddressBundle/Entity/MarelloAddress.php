<?php

namespace Marello\Bundle\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Marello\Bundle\OrderBundle\Entity\Customer;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation as Oro;

/**
 * @ORM\Entity
 * @ORM\Table(name="marello_address")
 * @ORM\AssociationOverrides({
 *      @ORM\AssociationOverride(name="region",
 *          joinColumns={
 *              @ORM\JoinColumn(name="region_code", referencedColumnName="combined_code", nullable=true)
 *          }
 *      )
 * })
 * @Oro\Config(
 *      defaultValues={
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "ownership"={
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          }
 *      }
 * )
 */
class MarelloAddress extends AbstractAddress
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="Marello\Bundle\OrderBundle\Entity\Customer", inversedBy="addresses")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     *
     * @var Customer
     */
    protected $customer;

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return $this
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getFullName()
    {
        return implode(' ', array_filter([
            $this->namePrefix,
            $this->firstName,
            $this->middleName,
            $this->lastName,
            $this->nameSuffix,
        ]));
    }
}

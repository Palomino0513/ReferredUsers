<?php

namespace WolfSellers\ReferredUsers\Model;

use Magento\Framework\Model\AbstractModel;
use WolfSellers\ReferredUsers\Api\Data\ReferralInterface;

class Referral extends AbstractModel implements ReferralInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\WolfSellers\ReferredUsers\Model\ResourceModel\Referral::class);
    }

    /**
     * Function to get id of the record.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Function to set id of the record.
     * @param int $id
     * @return Referral
     */
    public function setId($id): static
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Function to get customer id.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Function to set id.
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): static
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Function to get fist name.
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * Function to set first name.
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): static
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * Function to get last name.
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * Function to get last name.
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): static
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * Function to get email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Function to set email.
     *
     * @param string $email
     * @return Referral
     */
    public function setEmail(string $email): static
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Function to get phone.
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * Function to set phone.
     *
     * @param string $phone
     * @return Referral
     */
    public function setPhone(string $phone): static
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * Function to get status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Function to set status.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        return $this->setData(self::STATUS, $status);
    }
}

<?php
namespace WolfSellers\ReferredUsers\Api\Data;

/**
 * Interface for Referral data.
 *
 * @api
 */
interface ReferralInterface
{
    /**
     * Constants for keys of data array.
     */
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const STATUS = 'status';

    /**
     * Get ID.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static;

    /**
     * Get customer id.
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Set customer id.
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): static;

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Set first name.
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): static;

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName(): string;

    /**
     * Set last name.
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): static;

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Set email.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static;

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): string;

    /**
     * Set phone.
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): static;

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set status.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static;
}

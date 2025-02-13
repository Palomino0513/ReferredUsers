<?php

namespace WolfSellers\ReferredUsers\Setup\Patch\Data;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddReferralData implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    private ModuleDataSetupInterface $moduleDataSetup;

    /** @var CustomerInterfaceFactory */
    protected CustomerInterfaceFactory $customerFactory;

    /** @var CustomerRepositoryInterface */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerInterfaceFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerInterfaceFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return void
     * @throws InputException
     * @throws LocalizedException
     * @throws InputMismatchException
     */
    public function apply(): void
    {
        // Test data population begins.
        $this->moduleDataSetup->getConnection()->startSetup();

        // Create test client.
        $customer = $this->customerFactory->create();
        $customer->setFirstname('Roni');
        $customer->setLastname('Cost');
        $customer->setEmail('roni_cost@example.com');
        $customer->setWebsiteId(1);
        $customer->setGroupId(1);

        // Save customer in Magento.
        $customer = $this->customerRepository->save($customer);

        // Get the ID of the created client.
        $customerId = $customer->getId();

        // List with the data of the referred test users.
        $data = [
            [
                'first_name' => 'Ivan',
                'last_name' => 'Rodriguez',
                'email' => 'ivan.rodriguez@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Mateo',
                'last_name' => 'Fernández',
                'email' => 'mateo.fernandez@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Nicolás',
                'last_name' => 'Rodriguez',
                'email' => 'nicolas.ramirez@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Santiago',
                'last_name' => 'Herrera',
                'email' => 'santiago.herrera@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Leonardo',
                'last_name' => 'Torres',
                'email' => 'leonardo.torres@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Emilia',
                'last_name' => 'Castro',
                'email' => 'emilia.castro@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Valentina',
                'last_name' => 'Vargas',
                'email' => 'valentina.vargas@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Camila',
                'last_name' => 'Guzmán',
                'email' => 'camila.guzman@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Sofía',
                'last_name' => 'Rojas',
                'email' => 'sofia.rojas@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ],
            [
                'first_name' => 'Isabella',
                'last_name' => 'Molina',
                'email' => 'isabella.molina@example.com',
                'phone' => '5512345678',
                'status' => 'pendiente',
                'customer_id' => $customerId
            ]
        ];

        // Test referred users are registered.
        $this->moduleDataSetup
            ->getConnection()
            ->insertMultiple($this->moduleDataSetup->getTable('referral'), $data);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Function to return class imports.
     *
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Function to return aliases.
     *
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}

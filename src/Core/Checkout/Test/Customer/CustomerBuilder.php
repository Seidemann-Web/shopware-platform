<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Customer;

use Shopware\Core\Framework\Test\IdsCollection;
use Shopware\Core\Test\TestBuilderTrait;
use Shopware\Core\Test\TestDefaults;

/**
 * How to use:
 *
 * $x = (new CustomerBuilder(new IdsCollection(), 'p1'))
 *          ->firstName('Max')
 *          ->lastName('Muster')
 *          ->group('standard')
 *          ->build();
 */
class CustomerBuilder
{
    use TestBuilderTrait;

    protected string $id;

    protected string $customerNumber;

    protected string $firstName;

    protected string $lastName;

    protected string $email;

    protected string $customerGroupId;

    protected string $defaultBillingAddressId;

    protected array $defaultBillingAddress = [];

    protected string $defaultShippingAddressId;

    protected string $defaultPaymentMethodId;

    protected string $salesChannelId;

    protected array $addresses = [];

    protected array $group = [];

    protected array $defaultPaymentMethod = [];

    public function __construct(
        IdsCollection $ids,
        string $customerNumber,
        string $firstName = 'Max',
        string $lastName = 'Mustermann',
        string $email = 'max@mustermann.com',
        string $customerGroup = 'Standard customer group',
        string $billingAddress = 'Default address',
        string $shippingAddress = 'Default address',
        string $paymentMethod = 'Cash on delivery',
        string $salesChannelId = TestDefaults::SALES_CHANNEL
    ) {
        $this->ids = $ids;
        $this->customerNumber = $customerNumber;
        $this->id = $ids->create($customerNumber);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->salesChannelId = $salesChannelId;

        $this->customerGroup($customerGroup);
        $this->defaultBillingAddress($billingAddress);
        $this->defaultShippingAddress($shippingAddress);
        $this->defaultPaymentMethod($paymentMethod);
    }

    public function customerNumber(string $customerNumber): self
    {
        $this->customerNumber = $customerNumber;

        return $this;
    }

    public function firstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function lastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function customerGroup(string $key): self
    {
        $this->customerGroupId = $this->ids->get($key);
        $this->group = [
            'id' => $this->ids->get($key),
            'name' => $key,
        ];

        return $this;
    }

    public function defaultBillingAddress(string $key, array $customParams = []): self
    {
        $this->addAddress($key, $customParams);

        $defaultBillingAddress = $this->addresses;
        $defaultBillingAddress[$key]['id'] = $this->ids->get($key);
        $this->defaultBillingAddress = $defaultBillingAddress[$key];
        $this->defaultBillingAddressId = $this->ids->get($key);

        return $this;
    }

    public function defaultShippingAddress(string $key, array $customParams = []): self
    {
        $this->addAddress($key, $customParams);
        $this->defaultShippingAddressId = $this->ids->get($key);

        return $this;
    }

    public function defaultPaymentMethod(string $key): self
    {
        $this->defaultPaymentMethodId = $this->ids->get($key);

        $this->defaultPaymentMethod = [
            'id' => $this->ids->get($key),
            'name' => $key,
        ];

        return $this;
    }

    public function addAddress(string $key, array $customParams = []): self
    {
        $address = \array_replace([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'city' => 'Bielefeld',
            'street' => 'Buchenweg 5',
            'zipcode' => '33062',
            'country' => [
                'id' => $this->ids->get($key),
                'name' => 'Germany',
            ],
        ], $customParams);

        $this->addresses[$key] = $address;

        return $this;
    }
}

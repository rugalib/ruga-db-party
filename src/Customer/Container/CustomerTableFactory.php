<?php

declare(strict_types=1);

namespace Ruga\Party\Customer\Container;

use Psr\Container\ContainerInterface;
use Ruga\Db\Adapter\Adapter;
use Ruga\Party\Customer\AbstractCustomerTable;
use Ruga\Party\Customer\CustomerTable;

class CustomerTableFactory
{
    public function __invoke(ContainerInterface $container): AbstractCustomerTable
    {
        return new CustomerTable($container->get(Adapter::class));
    }
}
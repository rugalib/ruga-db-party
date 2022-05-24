<?php

declare(strict_types=1);

namespace Ruga\Party\Tenant\Container;

use Psr\Container\ContainerInterface;
use Ruga\Db\Adapter\Adapter;
use Ruga\Party\Customer\CustomerTable;
use Ruga\Party\Tenant\AbstractTenantTable;

class TenantTableFactory
{
    public function __invoke(ContainerInterface $container): AbstractTenantTable
    {
        return new CustomerTable($container->get(Adapter::class));
    }
}
<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Tenant\Container;

use Psr\Container\ContainerInterface;
use Ruga\Db\Adapter\Adapter;
use Ruga\Party\Customer\CustomerTable;
use Ruga\Party\Tenant\AbstractTenantTable;
use Ruga\Party\Tenant\TenantTable;

class TenantTableFactory
{
    public function __invoke(ContainerInterface $container): AbstractTenantTable
    {
        return new TenantTable($container->get(Adapter::class));
    }
}
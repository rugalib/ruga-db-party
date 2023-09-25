<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Container;

use Psr\Container\ContainerInterface;
use Ruga\Db\Adapter\Adapter;
use Ruga\Party\AbstractPartyTable;
use Ruga\Party\PartyTable;

class PartyTableFactory
{
    public function __invoke(ContainerInterface $container): AbstractPartyTable
    {
        return new PartyTable($container->get(Adapter::class));
    }
}
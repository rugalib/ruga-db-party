<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party;

use Ruga\Db\Schema\Updater;
use Ruga\Party\Customer\CustomerTable;
use Ruga\Party\Link\Organization\PartyHasOrganizationTable;
use Ruga\Party\Link\Person\PartyHasPersonTable;
use Ruga\Party\Link\User\PartyHasUserTable;
use Ruga\Party\Relationship\PartyHasPartyTable;
use Ruga\Party\Tenant\TenantTable;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'db' => [
                Updater::class => [
                    'components' => [
                        Party::class => [
                            Updater::CONF_REQUESTED_VERSION => 14,
                            Updater::CONF_SCHEMA_DIRECTORY => __DIR__ . '/../ruga-dbschema-party',
                            Updater::CONF_TABLES => [
                                'PartyTable' => PartyTable::class,
                                'CustomerTable' => CustomerTable::class,
                                'TenantTable' => TenantTable::class,
                                'PartyHasOrganizationTable' => PartyHasOrganizationTable::class,
                                'PartyHasPersonTable' => PartyHasPersonTable::class,
                                'PartyHasPartyTable' => PartyHasPartyTable::class,
                                'PartyHasUserTable' => PartyHasUserTable::class,
                            ]
                        ],
                    ],
                ],
            ],
            'dependencies' => [
                'factories' => [
                    PartyTable::class => Container\PartyTableFactory::class,
                    Customer\CustomerTable::class => Customer\Container\CustomerTableFactory::class,
                    Tenant\TenantTable::class => Tenant\Container\TenantTableFactory::class,
                ],
                'aliases' => [
                    'PartyTable' => PartyTable::class,
                    'CustomerTable' => Customer\CustomerTable::class,
                    'TenantTable' => Tenant\TenantTable::class,
                ]
            ],
        ];
    }
}
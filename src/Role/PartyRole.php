<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Role;

use Ruga\Party\Customer\CustomerTable;
use Ruga\Party\Tenant\TenantTable;
use Ruga\Std\Enum\AbstractEnum;

/**
 * Class PartyRole
 *
 * @method static self CUSTOMER()
 * @method static self SUPPLIER()
 * @method static self PROSPECT()
 * @method static self SHAREHOLDER()
 * @method static self TENANT()
 */
class PartyRole extends AbstractEnum
{
    const CUSTOMER = 'CUSTOMER';
    const SUPPLIER = 'SUPPLIER';
    const PROSPECT = 'PROSPECT';
    const SHAREHOLDER = 'SHAREHOLDER';
    const TENANT = 'TENANT';
    
    const __fullnameMap = [
        self::CUSTOMER => 'Kunde',
        self::SUPPLIER => 'Lieferant',
        self::PROSPECT => 'Interessent',
        self::SHAREHOLDER => 'Shareholder',
        self::TENANT => 'Mandant',
    ];
    
    const __extraMap = [
        self::CUSTOMER => [
            'roleTableClass' => CustomerTable::class,
//            'template' => 'contactmechanism-TelecomNumber-edit',
//            'formClass' => Form\TelecomNumberForm::class,
        ],
        self::TENANT => [
            'roleTableClass' => TenantTable::class,
//            'template' => 'contactmechanism-TelecomNumber-edit',
//            'formClass' => Form\TelecomNumberForm::class,
        ],
    ];
    
    
}
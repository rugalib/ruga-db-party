<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party;

use Ruga\Std\Enum\AbstractEnum;

/**
 * Defines the types of party subtypes available.
 *
 * @method static self PERSON()
 * @method static self ORGANIZATION()
 */
class PartySubtypeType extends AbstractEnum
{
    const PERSON = 'PERSON';
    const ORGANIZATION = 'ORGANIZATION';
    
    const __default = self::ORGANIZATION;
    
    const __fullnameMap = [
        self::PERSON => 'Person',
        self::ORGANIZATION => 'Firma',
    ];
    
    const __extraMap = [
        self::PERSON => [
            'subtypeLinkTableClass' => Link\Person\PartyHasPersonTable::class,
            'subtypeTableClass' => Subtype\Person\PersonTable::class,
//            'template' => 'contactmechanism-TelecomNumber-edit',
//            'formClass' => Form\TelecomNumberForm::class,
        ],
        self::ORGANIZATION => [
            'subtypeLinkTableClass' => Link\Organization\PartyHasOrganizationTable::class,
            'subtypeTableClass' => Subtype\Organization\OrganizationTable::class,
//            'template' => 'contactmechanism-TelecomNumber-edit',
//            'formClass' => Form\TelecomNumberForm::class,
        ],
    ];
}


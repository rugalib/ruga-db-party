<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Relationship;

/**
 * Class PartyRelationshipType.
 * Representation of the PartyHasParty::$relationship_type.
 *
 * @method static self CUSTOMER()
 * @method static self EMPLOYEE()
 * @method static self CONTRACTOR()
 * @method static self SUPPLIER()
 * @method static self CONTACT()
 * @method static self DISTRIBUTOR()
 * @method static self PARTNER()
 * @method static self ORGANIZATION_UNIT()
 * @method static self REPRESENTATIVE()
 */
class PartyRelationshipType extends \Ruga\Std\Enum\AbstractEnum
{
    const CUSTOMER = 'CUSTOMER';
    const EMPLOYEE = 'EMPLOYEE';
    const CONTRACTOR = 'CONTRACTOR';
    const SUPPLIER = 'SUPPLIER';
    const CONTACT = 'CONTACT';
    const DISTRIBUTOR = 'DISTRIBUTOR';
    const PARTNER = 'PARTNER';
    const ORGANIZATION_UNIT = 'ORGANIZATION_UNIT';
    const REPRESENTATIVE = 'REPRESENTATIVE';
}
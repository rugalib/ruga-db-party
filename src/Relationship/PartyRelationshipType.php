<?php

declare(strict_types=1);

namespace Ruga\Party\Relationship;

/**
 * Class PartyRelationshipType.
 * Representation of the PartyHasParty::$relationship_type.
 *
 * @method static PartyRelationshipType CUSTOMER()
 * @method static PartyRelationshipType EMPLOYEE()
 * @method static PartyRelationshipType CONTRACTOR()
 * @method static PartyRelationshipType SUPPLIER()
 * @method static PartyRelationshipType CONTACT()
 * @method static PartyRelationshipType DISTRIBUTOR()
 * @method static PartyRelationshipType PARTNER()
 * @method static PartyRelationshipType ORGANIZATION_UNIT()
 * @method static PartyRelationshipType REPRESENTATIVE()
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
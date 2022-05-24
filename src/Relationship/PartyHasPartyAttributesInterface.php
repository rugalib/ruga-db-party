<?php

declare(strict_types=1);

namespace Ruga\Party\Relationship;

use Ruga\Db\Row\RowAttributesInterface;

/**
 * Interface PartyHasPartyAttributesInterface
 *
 * @property int                   $Party1_id         Primary key / foreign key to Party 1
 * @property int                   $Party2_id         Primary key / foreign key to Party 2
 * @property PartyRelationshipType $relationship_type Type of the Relation (Party1 is relationship_type of Party2)
 * @property string                $remark            Remark
 */
interface PartyHasPartyAttributesInterface extends RowAttributesInterface
{
    
}
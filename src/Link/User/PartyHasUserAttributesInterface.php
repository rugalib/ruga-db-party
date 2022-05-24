<?php

declare(strict_types=1);

namespace Ruga\Party\Link\User;

use Ruga\Party\Link\AbstractSubtypeLinkAttributesInterface;

/**
 * Interface PartyHasUserAttributesInterface
 *
 * @property int    $User_id           Primary key / foreign key to User
 * @property string $remark            Remark
 */
interface PartyHasUserAttributesInterface extends AbstractSubtypeLinkAttributesInterface
{
    
}
<?php

declare(strict_types=1);

namespace Ruga\Party\EntityHasParty;

use Ruga\Db\ResultSet\ResultSet;
use Ruga\Party\PartyInterface;

/**
 * Interface must be implemented by all entities that provide PARTY role.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
interface EntityRowWithPartyRoleInterface
{
    /**
     * Find and return the connected PARTYs by role.
     *
     * @param EntityHasPartyRole $partyRole
     *
     * @return ResultSet
     */
    public function findPartyByRole(EntityHasPartyRole $partyRole): ResultSet;
    
    
    
    /**
     * Unlink the given PARTY with the given role from the entity.
     *
     * @param PartyInterface     $party
     * @param EntityHasPartyRole $partyRole
     *
     * @return bool
     */
    public function unlinkParty(PartyInterface $party, EntityHasPartyRole $partyRole): bool;
}
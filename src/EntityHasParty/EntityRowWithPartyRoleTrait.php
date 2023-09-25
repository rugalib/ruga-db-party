<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\EntityHasParty;

use Ruga\Db\ResultSet\ResultSet;
use Ruga\Db\Table\TableInterface;
use Ruga\Party\PartyInterface;
use Ruga\Party\PartyTable;

/**
 * Provides functions for an entity that provides PARTY role links.
 * Can be applied to a \Ruga\Db\Row\AbstractRow.
 *
 * @see EntityRowWithPartyRoleInterface
 *
 * @property TableInterface $table
 * @property int            id
 */
trait EntityRowWithPartyRoleTrait
{
    /**
     * Find and return the connected PARTYs by role.
     *
     * @param EntityHasPartyRole $partyRole
     *
     * @return ResultSet
     * @throws \Exception
     */
    
    public function findPartyByRole(EntityHasPartyRole $partyRole): ResultSet
    {
        $entityTableName = $this->table;
        $partyTable = new PartyTable($this->getTableGateway()->getAdapter());
        $partyTableName = $partyTable->table;
        $entityHasPartyTableName = "{$entityTableName}_has_{$partyTableName}";
        $entityHasPartyTable = $this->getTableGateway()->getAdapter()->tableFactory($entityHasPartyTableName);
        
        $sql = $entityHasPartyTable->getSql();
        $select = $sql->select();
        $select->where([
                           "{$entityTableName}_id" => $this->id,
                           "party_role" => "{$partyRole}",
                       ]);
        $links = $entityHasPartyTable->selectWith($select);
        
        // Find the referenced PARTYs
        $party_ids = [];
        iterator_apply(
            $links,
            function ($links, &$party_ids) {
                $party_ids[] = $links->current()->Party_id;
                return true;
            },
            [$links, &$party_ids]
        );
        return $partyTable->findById($party_ids);
    }
    
    
    
    /**
     * Unlink the given PARTY with the given role from the entity.
     *
     * @param PartyInterface     $party
     * @param EntityHasPartyRole $partyRole
     *
     * @return bool
     */
    public function unlinkParty(PartyInterface $party, EntityHasPartyRole $partyRole): bool
    {
        $entityTableName = $this->table;
        $partyTable = new PartyTable($this->getTableGateway()->getAdapter());
        $partyTableName = $partyTable->table;
        $entityHasPartyTableName = "{$entityTableName}_has_{$partyTableName}";
        $entityHasPartyTable = $this->getTableGateway()->getAdapter()->tableFactory($entityHasPartyTableName);
        
        $sql = $entityHasPartyTable->getSql();
        $select = $sql->select();
        $select->where([
                           "{$entityTableName}_id" => $this->id,
                           "Party_id" => $party->id,
                           "party_role" => "{$partyRole}",
                       ]);
        $links = $entityHasPartyTable->selectWith($select);
        
        /** @var \Ruga\Db\Row\RowInterface $link */
        foreach ($links as $link) {
            $link->delete();
        }
        
        return true;
    }
    
    
    
    /**
     * Link the given PARTY with the given role to the entity.
     *
     * @param PartyInterface     $party
     * @param EntityHasPartyRole $partyRole
     *
     * @return EntityHasPartyInterface
     * @throws \Exception
     */
    public function linkParty(PartyInterface $party, EntityHasPartyRole $partyRole): EntityHasPartyInterface
    {
        $entityTableName = $this->table;
        $partyTable = new PartyTable($this->getTableGateway()->getAdapter());
        $partyTableName = $partyTable->table;
        $entityHasPartyTableName = "{$entityTableName}_has_{$partyTableName}";
        /** @var AbstractEntityHasPartyTable $entityHasPartyTable */
        $entityHasPartyTable = $this->getTableGateway()->getAdapter()->tableFactory($entityHasPartyTableName);
        
        $link = $entityHasPartyTable->createRow([
                                                    "{$entityTableName}_id" => $this->id,
                                                    "Party_id" => $party->id,
                                                    "party_role" => "{$partyRole}",
                                                ]);
        
        return $link;
    }
    
    
}

<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Relationship;

use Ruga\Db\Row\RowInterface;
use Ruga\Party\AbstractParty;

interface PartyRelationshipInterface extends RowInterface
{
    /**
     * Find related parties, where $this is <$relType> of.
     * AKA: <$this> is <$relType> of <return[]>
     *
     * @param PartyRelationshipType $relType
     * @param bool                  $rev
     *
     * @return \Laminas\Db\ResultSet\ResultSetInterface|\Ruga\Db\ResultSet\ResultSet
     * @throws \ReflectionException
     */
    public function findRelatedParties(PartyRelationshipType $relType, bool $rev = false);
    
    
    
    /**
     * Find related parties, that are <$relType> of.
     * AKA: <return[]> is <$relType> of <$this>
     *
     * @param PartyRelationshipType $relType
     *
     * @return \Laminas\Db\ResultSet\ResultSetInterface|\Ruga\Db\ResultSet\ResultSet
     * @throws \ReflectionException
     */
    public function findRelatedPartiesRev(PartyRelationshipType $relType);
    
    
    
    /**
     * Links two parties.
     * Read: <$this> is a <$relType> of <$party2>
     *
     * @param PartyRelationshipInterface $party2
     * @param PartyRelationshipType      $relType
     *
     * @return PartyHasParty
     * @throws \ReflectionException
     */
    public function linkTo(PartyRelationshipInterface $party2, PartyRelationshipType $relType): PartyHasParty;
    
    
    
    /**
     * Unlinks two parties.
     * Where <$this> is <$relType> of <$party2>
     *
     * @param PartyRelationshipInterface $party2
     * @param PartyRelationshipType      $relType
     *
     * @return void
     * @throws \ReflectionException
     */
    public function unlinkFrom(PartyRelationshipInterface $party2, PartyRelationshipType $relType);
    
    
    
    /**
     * Return the party object.
     *
     * @return AbstractParty
     * @throws \ReflectionException
     */
    public function getParty(): AbstractParty;
}
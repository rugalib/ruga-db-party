<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Relationship;

use Ruga\Db\Row\AbstractRugaRow;
use Ruga\Party\AbstractParty;
use Ruga\Party\Party;

/**
 * Links a party to a party.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyHasParty extends AbstractRugaRow implements PartyHasPartyAttributesInterface
{
    /** @var AbstractParty */
    private $party1;
    
    /** @var AbstractParty */
    private $party2;
    
    
    
    public function __get($name)
    {
        switch ($name) {
            case 'relationship_type':
                return new PartyRelationshipType(parent::__get($name));
                break;
        }
        return parent::__get($name);
    }
    
    
    
    public function linkToParty1(PartyRelationshipInterface $party1)
    {
        $this->party1 = $party1->getParty();
        $this->Party1_id = $party1->id;
    }
    
    
    
    public function linkToParty2(PartyRelationshipInterface $party2)
    {
        $this->party2 = $party2->getParty();
        $this->Party2_id = $party2->id;
    }
    
    
    
    public function save()
    {
//        $this->party1->save();
//        $this->party2->save();
        
        /** @todo Need specific exceptions */
        if ($this->party1->isNew()) {
            throw new \Exception("Party1 is not saved");
        }
        if ($this->party2->isNew()) {
            throw new \Exception("Party2 is not saved");
        }
        
        $this->Party1_id = $this->party1->id;
        $this->Party2_id = $this->party2->id;
        
        return parent::save();
    }
    
    
}
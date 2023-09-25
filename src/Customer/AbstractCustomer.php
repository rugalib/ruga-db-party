<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Customer;

use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Ruga\Contact\AbstractContactMechanism;
use Ruga\Contact\ContactMechanism;
use Ruga\Contact\ContactMechanismTable;
use Ruga\Contact\ContactMechanismType;
use Ruga\Contact\Link\Party\PartyHasContactMechanismTable;
use Ruga\Contact\Subtype\Address\AbstractAddress;
use Ruga\Contact\Subtype\Address\AddressAttributesInterface;
use Ruga\Db\Row\AbstractRugaRow;
use Ruga\Db\Row\Exception\InvalidArgumentException;
use Ruga\Db\Row\Feature\FullnameFeatureRowInterface;
use Ruga\Party\AbstractParty;
use Ruga\Party\Link\Organization\PartyHasOrganizationAttributesInterface;
use Ruga\Party\Link\Person\PartyHasPersonAttributesInterface;
use Ruga\Party\Party;
use Ruga\Party\PartyAttributesInterface;
use Ruga\Party\PartyTable;
use Ruga\Party\Relationship\PartyHasParty;
use Ruga\Party\Relationship\PartyRelationshipInterface;
use Ruga\Party\Relationship\PartyRelationshipType;
use Ruga\Party\Role\PartyRole;
use Ruga\Party\Subtype\Organization\OrganizationAttributesInterface;
use Ruga\Party\Subtype\Person\PersonAttributesInterface;

/**
 * Abstract customer.
 *
 * @see      Customer
 * @see      CustomerAttributesInterface
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class AbstractCustomer extends AbstractRugaRow implements CustomerAttributesInterface,
                                                                   PartyAttributesInterface,
                                                                   PartyHasOrganizationAttributesInterface,
                                                                   PartyHasPersonAttributesInterface,
                                                                   OrganizationAttributesInterface,
                                                                   PersonAttributesInterface,
                                                                   AddressAttributesInterface, CustomerInterface,
                                                                   FullnameFeatureRowInterface,
                                                                   PartyRelationshipInterface
{
    /**
     * Return the party object.
     *
     * @return AbstractParty
     * @throws \ReflectionException
     */
    public function getParty(): AbstractParty
    {
        if (!$party = $this->findParentRow(PartyTable::class)) {
            $party = $this->createParentRow(PartyTable::class);
        }
        return $party;
    }
    
    
    
    public function findContactMechanismTable()
    {
        return (new ContactMechanismTable($this->getTableGateway()->getAdapter()))->findContactMechanismTable(
            $this->getParty()
        );
    }
    
    
    
    public function getAddress(): AbstractAddress
    {
        /** @var Party $party */
        $party = $this->getParty();
        $cmRowset = $party->findManyToManyRowset(
            ContactMechanismTable::class,
            PartyHasContactMechanismTable::class,
            null,
            null,
            (new Select())->where(function (Where $where) {
                $where->equalTo('contactmechanism_type', ContactMechanismType::POSTAL_ADDRESS);
            })
        );
        
        /** @var ContactMechanism $cm */
        if (!$cm = $cmRowset->current()) {
            $cm = $party->createManyToManyRow(ContactMechanismTable::class, PartyHasContactMechanismTable::class);
            $cm->contactmechanism_type = ContactMechanismType::POSTAL_ADDRESS;
        }
        
        return $cm->getSubtype();
    }
    
    
    
    /**
     * Constructs a display name from the given fields.
     * Fullname is saved in the row to speed up queries.
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getFullname(): string
    {
        return $this->getParty()->getFullname();
    }
    
    
    
    public function __get($name)
    {
        // Try customer attributes
        try {
            return parent::__get($name);
        } catch (\Exception $eThis) {
            if (!$eThis instanceof InvalidArgumentException) {
                throw $eThis;
            }
        }
        
        // try party attributes
        try {
            return $this->getParty()->__get($name);
        } catch (\Exception $eParty) {
            if (!$eParty instanceof InvalidArgumentException) {
                throw $eParty;
            }
        }
        
        // try address attributes
        try {
            return $this->getAddress()->__get($name);
        } catch (\Exception $eAddress) {
            if (!$eAddress instanceof InvalidArgumentException) {
                throw $eAddress;
            }
        }
        
        
        throw $eThis;
    }
    
    
    
    public function __set($name, $value)
    {
        // Try customer attributes
        try {
            parent::__set($name, $value);
            return;
        } catch (\Exception $eThis) {
            if (!$eThis instanceof InvalidArgumentException) {
                throw $eThis;
            }
        }
        
        // try party attributes
        try {
            $this->getParty()->__set($name, $value);
            return;
        } catch (\Exception $eParty) {
            if (!$eParty instanceof InvalidArgumentException) {
                throw $eParty;
            }
        }
        
        // try address attributes
        try {
            $this->getAddress()->__set($name, $value);
            return;
        } catch (\Exception $eAddress) {
            if (!$eAddress instanceof InvalidArgumentException) {
                throw $eAddress;
            }
        }
        
        
        throw $eThis;
    }
    
    
    
    public function save()
    {
        $party = $this->getParty();
        $party->party_role = array_merge($party->party_role, [PartyRole::CUSTOMER]);
        if ($party->isNew()) {
            $party->save();
        }
        return parent::save();
    }
    
    
    
    public function toArray(): array
    {
        $aA = [];
        $aA['html_link'] = "<a href=\"customer/{$this->PK}/edit\">" . $this->fullname . '</a>';
        $aA['isDisabled'] = $this->isDisabled();
        
        $aA['isDisabled'] = false;
        $aA['isDeleted'] = false;
        $aA['canBeChangedBy'] = true;
        
        $aCustomer = parent::toArray();
        $aParty = $this->getParty()->toArray();
        $aAddress = $this->getAddress()->toArray();
        $aSubtype = $this->getParty()->getSubtype()->toArray();
        return array_merge($aParty, $aAddress, $aSubtype, $aCustomer, $aA);
    }
    
    
    
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
    public function findRelatedParties(PartyRelationshipType $relType, bool $rev = false)
    {
        return $this->getParty()->findRelatedParties($relType, $rev);
    }
    
    
    
    /**
     * Find related parties, that are <$relType> of.
     * AKA: <return[]> is <$relType> of <$this>
     *
     * @param PartyRelationshipType $relType
     *
     * @return \Laminas\Db\ResultSet\ResultSetInterface|\Ruga\Db\ResultSet\ResultSet
     * @throws \ReflectionException
     */
    public function findRelatedPartiesRev(PartyRelationshipType $relType)
    {
        return $this->getParty()->findRelatedPartiesRev($relType);
    }
    
    
    
    /**
     * Links two parties.
     * Read: $this is a <$relType> of $party2
     *
     * @param PartyRelationshipInterface $party2
     * @param PartyRelationshipType      $relType
     *
     * @return PartyHasParty
     * @throws \ReflectionException
     */
    public function linkTo(PartyRelationshipInterface $party2, PartyRelationshipType $relType): PartyHasParty
    {
        return $this->getParty()->linkTo($party2, $relType);
    }
    
    
    
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
    public function unlinkFrom(PartyRelationshipInterface $party2, PartyRelationshipType $relType)
    {
        $this->getParty()->unlinkFrom($party2, $relType);
    }
    
    
}

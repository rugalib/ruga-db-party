<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party;

use Ruga\Contact\ContactMechanism;
use Ruga\Contact\ContactMechanismTable;
use Ruga\Contact\Link\ContactMechanismCapableObjectInterface;
use Ruga\Contact\Link\ContactMechanismCapableObjectTrait;
use Ruga\Db\Row\AbstractRugaRow;
use Ruga\Db\Row\Exception\InvalidArgumentException;
use Ruga\Db\Row\Feature\FullnameFeatureRowInterface;
use Ruga\Db\Row\RowInterface;
use Ruga\Party\Exception\IllegalSubtypeLinkException;
use Ruga\Party\Link\AbstractLinkParty;
use Ruga\Party\Link\Organization\PartyHasOrganizationAttributesInterface;
use Ruga\Party\Link\Person\PartyHasPersonAttributesInterface;
use Ruga\Party\Link\User\PartyHasUserTable;
use Ruga\Party\Relationship\PartyHasParty;
use Ruga\Party\Relationship\PartyHasPartyTable;
use Ruga\Party\Relationship\PartyRelationshipInterface;
use Ruga\Party\Relationship\PartyRelationshipType;
use Ruga\Party\Subtype\Organization\Organization;
use Ruga\Party\Subtype\Organization\OrganizationAttributesInterface;
use Ruga\Party\Subtype\Person\Person;
use Ruga\Party\Subtype\Person\PersonAttributesInterface;
use Ruga\Party\Subtype\SubtypeRowInterface;
use Ruga\User\User;

/**
 * Abstract party.
 *
 * @see      Party
 * @see      PartyAttributesInterface
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class AbstractParty extends AbstractRugaRow implements PartyAttributesInterface,
                                                                PartyHasOrganizationAttributesInterface,
                                                                PartyHasPersonAttributesInterface,
                                                                OrganizationAttributesInterface,
                                                                PersonAttributesInterface, PartyInterface,
                                                                FullnameFeatureRowInterface,
                                                                PartyRelationshipInterface,
                                                                ContactMechanismCapableObjectInterface
{
    use ContactMechanismCapableObjectTrait;
    
    /** @var AbstractLinkParty */
    private $subtypelink;
    
    /** @var SubtypeRowInterface */
    private $subtype;
    
    /** @var PartyHasParty[] */
    private $partyRelationships;
    
    
    
    /**
     * Constructs a display name from the given fields.
     * Fullname is saved in the row to speed up queries.
     *
     * @return string
     * @throws \Exception
     */
    public function getFullname(): string
    {
        $subtype_fullname = $this->getSubtype()->getFullname();
        return "" . (empty($subtype_fullname) ? parent::offsetGet('fullname') : $subtype_fullname);
    }
    
    
    
    public function __get($name)
    {
        switch ($name) {
            case 'party_role':
                return parent::__get($name) ?? [];
                break;
            
            case 'party_subtype':
                return new PartySubtypeType(parent::__get($name));
                break;
        }
        
        // Try party attributes
        try {
            return parent::__get($name);
        } catch (\Exception $eThis) {
            if (!$eThis instanceof InvalidArgumentException) {
                throw $eThis;
            }
        }
        
        // try subtype attributes
        try {
            return $this->getSubtype()->__get($name);
        } catch (\Exception $eSubtype) {
            if (!$eSubtype instanceof InvalidArgumentException) {
                throw $eSubtype;
            }
        }
        
        // try link attributes
        try {
            return $this->getSubtypeLink()->__get($name);
        } catch (\Exception $eSubtypeLink) {
            if (!$eSubtypeLink instanceof InvalidArgumentException) {
                throw $eSubtypeLink;
            }
        }
        
        throw $eThis;
    }
    
    
    
    public function __set($name, $value)
    {
        // Try my attributes
        try {
            parent::__set($name, $value);
            return;
        } catch (\Exception $eThis) {
            if (!$eThis instanceof \Ruga\Db\Row\Exception\InvalidArgumentException) {
                throw $eThis;
            }
        }
        
        // Try subtype (person/organization)
        try {
            $this->getSubtype()->__set($name, $value);
            return;
        } catch (\Exception $eSubtype) {
            if (!$eSubtype instanceof \Ruga\Db\Row\Exception\InvalidArgumentException) {
                throw $eSubtype;
            }
        }
        
        // Try subtypelink (person/organization)
        try {
            $this->getSubtypeLink()->__set($name, $value);
            return;
        } catch (\Exception $eSubtypeLink) {
            if (!$eSubtypeLink instanceof \Ruga\Db\Row\Exception\InvalidArgumentException) {
                throw $eSubtypeLink;
            }
        }
        
        throw $eThis;
    }
    
    
    
    public function save()
    {
        try {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->beginTransaction();
            
            $ret = parent::save();
            
            // Save the sub type (Organizaion, Person)
            $this->getSubtype()->save();
            
            // Save the sub type link
            $this->getSubtypeLink()->Party_id = $this->id;
            $this->getSubtypeLink()->Subtype_id = $this->getSubtype()->id;
            $this->getSubtypeLink()->save();
            
            // Save the registered contact mechanisms
            $this->saveRegisteredContactMechanisms();
            
            return $ret;
        } catch (\Exception $e) {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->rollback();
            throw $e;
        } finally {
            if (!isset($e)) {
                $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->commit();
            }
        }
    }
    
    
    
    public function getSubtypeLink(): AbstractLinkParty
    {
//        \Ruga\Log::functionHead($this);
        
        if (!$this->subtypelink) {
            if (!PartySubtypeType::isValidValue($this->party_subtype)) {
                throw new Exception\IllegalSubtypeLinkException(
                    "'{$this->party_subtype}' is not valid subtype for '" . get_class($this) . "'"
                );
            }
            $suptypeLinkTableClassName = null;
            foreach (PartySubtypeType::getObjects() as $object) {
                if ($object->val == $this->party_subtype) {
                    $suptypeLinkTableClassName = $object->suptypeLinkTableClass;
                }
            }
            
            $t = new $suptypeLinkTableClassName($this->getTableGateway()->getAdapter());
            if ($this->isNew() || !$this->subtypelink = $t->select(
                    ['Party_id' => $this->id]
                )->current()) {
                $this->subtypelink = $t->createRow();
            }
        }
        if (!$this->subtypelink instanceof AbstractLinkParty) {
            throw new Exception\IllegalSubtypeLinkException(
                "subtypelink is not a '" . AbstractLinkParty::class . "'."
            );
        }
        return $this->subtypelink;
    }
    
    
    
    public function getSubtypePerson(): Person
    {
        $subtype = $this->getSubtype();
        if (!$subtype instanceof Person) {
            throw new IllegalSubtypeLinkException("subtype is not a '" . Person::class . "'");
        }
        return $subtype;
    }
    
    
    
    public function getSubtypeOrganization(): Organization
    {
        $subtype = $this->getSubtype();
        if (!$subtype instanceof Organization) {
            throw new IllegalSubtypeLinkException("subtype is not a '" . Organization::class . "'");
        }
        return $subtype;
    }
    
    
    
    public function getSubtype(): SubtypeRowInterface
    {
//        \Ruga\Log::functionHead($this);
        
        if (!$this->subtype) {
            if (!PartySubtypeType::isValidValue($this->party_subtype)) {
                throw new Exception\IllegalSubtypeLinkException(
                    "'{$this->party_subtype}' is not valid subtype for '" . get_class($this) . "'"
                );
            }
            $suptypeTableClassName = null;
            foreach (PartySubtypeType::getObjects() as $object) {
                if ($object->val == $this->party_subtype) {
                    $suptypeTableClassName = $object->subtypeTableClass;
                    $subtypeLinkKeyName = $object->val == 'ORGANIZATION' ? 'Organization_id' : 'Person_id';
                }
            }
            $t = new $suptypeTableClassName($this->getTableGateway()->getAdapter());
            if ($this->isNew() || !$this->getSubtypeLink() || !isset(
                    $this->getSubtypeLink()->Subtype_id
                ) || !$this->subtype = $t->findById(
                    $this->getSubtypeLink()->Subtype_id
                )->current()) {
                $this->subtype = $t->createRow();
            }
        }
        if (!$this->subtype instanceof SubtypeRowInterface) {
            throw new Exception\IllegalSubtypeLinkException(
                "subtype is not a '" . SubtypeRowInterface::class . "'."
            );
        }
        return $this->subtype;
    }
    
    
    
    public function toArray(): array
    {
        $aParty = parent::toArray();
        $aParty['html_link'] = "<a href=\"party/{$this->PK}/edit\">" . $this->fullname . '</a>';
        $aParty['isDisabled'] = $this->isDisabled();
        
        $aParty['isDisabled'] = false;
        $aParty['isDeleted'] = false;
        $aParty['canBeChangedBy'] = true;
        
        
        $aSubtypeLink = $this->getSubtypeLink()->toArray();
        $aSubtype = $this->getSubtype()->toArray();
        return array_merge($aSubtypeLink, $aSubtype, $aParty);
    }
    
    
    
    /**
     * Links two parties.
     * Read: $this is a $relType of $party2
     *
     * @param Party $party2
     * @param PartyRelationshipType $relType
     *
     * @return PartyHasParty
     * @throws \ReflectionException
     */
    public function linkTo(PartyRelationshipInterface $party2, PartyRelationshipType $relType): PartyHasParty
    {
        $t = new PartyHasPartyTable($this->getTableGateway()->getAdapter());
        
        /** @var PartyHasParty $link */
        $link = $t->createRow();
        
        $link->linkToParty1($this);
        $link->linkToParty2($party2);
        $link->relationship_type = $relType;
        
        $this->partyRelationships[] = $link;
        return $link;
    }
    
    
    
    public function unlinkFrom(PartyRelationshipInterface $party2, PartyRelationshipType $relType)
    {
        $t = new PartyHasPartyTable($this->getTableGateway()->getAdapter());
        
        /** @var PartyHasParty $link */
        $link = $t->select(
            [
                'Party1_id' => $this->id,
                'Party2_id' => $party2->getParty()->id,
                'relationship_type' => ($relType)(),
            ]
        )->current();
        $link->delete();
    }
    
    
    
    /**
     * Find related parties, where $this is $relType of.
     * AKA: $this is $relType of return[]
     *
     * @param PartyRelationshipType $relType
     * @param bool                  $rev
     *
     * @return \Laminas\Db\ResultSet\ResultSetInterface|\Ruga\Db\ResultSet\ResultSet
     * @throws \ReflectionException
     */
    public function findRelatedParties(PartyRelationshipType $relType, bool $rev = false)
    {
        if ($rev) {
            $lhs = 'Party2_id';
            $rhs = 'Party1_id';
        } else {
            $lhs = 'Party1_id';
            $rhs = 'Party2_id';
        }
        
        $t = new PartyHasPartyTable($this->getTableGateway()->getAdapter());
        
        $sql = $t->getSql();
        $select = $sql->select();
        
        $select->where(
            [
                $lhs => $this->PK,
                'relationship_type' => ($relType)(),
            ]
        );

//        \Ruga\Log::log_msg("SQL={$sql->buildSqlString($select)}");
        $links = $t->selectWith($select);
        
        // Find the referenced Parties
        $party_ids = [];
        iterator_apply(
            $links,
            function ($links, &$party_ids) use ($rhs) {
                $party_ids[] = $links->current()->__get($rhs);
                return true;
            },
            [$links, &$party_ids]
        );
        
        return $this->getTableGateway()->findById($party_ids);
    }
    
    
    
    /**
     * Find related parties, that are $relType of.
     * AKA: return[] is $relType of $this
     *
     * @param PartyRelationshipType $relType
     *
     * @return \Laminas\Db\ResultSet\ResultSetInterface|\Ruga\Db\ResultSet\ResultSet
     * @throws \ReflectionException
     */
    public function findRelatedPartiesRev(PartyRelationshipType $relType)
    {
        return $this->findRelatedParties($relType, true);
    }
    
    
    
    /**
     * Return the party object.
     *
     * @return AbstractParty
     * @throws \ReflectionException
     */
    public function getParty(): AbstractParty
    {
        return $this;
    }
    
    
    
    /**
     * Delete the party with all its components.
     * This function is transaction-save.
     *
     * @return int
     * @throws \Exception
     */
    public function delete()
    {
        try {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->beginTransaction();
            
            // Delete subtype link
            $subtypelink = $this->getSubtypeLink();
            $subtypelink->delete();
            
            // Delete subtype (Person/Organization)
            $subtype = $this->getSubtype();
            $subtype->delete();
            
            
            // Delete contactmechanisms
            /** @var ContactMechanism $cm */
            foreach (
                (new ContactMechanismTable($this->getTableGateway()->getAdapter()))->findContactMechanismTable(
                    $this,
                    true
                ) as $cm
            ) {
                $cm->unlinkFrom($this);
                $cm->delete();
            }
            
            // Delete party
            return parent::delete();
        } catch (\Exception $e) {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->rollback();
            throw $e;
        } finally {
            if (!isset($e)) {
                $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->commit();
            }
        }
    }
    
    
    
    public function findContactMechanismTable()
    {
        return (new ContactMechanismTable($this->getTableGateway()->getAdapter()))->findContactMechanismTable(
            $this
        );
    }
    
    
    
    /**
     * Link the PARTY to the given USER.
     *
     * @param User  $user
     * @param array $rowData
     *
     * @return RowInterface
     * @throws \ReflectionException
     */
    public function linkToUser(User $user, array $rowData=[]): RowInterface
    {
        return $this->getParty()->linkManyToManyRow($user, PartyHasUserTable::class, $rowData, 'User_id');
    }
    
}

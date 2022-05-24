<?php

declare(strict_types=1);

namespace Ruga\Party\Tenant;

use Ruga\Contact\AbstractContactMechanism;
use Ruga\Contact\ContactMechanismTable;
use Ruga\Contact\ContactMechanismType;
use Ruga\Contact\Subtype\Address\AbstractAddress;
use Ruga\Contact\Subtype\Address\AddressAttributesInterface;
use Ruga\Db\Row\AbstractRugaRow;
use Ruga\Db\Row\Exception\InvalidArgumentException;
use Ruga\Db\Row\Feature\FullnameFeatureRowInterface;
use Ruga\Party\AbstractParty;
use Ruga\Party\Link\Organization\PartyHasOrganizationAttributesInterface;
use Ruga\Party\Link\Person\PartyHasPersonAttributesInterface;
use Ruga\Party\PartyAttributesInterface;
use Ruga\Party\PartyTable;
use Ruga\Party\Relationship\PartyHasParty;
use Ruga\Party\Relationship\PartyRelationshipInterface;
use Ruga\Party\Relationship\PartyRelationshipType;
use Ruga\Party\Role\PartyRole;
use Ruga\Party\Role\PartyRoleInterface;
use Ruga\Party\Subtype\Organization\OrganizationAttributesInterface;
use Ruga\Party\Subtype\Person\PersonAttributesInterface;

/**
 * Abstract tenant.
 *
 * @see      Tenant
 * @see      TenantAttributesInterface
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class AbstractTenant extends AbstractRugaRow implements TenantAttributesInterface,
                                                                 PartyAttributesInterface,
                                                                 PartyHasOrganizationAttributesInterface,
                                                                 PartyHasPersonAttributesInterface,
                                                                 OrganizationAttributesInterface,
                                                                 PersonAttributesInterface,
                                                                 AddressAttributesInterface, TenantInterface,
                                                                 FullnameFeatureRowInterface,
                                                                 PartyRelationshipInterface
{
    /** @var AbstractParty */
    private $party;
    
    /** @var AbstractContactMechanism */
    private $contactmechanism_address;
    
    
    
    /**
     * Return the party object.
     *
     * @return AbstractParty
     * @throws \ReflectionException
     */
    public function getParty(): AbstractParty
    {
        if (!$this->party) {
            if (!isset($this->Party_id) || !($this->party = (new PartyTable(
                    $this->getTableGateway()->getAdapter()
                ))->findById(
                    $this->Party_id
                )->current())) {
                $this->party = (new PartyTable($this->getTableGateway()->getAdapter()))->createRow();
            }
        }
        return $this->party;
    }
    
    
    
    public function findContactMechanismTable()
    {
        return (new ContactMechanismTable($this->getTableGateway()->getAdapter()))->findContactMechanismTable(
            $this->getParty()
        );
    }
    
    
    
    public function getAddress(): AbstractAddress
    {
        \Ruga\Log::functionHead();
        
        if (!$this->contactmechanism_address) {
            /** @var AbstractContactMechanism $contactmechanism */
            foreach ($this->findContactMechanismTable() as $contactmechanism) {
                if ($contactmechanism->contactmechanism_type == ContactMechanismType::POSTAL_ADDRESS) {
                    $this->contactmechanism_address = $contactmechanism;
                }
            }
            if (!$this->contactmechanism_address) {
                $this->contactmechanism_address = (new ContactMechanismTable(
                    $this->getTableGateway()->getAdapter()
                ))->createRow(
                    ['contactmechanism_type' => ContactMechanismType::POSTAL_ADDRESS]
                );
                $this->contactmechanism_address->linkTo($this->getParty());
            }
        }

//        \Ruga\Log::log_msg("contactmechanism_address: {$this->contactmechanism_address->idname}");
        return $this->contactmechanism_address->getSubtype();
    }
    
    
    
    /**
     * Constructs a display name from the given fields.
     * Fullname is saved in the row to speed up queries.
     *
     * @return string
     */
    public function getFullname(): string
    {
        return $this->getParty()->getFullname();
    }
    
    
    
    public function __get($name)
    {
//        switch ($name) {
//        }
        
        // Try tenant attributes
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
        // Try tenant attributes
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
        try {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->beginTransaction();
            
            $party = $this->getParty();
            $party->party_role = array_merge($party->party_role, [PartyRole::TENANT]);
            $party->save();
            $this->Party_id = $party->id;
            $ret = parent::save();
            
            if ($this->contactmechanism_address) {
                $this->contactmechanism_address->save();
            }
            
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
    
    
    
    public function toArray(): array
    {
        $aA = [];
        $aA['html_link'] = "<a href=\"tenant/{$this->PK}/edit\">" . $this->fullname . '</a>';
        $aA['isDisabled'] = $this->isDisabled();
        
        $aA['isDisabled'] = false;
        $aA['isDeleted'] = false;
        $aA['canBeChangedBy'] = true;
        
        $aThis = parent::toArray();
        $aParty = $this->getParty()->toArray();
        $aAddress = $this->getAddress()->toArray();
        $aSubtype = $this->getParty()->getSubtype()->toArray();
        return array_merge($aParty, $aAddress, $aSubtype, $aThis, $aA);
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

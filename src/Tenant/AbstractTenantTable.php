<?php

declare(strict_types=1);

namespace Ruga\Party\Tenant;

use Laminas\Db\Sql\Select;
use Ruga\Db\ResultSet\ResultSet;
use Ruga\Db\Table\AbstractRugaTable;
use Ruga\Party\Link\User\PartyHasUserTable;
use Ruga\Party\Party;
use Ruga\Party\PartyTable;
use Ruga\User\User;

abstract class AbstractTenantTable extends AbstractRugaTable
{
    /**
     * Find tenants by given user.
     * This returns all the tenants that the user is a representative of. It checks the Party-Party-Relation.
     *
     * @param User $user
     *
     * @return ResultSet
     * @throws \ReflectionException
     */
    public function findByUser(User $user): ResultSet
    {
        /** @var Party $userParty */
        $userParty = (new PartyTable($this->getAdapter()))->findByUser($user)->current();
        if (!$userParty) {
            // User has not party associated => return empty ResultSet
            return (new ResultSet())->initialize([]);
        }
        
        $tenantParties = $userParty->findRelatedParties(
            \Ruga\Party\Relationship\PartyRelationshipType::REPRESENTATIVE()
        );
        $tenantPartyIds = array_map(
            function (Party $party) {
                return $party->PK;
            },
            iterator_to_array($tenantParties)
        );
        
        /** @var Select $select */
        $select = $this->getSql()->select();
        if (empty($tenantPartyIds)) {
            $select->where("FALSE");
        } else {
            $select->where(['Party_id' => $tenantPartyIds]);
        }

//        \Ruga\Log::log_msg($select->getSqlString($this->getAdapter()->getPlatform()));
        
        return $this->selectWith($select);
    }
}
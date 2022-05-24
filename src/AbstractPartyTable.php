<?php

declare(strict_types=1);

namespace Ruga\Party;

use Laminas\Db\Sql\Select;
use Psr\Http\Message\ServerRequestInterface;
use Ruga\Db\ResultSet\ResultSet;
use Ruga\Db\Row\RowInterface;
use Ruga\Db\Table\AbstractRugaTable;
use Ruga\Party\Customer\Customer;
use Ruga\Party\Exception\UnknownObject;
use Ruga\Party\Link\User\PartyHasUserTable;
use Ruga\Party\Relationship\PartyHasPartyTable;
use Ruga\Party\Role\PartyRoleInterface;
use Ruga\Party\Subtype\Organization\Organization;
use Ruga\Party\Subtype\Person\Person;
use Ruga\Party\Subtype\SubtypeRowInterface;
use Ruga\Party\Tenant\Tenant;
use Ruga\User\User;

abstract class AbstractPartyTable extends AbstractRugaTable
{
    /**
     * Finds the base party object of a specialized object.
     *
     * @param RowInterface|User|Customer|Tenant|Organization|Person $row
     *
     * @return ResultSet
     *
     * @throws \ReflectionException
     */
    public function findByLinkedObject(RowInterface $row): ResultSet
    {
        if ($row instanceof User) {
            /** @var User $row */
            return $this->findByUser($row);
        }
        if ($row instanceof PartyRoleInterface) {
            /** @var PartyRoleInterface $row */
            return $this->findById($row->Party_id);
        }
        if ($row instanceof SubtypeRowInterface) {
            /** @var SubtypeRowInterface $row */
            return $this->findById($row->getParty()->PK);
        }
        
        throw new UnknownObject("Unable to find a party link for object of type '" . get_class($row) . "'");
    }
    
    
    
    public function findByUser(User $user): ResultSet
    {
        /** @var Select $select */
        $select = $this->getSql()->select();
        
        $select->join(['pu' => PartyHasUserTable::TABLENAME], "pu.Party_id = {$this->info('name')}.id", []);
        $select->where(['pu.User_id' => $user->PK]);

//        \Ruga\Log::log_msg($select->getSqlString($this->getAdapter()->getPlatform()));
        
        return $this->selectWith($select);
    }
    
    
    
    public function customizeSqlSelectFromRequest(
        string $customSqlSelectName,
        Select $select,
        ServerRequestInterface $request
    ) {
        \Ruga\Log::functionHead();
        
        parent::customizeSqlSelectFromRequest(
            $customSqlSelectName,
            $select,
            $request
        );
        
        if ($customSqlSelectName == 'related') {
            $customSqlData = $request->getQueryParams()['customSqlData'] ?? [];
            $mainPartyId = $customSqlData['Party_id'];
            $relationshipType = new Relationship\PartyRelationshipType($customSqlData['relationship_type']);
            $select->join(['PhP' => PartyHasPartyTable::TABLENAME], "PhP.Party1_id={$this->getTable()}.id", []);
            $select->where(['PhP.Party2_id' => "{$mainPartyId}", 'PhP.relationship_type' => "{$relationshipType}"]);
        }
    }
    
    
}
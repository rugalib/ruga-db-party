<?php

declare(strict_types=1);

namespace Ruga\Party\Test;


use Ruga\Party\Link\User\PartyHasUserTable;
use Ruga\Party\Party;
use Ruga\Person\Person;
use Ruga\User\User;

/**
 * @author Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class UserTest extends \Ruga\Party\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanFindPartyOfUser(): void
    {
        /** @var \Ruga\User\User $user */
        $user = $this->getAdapter()->rowFactory('4@UserTable');
        echo "User: {$user->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\User\User::class, $user);
        
        /** @var \Ruga\Party\PartyTable $partyTable */
        $partyTable = $this->getAdapter()->tableFactory(\Ruga\Party\PartyTable::class);
        
        /** @var \Ruga\Party\Party $party */
        $party = $partyTable->findByUser($user)->current();
        echo "Party: {$party->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\Party\Party::class, $party);
    }
    
    
    
    public function testCanFindTenantOfUser(): void
    {
        /** @var \Ruga\User\User $user */
        $user = $this->getAdapter()->rowFactory('4@UserTable');
        echo "User: {$user->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\User\User::class, $user);
        
        /** @var \Ruga\Party\PartyTable $partyTable */
        $partyTable = $this->getAdapter()->tableFactory(\Ruga\Party\PartyTable::class);
        
        /** @var \Ruga\Party\Party $userParty */
        $userParty = $partyTable->findByUser($user)->current();
        echo "User Party: {$userParty->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\Party\Party::class, $userParty);
        
        /** @var \Ruga\Party\Party $tenantParty */
        $tenantParty = $userParty->findRelatedParties(
            \Ruga\Party\Relationship\PartyRelationshipType::REPRESENTATIVE()
        )->current();
        echo "Tenant Party: {$tenantParty->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\Party\Party::class, $tenantParty);
        
        /** @var \Ruga\Party\Tenant\Tenant $tenant */
        $tenant = (new \Ruga\Party\Tenant\TenantTable($this->getAdapter()))->select(['Party_id' => $tenantParty->PK]
        )->current();
        echo "Tenant: {$tenant->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\Party\Tenant\Tenant::class, $tenant);
    }
    
    
    
    public function testCanFindTenantsOfUser(): void
    {
        /** @var \Ruga\User\User $user */
        $user = $this->getAdapter()->rowFactory('4@UserTable');
        echo "User: {$user->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\User\User::class, $user);
        
        /** @var \Ruga\Party\Tenant\TenantTable $tenantTable */
        $tenantTable = $this->getAdapter()->tableFactory(\Ruga\Party\Tenant\TenantTable::class);
        $tenants = $tenantTable->findByUser($user);
        /** @var \Ruga\Party\Tenant\Tenant $tenant */
        foreach ($tenants as $tenant) {
            echo "Tenant: {$tenant->idname}" . PHP_EOL;
            $this->assertInstanceOf(\Ruga\Party\Tenant\Tenant::class, $tenant);
        }
    }
    
    
    
    public function testCanLinkUserToParty(): void
    {
        $userTable = new \Ruga\User\UserTable($this->getAdapter());
        /** @var User $user */
        $user = $userTable->createRow(['username' => 'markus.hugentobler', 'fullname' => 'Markus Hugentobler']);
        
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        /** @var Party $party */
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::PERSON]);
        $party->first_name='Markus';
        $party->last_name='Hugentobler';
        
        $party->linkToUser($user);
        $party->save();
        
        $personTable = new \Ruga\Person\PersonTable($this->getAdapter());
        /** @var Person $person */
        $person = $personTable->select(['first_name' => 'Markus', 'last_name' => 'Hugentobler'])->current();
        $this->assertInstanceOf(Person::class, $person);
    }
    
}

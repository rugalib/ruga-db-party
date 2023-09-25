<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Test;

/**
 * @author Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyRelationshipTest extends \Ruga\Party\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanLinkContactToCustomer(): void
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        $customerTable = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var \Ruga\Party\Customer\Customer $customer */
        $customer = $customerTable->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $customer->name = "Holdrioooo AG";
        $customer->customer_number = rand(100000, 199999);
        $customer->save();
        
        /** @var \Ruga\Party\Party $contact */
        $contact = $partyTable->createRow();
        $contact->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $contact->first_name = 'Dave';
        $contact->last_name = 'Danger';
        $contact->save();
        
        $link = $contact->linkTo($customer, \Ruga\Party\Relationship\PartyRelationshipType::CONTACT());
        $this->assertInstanceOf(\Ruga\Party\Relationship\PartyHasParty::class, $link);
        
        $link->save();
    }
    
    
    
    public function testCanFetchContactsOfCustomer()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        $customerTable = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var \Ruga\Party\Customer\Customer $customer */
        $customer = $customerTable->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $customer->name = "Holdrioooo AG";
        $customer->customer_number = rand(100000, 199999);
        $customer->save();
        
        /** @var \Ruga\Party\Party $contact */
        $contact = $partyTable->createRow();
        $contact->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $contact->first_name = 'Dave';
        $contact->last_name = 'Danger';
        $contact->save();
        $contact->linkTo($customer, \Ruga\Party\Relationship\PartyRelationshipType::CONTACT())->save();
        
        $contact = $partyTable->createRow();
        $contact->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $contact->first_name = 'Anita';
        $contact->last_name = 'Bühlmann';
        $contact->save();
        $contact->linkTo($customer, \Ruga\Party\Relationship\PartyRelationshipType::CONTACT())->save();
        
        
        $parties = $customer->findRelatedPartiesRev(\Ruga\Party\Relationship\PartyRelationshipType::CONTACT());
        $this->assertCount(2, $parties);
        /** @var \Ruga\Party\Party $party */
        foreach ($parties as $party) {
            echo "Contact: {$party->type} {$party->idname}" . PHP_EOL;
            $this->assertInstanceOf(\Ruga\Party\Party::class, $party);
        }
        
        
        $customers = $party->findRelatedParties(\Ruga\Party\Relationship\PartyRelationshipType::CONTACT());
        $this->assertCount(1, $customers);
        $customer = $customers->current();
        echo "Customer: {$customer->type} {$customer->idname}" . PHP_EOL;
        $this->assertInstanceOf(\Ruga\Party\Party::class, $customer);
    }
    
    
    
    public function testCanUnlinkContactFromCustomer()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        $customerTable = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var \Ruga\Party\Customer\Customer $customer */
        $customer = $customerTable->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $customer->name = "Holdrioooo AG";
        $customer->customer_number = rand(100000, 199999);
        $customer->save();
        $customer_id = $customer->id;
        
        /** @var \Ruga\Party\Party $contact */
        $contact = $partyTable->createRow();
        $contact->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $contact->first_name = 'Dave';
        $contact->last_name = 'Danger';
        $contact->save();
        $id1 = $contact->id;
        $contact->linkTo($customer, \Ruga\Party\Relationship\PartyRelationshipType::CONTACT())->save();
        
        $contact = $partyTable->createRow();
        $contact->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $contact->first_name = 'Anita';
        $contact->last_name = 'Bühlmann';
        $contact->save();
        $id2 = $contact->id;
        $contact->linkTo($customer, \Ruga\Party\Relationship\PartyRelationshipType::CONTACT())->save();
        
        unset($contact);
        unset($customer);
        $contact = $partyTable->findById($id1)->current();
        $this->assertInstanceOf(\Ruga\Party\Party::class, $contact);
        $customer = $customerTable->findById($customer_id)->current();
        $this->assertInstanceOf(\Ruga\Party\Customer\Customer::class, $customer);
        
        $parties = $customer->findRelatedPartiesRev(\Ruga\Party\Relationship\PartyRelationshipType::CONTACT());
        $this->assertCount(2, $parties);
        
        $contact->unlinkFrom($customer, \Ruga\Party\Relationship\PartyRelationshipType::CONTACT());
        
        $parties = $customer->findRelatedPartiesRev(\Ruga\Party\Relationship\PartyRelationshipType::CONTACT());
        $this->assertCount(1, $parties);
    }
    
}

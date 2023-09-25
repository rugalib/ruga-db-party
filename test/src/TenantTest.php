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
class TenantTest extends \Ruga\Party\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanCreateTenant(): void
    {
        $t = new \Ruga\Party\Tenant\TenantTable($this->getAdapter());
        
        /** @var \Ruga\Party\Tenant\Tenant $row */
        $row = $t->createRow();
        $this->assertInstanceOf(\Ruga\Party\Tenant\Tenant::class, $row);
        $row->save();
        
        $row = $t->createRow();
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $this->assertInstanceOf(\Ruga\Party\Subtype\Person\Person::class, $row->getParty()->getSubtype());
        $row->save();
        
        $row = $t->createRow();
        $row->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $this->assertInstanceOf(
            \Ruga\Party\Subtype\Organization\Organization::class,
            $row->getParty()->getSubtype()
        );
        $row->save();
    }
    
    
    
    public function testCanWriteTenant(): void
    {
        $t = new \Ruga\Party\Tenant\TenantTable($this->getAdapter());
        
        /** @var \Ruga\Party\Tenant\Tenant $row */
        $row = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $row->first_name = $first_name;
        $row->last_name = $last_name;
        $this->assertSame($first_name, $row->first_name);
        $this->assertSame($last_name, $row->last_name);
        $this->assertSame("{$first_name} {$last_name}", $row->fullname);
        $row->save();
        
        $row = $t->createRow();
        $name = 'Meier AG';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $row->name = $name;
        $row->date_of_establishment = new \DateTimeImmutable();
        $this->assertSame($name, $row->name);
        $this->assertSame("{$name}", $row->fullname);
        $row->save();
    }
    
    
    
    public function testCanReadTenant(): void
    {
        $t = new \Ruga\Party\Tenant\TenantTable($this->getAdapter());
        /** @var \Ruga\Party\Tenant\Tenant $row */
        $row = $t->createRow();
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $row->first_name = 'Hans';
        $row->last_name = 'Müller';
        $row->save();
        $id1 = $row->id;
        
        $row = $t->createRow();
        $row->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $row->name = 'Meier AG';
        $row->date_of_establishment = new \DateTimeImmutable();
        $row->save();
        $id2 = $row->id;
        
        unset($row);
        $row = $t->findById($id1)->current();
        $this->assertSame('Hans', $row->first_name);
        $this->assertSame('Hans Müller', $row->fullname);
        
        unset($row);
        $row = $t->findById($id2)->current();
        $this->assertInstanceOf(
            \Ruga\Party\Subtype\Organization\Organization::class,
            $row->getParty()->getSubtype()
        );
        $this->assertInstanceOf(
            \Ruga\Party\Subtype\Organization\Organization::class,
            $row->getParty()->getSubtypeOrganization()
        );
    }
    
    
    
    public function testCanSetAndReadAddressAttributes(): void
    {
        $t = new \Ruga\Party\Tenant\TenantTable($this->getAdapter());
        /** @var \Ruga\Party\Tenant\Tenant $row */
        $row = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $row->first_name = $first_name;
        $row->last_name = $last_name;
        $address = $row->getAddress();
        $this->assertInstanceOf(\Ruga\Contact\Subtype\Address\Address::class, $address);
        $address->address1 = 'Strasse 77';
        $row->save();
        $id1 = $row->id;
        
        unset($row);
        $row = $t->findById($id1)->current();
        $this->assertSame('Strasse 77', $row->getAddress()->address1);
    }
    
    
    
    public function testCanGetContentsAsArray(): void
    {
        $t = new \Ruga\Party\Tenant\TenantTable($this->getAdapter());
        /** @var \Ruga\Party\Tenant\Tenant $row */
        $row = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $row->first_name = $first_name;
        $row->last_name = $last_name;
        $address = $row->getAddress();
        $this->assertInstanceOf(\Ruga\Contact\Subtype\Address\Address::class, $address);
        $address->address1 = 'Strasse 77';
        $row->save();
        $id1 = $row->id;
        unset($row);
        
        $row = $t->createRow();
        $name = 'Meier AG';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $row->name = $name;
        $row->date_of_establishment = new \DateTimeImmutable();
        $row->save();
        $id2 = $row->id;
        
        /** @var \Ruga\Party\Customer\Customer $row */
        $row = $t->findById($id2)->current();
        $a = $row->toArray();
        $this->assertIsArray($a);
        $this->assertSame('Meier AG', $a['name']);
        print_r($a);
    }
    
    
    
    public function testCanSetAddressAttributesInCustomerObject(): void
    {
        $t = new \Ruga\Party\Tenant\TenantTable($this->getAdapter());
        /** @var \Ruga\Party\Tenant\Tenant $row */
        
        $row = $t->createRow();
        $name = 'Meier AG';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $row->name = $name;
        $row->date_of_establishment = new \DateTimeImmutable();
        $row->address1 = 'Musterstrasse 17a';
        $row->save();
        $id2 = $row->id;
        unset($row);
        
        $row = $t->findById($id2)->current();
        $a = $row->toArray();
        $this->assertIsArray($a);
        $this->assertSame('Musterstrasse 17a', $a['address1']);
        $this->assertSame('Musterstrasse 17a', $row->address1);
        print_r($a);
    }
    
}

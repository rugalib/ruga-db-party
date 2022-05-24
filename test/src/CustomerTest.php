<?php

declare(strict_types=1);

namespace Ruga\Party\Test;

use Laminas\ServiceManager\ServiceManager;
use Ruga\Party\Customer\Customer;
use Ruga\Party\Party;

/**
 * @author Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class CustomerTest extends \Ruga\Party\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanCreateCustomer(): void
    {
        $t = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var Customer $customer */
        $customer = $t->createRow();
        $this->assertInstanceOf(Customer::class, $customer);
        $customer->save();
        
        $customer = $t->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $this->assertInstanceOf(\Ruga\Party\Subtype\Person\Person::class, $customer->getParty()->getSubtype());
        $customer->save();
        
        $customer = $t->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $this->assertInstanceOf(
            \Ruga\Party\Subtype\Organization\Organization::class,
            $customer->getParty()->getSubtype()
        );
        $customer->save();
    }
    
    
    
    public function testCanWriteCustomer(): void
    {
        $t = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var Customer $customer */
        $customer = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $customer_number = uniqid();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $customer->customer_number = $customer_number;
        $customer->first_name = $first_name;
        $customer->last_name = $last_name;
        $this->assertSame($first_name, $customer->first_name);
        $this->assertSame($last_name, $customer->last_name);
        $this->assertSame("{$first_name} {$last_name}", $customer->fullname);
        $customer->save();
        
        $customer = $t->createRow();
        $name = 'Meier AG';
        $customer_number = uniqid();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $customer->customer_number = $customer_number;
        $customer->name = $name;
        $customer->date_of_establishment = new \DateTimeImmutable();
        $this->assertSame($name, $customer->name);
        $this->assertSame("{$name}", $customer->fullname);
        $customer->save();
    }
    
    
    
    public function testCanReadCustomer(): void
    {
        $t = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        /** @var Customer $customer */
        $customer = $t->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $customer->customer_number = '23456';
        $customer->first_name = 'Hans';
        $customer->last_name = 'Müller';
        $customer->save();
        $id1 = $customer->id;
        
        $customer = $t->createRow();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $customer->customer_number = '34567';
        $customer->name = 'Meier AG';
        $customer->date_of_establishment = new \DateTimeImmutable();
        $customer->save();
        $id2 = $customer->id;
        
        
        unset($customer);
        $customer = $t->findById($id1)->current();
        $this->assertSame('Hans', $customer->first_name);
        $this->assertSame('Hans Müller', $customer->fullname);
        
        unset($customer);
        $customer = $t->findById($id2)->current();
        $this->assertInstanceOf(
            \Ruga\Party\Subtype\Organization\Organization::class,
            $customer->getParty()->getSubtype()
        );
        $this->assertInstanceOf(
            \Ruga\Party\Subtype\Organization\Organization::class,
            $customer->getParty()->getSubtypeOrganization()
        );
    }
    
    
    
    public function testCanSetAndReadAddressAttributes(): void
    {
        $t = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var Customer $customer */
        $customer = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $customer_number = uniqid();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $customer->customer_number = $customer_number;
        $customer->first_name = $first_name;
        $customer->last_name = $last_name;
        $address = $customer->getAddress();
        $this->assertInstanceOf(\Ruga\Contact\Subtype\Address\Address::class, $address);
        $address->address1 = 'Strasse 77';
        $customer->save();
        $id1 = $customer->id;
        
        unset($customer);
        $customer = $t->findById($id1)->current();
        $this->assertSame('Strasse 77', $customer->getAddress()->address1);
    }
    
    
    
    public function testCanGetContentsAsArray(): void
    {
        $t = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var Customer $customer */
        $customer = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $customer_number = uniqid();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $customer->customer_number = $customer_number;
        $customer->first_name = $first_name;
        $customer->last_name = $last_name;
        $address = $customer->getAddress();
        $this->assertInstanceOf(\Ruga\Contact\Subtype\Address\Address::class, $address);
        $address->address1 = 'Strasse 77';
        $customer->save();
        $id1 = $customer->id;
        unset($customer);
        
        $customer = $t->createRow();
        $name = 'Meier AG';
        $customer_number = uniqid();
        $customer->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $customer->customer_number = $customer_number;
        $customer->name = $name;
        $customer->date_of_establishment = new \DateTimeImmutable();
        $customer->save();
        $id2 = $customer->id;
        
        /** @var Customer $row */
        $row = $t->findById($id2)->current();
        $a = $row->toArray();
        $this->assertIsArray($a);
        $this->assertSame('Meier AG', $a['name']);
        print_r($a);
    }
    
    
    
    public function testCanSetAddressAttributesInCustomerObject(): void
    {
        $t = new \Ruga\Party\Customer\CustomerTable($this->getAdapter());
        
        /** @var Customer $row */
        $row = $t->createRow();
        $name = 'Meier AG';
        $customer_number = uniqid();
        $row->party_subtype = \Ruga\Party\PartySubtypeType::ORGANIZATION;
        $row->customer_number = $customer_number;
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

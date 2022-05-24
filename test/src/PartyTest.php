<?php

declare(strict_types=1);

namespace Ruga\Party\Test;

use Laminas\ServiceManager\ServiceManager;
use Ruga\Party\Party;

/**
 * @author                 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyTest extends \Ruga\Party\Test\PHPUnit\AbstractTestSetUp
{
    /**
     * Creates new parties and saves them without setting data.
     */
    public function testCanCreateParty()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        /** @var Party $party */
        $party = $partyTable->createRow();
        $this->assertInstanceOf(\Ruga\Party\Link\AbstractLinkParty::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\SubtypeRowInterface::class, $party->getSubtype());
        $party->save();
        
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::ORGANIZATION]);
        $this->assertInstanceOf(\Ruga\Party\Link\Organization\PartyHasOrganization::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\Organization\Organization::class, $party->getSubtype());
        $party->save();
        
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::PERSON]);
        $this->assertInstanceOf(\Ruga\Party\Link\Person\PartyHasPerson::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\Person\Person::class, $party->getSubtype());
        $party->save();
    }
    
    
    
    /**
     * Create new parties and set the name
     */
    public function testCanWriteParty()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        /** @var Party $party */
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::ORGANIZATION]);
        $this->assertInstanceOf(\Ruga\Party\Link\Organization\PartyHasOrganization::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\Organization\Organization::class, $party->getSubtype());
        $name = 'Muster AG';
        $party->name = $name;
        $this->assertSame($name, $party->name);
        $this->assertSame($name, $party->fullname);
        $party->save();
        
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::PERSON]);
        $this->assertInstanceOf(\Ruga\Party\Link\Person\PartyHasPerson::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\Person\Person::class, $party->getSubtype());
        $first_name = 'Hans';
        $last_name = 'Müller';
        $party->first_name = $first_name;
        $party->last_name = $last_name;
        $this->assertSame($first_name, $party->first_name);
        $this->assertSame($last_name, $party->last_name);
        $this->assertSame("{$first_name} {$last_name}", $party->fullname);
        $party->save();
    }
    
    
    
    public function testCanReadParty()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        /** @var Party $party */
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::ORGANIZATION]);
        $party->name = 'Muster AG';
        $party->save();
        $id1 = $party->id;
        
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::PERSON]);
        $party->first_name = 'Hans';
        $party->last_name = 'Müller';
        $party->save();
        $id2 = $party->id;
        
        unset($party);
        $party = $partyTable->findById($id1)->current();
        $this->assertSame('Muster AG', $party->name);
        
        unset($party);
        $party = $partyTable->findById($id2)->current();
        $this->assertSame('Hans Müller', $party->fullname);
    }
    
    
    
    public function testCanGetContentsAsArray()
    {
        $t = new \Ruga\Party\PartyTable($this->getAdapter());
        
        /** @var Party $row */
        $row = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $row->first_name = $first_name;
        $row->last_name = $last_name;
//        $address = $row->getAddress();
//        $this->assertInstanceOf(\Ruga\Address\Address::class, $address);
//        $address->address1 = 'Strasse 77';
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
        
        $row = $t->findById($id2)->current();
        $a = $row->toArray();
        $this->assertIsArray($a);
        print_r($a);
        $this->assertSame('Meier AG', $a['name']);
    }
    
    
    
    public function testCanDeleteParty()
    {
        $t = new \Ruga\Party\PartyTable($this->getAdapter());
        
        /** @var \Ruga\Db\Adapter\Adapter $a */
        $a = $this->getAdapter();
        
        /** @var Party $row */
        $row = $t->createRow();
        $first_name = 'Hans';
        $last_name = 'Müller';
        $row->party_subtype = \Ruga\Party\PartySubtypeType::PERSON;
        $row->first_name = $first_name;
        $row->last_name = $last_name;
        $row->save();
        $id1 = $row->id;
        
        
        /** @var \Ruga\Contact\AbstractContactMechanism $cm */
        $cm = (new \Ruga\Contact\ContactMechanismTable($this->getAdapter()))->createRow();
        $cm->contactmechanism_type = \Ruga\Contact\ContactMechanismType::POSTAL_ADDRESS;
        $cm->linkTo($row);
        $cm->address1 = 'Strasse 77';
        $cm->save();
        
        $cm = (new \Ruga\Contact\ContactMechanismTable($this->getAdapter()))->createRow();
        $cm->contactmechanism_type = \Ruga\Contact\ContactMechanismType::EMAIL;
        $cm->linkTo($row);
        $cm->address = 'test@easy-smart.ch';
        $cm->save();
        
        unset($row);
        
        $row = $t->findById($id1)->current();
        $this->assertInstanceOf(Party::class, $row);
        
        $i = $row->delete();
        $this->assertSame(1, $i);
    }
    
    
    
    public function testCanCreatePartyWithContactMechanisms()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        /** @var Party $party */
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::ORGANIZATION]);
        $this->assertInstanceOf(\Ruga\Party\Link\Organization\PartyHasOrganization::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\Organization\Organization::class, $party->getSubtype());
        $name = 'Muster AG';
        $party->name = $name;
        
        $cm = (new \Ruga\Contact\ContactMechanismTable($this->getAdapter()))->createRow();
        $cm->contactmechanism_type = \Ruga\Contact\ContactMechanismType::EMAIL;
        $cm->linkTo($party);
        $cm->address = 'test@easy-smart.ch';
        
        $this->assertSame($name, $party->name);
        $this->assertSame($name, $party->fullname);
        $party->save();
    }
    
    
    
    public function testCanCreatePartyWithContactMechanisms2()
    {
        $partyTable = new \Ruga\Party\PartyTable($this->getAdapter());
        /** @var Party $party */
        $party = $partyTable->createRow(['party_subtype' => \Ruga\Party\PartySubtypeType::ORGANIZATION]);
        $this->assertInstanceOf(\Ruga\Party\Link\Organization\PartyHasOrganization::class, $party->getSubtypeLink());
        $this->assertInstanceOf(\Ruga\Party\Subtype\Organization\Organization::class, $party->getSubtype());
        $name = 'Muster AG';
        $party->name = $name;
        
        $cm = $party->createContactMechanism(
            \Ruga\Contact\ContactMechanismType::EMAIL()
        );
        $cm->address = 'test2@easy-smart.ch';
        
        $this->assertSame($name, $party->name);
        $this->assertSame($name, $party->fullname);
        $party->save();
    }
    
}

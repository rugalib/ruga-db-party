<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Test;

use Ruga\Party\PartyTable;

/**
 * @author Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyTableTest extends \Ruga\Party\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanCreateNewPartyTable(): void
    {
        $t = new PartyTable($this->getAdapter());
        $this->assertInstanceOf(\Ruga\Party\PartyTable::class, $t);
    }
    
    
    
    public function testCanCreateNewParty(): void
    {
        $t = new PartyTable($this->getAdapter());
        $party = $t->createRow();
        $this->assertInstanceOf(\Ruga\Party\Party::class, $party);
    }
    
}

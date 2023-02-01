<?php

declare(strict_types=1);

namespace Ruga\Party\EntityHasParty;

use Ruga\Db\Table\AbstractRugaTable;

abstract class AbstractEntityHasPartyTable extends AbstractRugaTable
{
    const TABLENAME = 'Entity_has_Party';
    const PRIMARYKEY = ['Entity_id', 'Party_id'];
    const ROWCLASS = AbstractEntityHasParty::class;
}
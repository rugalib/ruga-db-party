<?php

declare(strict_types=1);

namespace Ruga\Party;

/**
 * The party table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyTable extends AbstractPartyTable
{
    const TABLENAME = 'Party';
    const PRIMARYKEY = ['id'];
    const ROWCLASS = Party::class;
}

<?php

declare(strict_types=1);

namespace Ruga\Party\Relationship;

/**
 * The party - party link table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyHasPartyTable extends \Ruga\Party\Link\AbstractLinkPartyTable
{
    const TABLENAME = 'Party_has_Party';
    const PRIMARYKEY = ['id'];
    const ROWCLASS = PartyHasParty::class;
}
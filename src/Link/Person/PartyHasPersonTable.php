<?php

declare(strict_types=1);

namespace Ruga\Party\Link\Person;

/**
 * The person - party link table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyHasPersonTable extends \Ruga\Party\Link\AbstractLinkPartyTable
{
    const TABLENAME = 'Party_has_Person';
    const PRIMARYKEY = ['Party_id', 'Person_id'];
    const ROWCLASS = PartyHasPerson::class;
}
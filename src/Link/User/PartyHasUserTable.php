<?php

declare(strict_types=1);

namespace Ruga\Party\Link\User;

/**
 * The user - party link table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyHasUserTable extends \Ruga\Party\Link\AbstractLinkPartyTable
{
    const TABLENAME = 'Party_has_User';
    const PRIMARYKEY = ['Party_id', 'User_id'];
    const ROWCLASS = PartyHasUser::class;
}
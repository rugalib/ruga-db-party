<?php

declare(strict_types=1);

namespace Ruga\Party\Link\Organization;

/**
 * The organization - party link table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PartyHasOrganizationTable extends \Ruga\Party\Link\AbstractLinkPartyTable
{
    const TABLENAME = 'Party_has_Organization';
    const PRIMARYKEY = ['Party_id', 'Organization_id'];
    const ROWCLASS = PartyHasOrganization::class;
}
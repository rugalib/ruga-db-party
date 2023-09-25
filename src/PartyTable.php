<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

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

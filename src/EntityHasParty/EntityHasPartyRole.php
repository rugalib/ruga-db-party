<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\EntityHasParty;

/**
 * Class EntityHasPartyRole.
 *
 * @see AbstractEntityHasParty::$party_role
 *
 * @method static EntityHasPartyRole UNKNOWN()
 */
class EntityHasPartyRole extends \Ruga\Std\Enum\AbstractEnum
{
    const UNKNOWN = 'UNKNOWN';
}
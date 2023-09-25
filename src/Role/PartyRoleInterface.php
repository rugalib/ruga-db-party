<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Role;

use Ruga\Party\AbstractParty;

/**
 * Interface PartyRoleInterface
 *
 * @property int $Party_id Foreign key of the party
 */
interface PartyRoleInterface
{
    /**
     * Return the party object.
     *
     * @return AbstractParty
     * @throws \ReflectionException
     */
    public function getParty(): AbstractParty;
}
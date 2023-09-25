<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\EntityHasParty;

use Ruga\Db\Row\RowAttributesInterface;

/**
 * Interface EntityHasPartyAttributesInterface
 *
 * @property int                $Entity_id              Foreign key to the entity object
 * @property int                $Party_id               Foreign key to the PARTY
 * @property EntityHasPartyRole $party_role             Role of the PARTY in the entity object
 * @property string|null        $remark                 Remark
 */
interface EntityHasPartyAttributesInterface extends RowAttributesInterface
{
    
}

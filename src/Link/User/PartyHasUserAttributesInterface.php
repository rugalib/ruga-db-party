<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Link\User;

use Ruga\Party\Link\AbstractSubtypeLinkAttributesInterface;

/**
 * Interface PartyHasUserAttributesInterface
 *
 * @property int    $User_id           Primary key / foreign key to User
 * @property string $remark            Remark
 */
interface PartyHasUserAttributesInterface extends AbstractSubtypeLinkAttributesInterface
{
    
}
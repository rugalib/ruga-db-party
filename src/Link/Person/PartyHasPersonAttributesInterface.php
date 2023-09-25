<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Link\Person;

use Ruga\Party\Link\AbstractSubtypeLinkAttributesInterface;

/**
 * Interface PartyHasPersonAttributesInterface
 *
 * @property int    $Person_id         Primary key / foreign key to Person
 * @property array  $person_role       Role of the person
 * @property string $remark            Remark
 */
interface PartyHasPersonAttributesInterface extends AbstractSubtypeLinkAttributesInterface
{
    
}
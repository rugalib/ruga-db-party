<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Subtype\Person;

use Ruga\Party\Subtype\SubtypeTableInterface;
use Ruga\Party\Subtype\SubtypeTableTrait;

class PersonTable extends \Ruga\Person\PersonTable implements SubtypeTableInterface
{
    const ROWCLASS = Person::class;

//    use SubtypeTableTrait;
}
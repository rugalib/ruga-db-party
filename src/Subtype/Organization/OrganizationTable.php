<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Subtype\Organization;

use Ruga\Party\Subtype\SubtypeTableInterface;
use Ruga\Party\Subtype\SubtypeTableTrait;

class OrganizationTable extends \Ruga\Organization\OrganizationTable implements SubtypeTableInterface
{
    const ROWCLASS = Organization::class;
    
//    use SubtypeTableTrait;
}
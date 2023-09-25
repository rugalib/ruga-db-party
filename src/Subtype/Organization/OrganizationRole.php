<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Subtype\Organization;

use Ruga\Std\Enum\AbstractEnum;

/**
 * Defines the types of ORGANIZATION roles available.
 *
 * @method static self PARTNER()
 * @method static self DEPARTMENT()
 */
class OrganizationRole extends AbstractEnum
{
    const PARTNER = 'PARTNER';
    const DEPARTMENT = 'DEPARTMENT';
    
}


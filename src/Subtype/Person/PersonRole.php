<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Subtype\Person;

use Ruga\Std\Enum\AbstractEnum;

/**
 * Defines the types of PERSON roles available.
 *
 * @method static self CONTACT()
 * @method static self EMPLOYEE()
 */
class PersonRole extends AbstractEnum
{
    const CONTACT = 'CONTACT';
    const EMPLOYEE = 'EMPLOYEE';
    
}


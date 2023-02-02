<?php

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


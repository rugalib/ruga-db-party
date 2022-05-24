<?php

declare(strict_types=1);

namespace Ruga\Party\Subtype\Organization;

use Ruga\Party\Subtype\SubtypeTableInterface;
use Ruga\Party\Subtype\SubtypeTableTrait;

class OrganizationTable extends \Ruga\Organization\OrganizationTable implements SubtypeTableInterface
{
    const ROWCLASS = Organization::class;
    
//    use SubtypeTableTrait;
}
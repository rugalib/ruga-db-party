<?php

declare(strict_types=1);

namespace Ruga\Party\Subtype\Person;

use Ruga\Party\Subtype\SubtypeTableInterface;
use Ruga\Party\Subtype\SubtypeTableTrait;

class PersonTable extends \Ruga\Person\PersonTable implements SubtypeTableInterface
{
    const ROWCLASS = Person::class;

//    use SubtypeTableTrait;
}
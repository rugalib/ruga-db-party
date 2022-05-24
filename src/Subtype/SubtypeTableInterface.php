<?php

declare(strict_types=1);

namespace Ruga\Party\Subtype;

use Ruga\Contact\AbstractContactMechanism;
use Ruga\Db\Table\TableInterface;

interface SubtypeTableInterface extends TableInterface
{
    /**
     * Find the subtype row associated to the contact mechanism.
     * If the row does not exist, create a new one.
     *
     * @param AbstractContactMechanism $contactMechanism
     *
     * @return SubtypeRowInterface|\Ruga\Db\Row\RowInterface
     * @throws \ReflectionException
     */
//    public function findOrCreateRowByContactMechanism(AbstractContactMechanism $contactMechanism): SubtypeRowInterface;
}
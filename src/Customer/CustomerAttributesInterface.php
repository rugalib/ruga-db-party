<?php

declare(strict_types=1);

namespace Ruga\Party\Customer;

use Ruga\Db\Row\RowAttributesInterface;

/**
 * Interface CustomerAttributesInterface
 *
 * @property int    $id                        Primary Key
 * @property string $fullname                  Full name / display name
 * @property string $customer_number           Customer number
 * @property int    $Party_id                  Foreign key of the party
 * @property string $remark                    Remark
 */
interface CustomerAttributesInterface extends RowAttributesInterface
{
    
}
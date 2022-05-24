<?php

declare(strict_types=1);

namespace Ruga\Party\Customer;

/**
 * The customer table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class CustomerTable extends AbstractCustomerTable
{
    const TABLENAME = 'Customer';
    const PRIMARYKEY = ['id'];
//    const RESULTSETCLASS = ;
    const ROWCLASS = Customer::class;
}

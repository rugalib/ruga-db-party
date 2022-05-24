<?php

declare(strict_types=1);

namespace Ruga\Party\Tenant;

/**
 * The tenant table.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class TenantTable extends AbstractTenantTable
{
    const TABLENAME = 'Tenant';
    const PRIMARYKEY = ['id'];
    const ROWCLASS = Tenant::class;
}

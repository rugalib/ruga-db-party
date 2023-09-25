<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

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

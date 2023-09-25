<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Tenant;

use Ruga\Party\Role\PartyRoleInterface;

/**
 * Interface to a tenant.
 *
 * @see      Tenant
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
interface TenantInterface extends PartyRoleInterface
{

}

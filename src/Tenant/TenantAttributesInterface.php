<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Tenant;

use Ruga\Db\Row\RowAttributesInterface;

/**
 * Interface TenantAttributesInterface
 *
 * @property int    $id                        Primary Key
 * @property string $fullname                  Full name / display name
 * @property int    $Party_id                  Foreign key of the party
 * @property string $remark                    Remark
 */
interface TenantAttributesInterface extends RowAttributesInterface
{
    
}
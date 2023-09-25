<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Link\Organization;

use Ruga\Party\Link\AbstractSubtypeLinkAttributesInterface;

/**
 * Interface PartyHasOrganizationAttributesInterface
 *
 * @property int    $Organization_id   Primary key / foreign key to Organization
 * @property array  $organization_role Role of the organization
 * @property string $remark            Remark
 */
interface PartyHasOrganizationAttributesInterface extends AbstractSubtypeLinkAttributesInterface
{
    
}
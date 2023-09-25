<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Party\Link;

use Ruga\Db\Row\RowAttributesInterface;

/**
 * Interface AbstractSubtypeLinkAttributesInterface
 *
 * @property int    $Party_id                        Primary key / foreign key to Party
 * @property int    $Subtype_id                      Primary key of the subtype
 * @property string $remark                          Remark
 */
interface AbstractSubtypeLinkAttributesInterface extends RowAttributesInterface
{
    
}
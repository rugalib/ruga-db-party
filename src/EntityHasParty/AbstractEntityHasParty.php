<?php

declare(strict_types=1);

namespace Ruga\Party\EntityHasParty;

use Ruga\Db\Row\AbstractRugaRow;
use Ruga\Db\Row\Feature\FullnameFeatureRowInterface;

/**
 * Abstract EntityHasParty.
 *
 * @see      EntityHasPartyAttributesInterface
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class AbstractEntityHasParty extends AbstractRugaRow implements EntityHasPartyAttributesInterface,
                                                                         EntityHasPartyInterface,
                                                                         FullnameFeatureRowInterface
{
}

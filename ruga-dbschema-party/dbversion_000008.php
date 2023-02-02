<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);

if ($partyRole_values = implode("','", \Ruga\Party\Role\PartyRole::getConstants())) {
    $partyRole_values = "'{$partyRole_values}'";
}
//$partyRole_default = \Ruga\Party\Role\PartyRole::__default;


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$partyTable}` CHANGE COLUMN `party_role` `party_role` SET({$partyRole_values}) NULL DEFAULT NULL AFTER `fullname`;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

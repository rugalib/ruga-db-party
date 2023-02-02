<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);

if ($partySubtype_values = implode("','", \Ruga\Party\PartySubtypeType::getConstants())) {
    $partySubtype_values = "'{$partySubtype_values}'";
}
$partySubtype_default = \Ruga\Party\PartySubtypeType::__default;

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$partyTable}` ADD COLUMN `party_subtype` ENUM({$partySubtype_values}) NOT NULL DEFAULT '{$partySubtype_default}' AFTER `party_role`;
ALTER TABLE `{$partyTable}` ADD INDEX `{$partyTable}_party_subtype_idx` (`party_subtype`);
SET FOREIGN_KEY_CHECKS = 1;

SQL;

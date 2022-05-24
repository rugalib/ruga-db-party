<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$person = $resolver->getTableName(\Ruga\Party\Subtype\Person\PersonTable::class);
$user = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasperson = $resolver->getTableName(\Ruga\Party\Link\Person\PartyHasPersonTable::class);

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$party}` ADD COLUMN `party_subtype` ENUM('PERSON','ORGANIZATION') NOT NULL DEFAULT 'ORGANIZATION' AFTER `party_role`;
ALTER TABLE `{$party}` ADD INDEX `{$party}_party_subtype_idx` (`party_subtype`);
SET FOREIGN_KEY_CHECKS = 1;

SQL;

<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$party}` CHANGE COLUMN `party_role` `party_role` SET('CUSTOMER','SUPPLIER','PROSPECT','SHAREHOLDER','TENANT') NULL DEFAULT NULL AFTER `fullname`;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

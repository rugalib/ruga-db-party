<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$customerTable = $resolver->getTableName(\Ruga\Party\Customer\CustomerTable::class);
$tenantTable = $resolver->getTableName(\Ruga\Party\Tenant\TenantTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$partyTable}` ADD COLUMN `Tenant_id` INT NULL DEFAULT NULL AFTER `party_subtype`;
ALTER TABLE `{$partyTable}` ADD INDEX `fk_{$partyTable}_Tenant_id_idx` (`Tenant_id`);
ALTER TABLE `{$partyTable}` ADD CONSTRAINT `fk_{$partyTable}_Tenant_id` FOREIGN KEY (`Tenant_id`) REFERENCES `{$tenantTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `{$customerTable}` ADD COLUMN `Tenant_id` INT NULL DEFAULT NULL AFTER `Party_id`;
ALTER TABLE `{$customerTable}` ADD INDEX `fk_{$customerTable}_Tenant_id_idx` (`Tenant_id`);
ALTER TABLE `{$customerTable}` ADD CONSTRAINT `fk_{$customerTable}_Tenant_id` FOREIGN KEY (`Tenant_id`) REFERENCES `{$tenantTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

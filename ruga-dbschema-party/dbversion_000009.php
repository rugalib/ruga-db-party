<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$customer = $resolver->getTableName(\Ruga\Party\Customer\CustomerTable::class);
$tenant = $resolver->getTableName(\Ruga\Party\Tenant\TenantTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$party}` ADD COLUMN `Tenant_id` INT NULL DEFAULT NULL AFTER `party_subtype`;
ALTER TABLE `{$party}` ADD INDEX `fk_{$party}_Tenant_id_idx` (`Tenant_id`);
ALTER TABLE `{$party}` ADD CONSTRAINT `fk_{$party}_Tenant_id` FOREIGN KEY (`Tenant_id`) REFERENCES `{$tenant}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `{$customer}` ADD COLUMN `Tenant_id` INT NULL DEFAULT NULL AFTER `Party_id`;
ALTER TABLE `{$customer}` ADD INDEX `fk_{$customer}_Tenant_id_idx` (`Tenant_id`);
ALTER TABLE `{$customer}` ADD CONSTRAINT `fk_{$customer}_Tenant_id` FOREIGN KEY (`Tenant_id`) REFERENCES `{$tenant}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

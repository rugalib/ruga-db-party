<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$tenantTable = $resolver->getTableName(\Ruga\Party\Tenant\TenantTable::class);


return <<<"SQL"
SET FOREIGN_KEY_CHECKS = 0;
INSERT INTO `{$tenantTable}` (`id`, `fullname`, `Party_id`, `created`, `createdBy`, `changed`, `changedBy`) VALUES
('1', 'SYSTEM', null, NOW(), '1', NOW(), '1');
SET FOREIGN_KEY_CHECKS = 1;
SQL;

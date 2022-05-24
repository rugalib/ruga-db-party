<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$customer = $resolver->getTableName(\Ruga\Party\Customer\CustomerTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$customer}` ADD UNIQUE `{$customer}_customer_number_UNIQUE` (`customer_number`);
SET FOREIGN_KEY_CHECKS = 1;

SQL;

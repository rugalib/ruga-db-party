<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$customer = $resolver->getTableName(\Ruga\Party\Customer\CustomerTable::class);
$user = $resolver->getTableName(\Ruga\User\UserTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$customer}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(255) NULL,
  `customer_number` VARCHAR(255) NULL,
  `Party_id` INT NOT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `{$customer}_fullname_idx` (`fullname`),
  INDEX `fk_{$customer}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$customer}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$customer}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$customer}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$party}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$customer}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$customer}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE=InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

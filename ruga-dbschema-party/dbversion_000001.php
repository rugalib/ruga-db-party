<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$customerTable = $resolver->getTableName(\Ruga\Party\Customer\CustomerTable::class);
$userTable = $resolver->getTableName(\Ruga\User\UserTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$customerTable}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(190) NULL,
  `customer_number` VARCHAR(190) NULL,
  `Party_id` INT NOT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `{$customerTable}_fullname_idx` (`fullname`),
  INDEX `fk_{$customerTable}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$customerTable}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$customerTable}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$customerTable}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$partyTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$customerTable}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$customerTable}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE=InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

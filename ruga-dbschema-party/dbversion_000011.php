<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$userTable = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasuserTable = $resolver->getTableName(\Ruga\Party\Link\User\PartyHasUserTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhasuserTable}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Party_id` INT NOT NULL,
  `User_id` INT NOT NULL,
  `valid_from` DATETIME NULL DEFAULT NULL,
  `valid_thru` DATETIME NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `{$partyhasuserTable}_valid_from_idx` (`valid_from`),
  INDEX `{$partyhasuserTable}_valid_thru_idx` (`valid_thru`),
  INDEX `fk_{$partyhasuserTable}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$partyhasuserTable}_User_id_idx` (`User_id` ASC),
  INDEX `fk_{$partyhasuserTable}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhasuserTable}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhasuserTable}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$partyTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasuserTable}_User_id` FOREIGN KEY (`User_id`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasuserTable}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasuserTable}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

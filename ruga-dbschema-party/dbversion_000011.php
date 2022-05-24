<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$user = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasuser = $resolver->getTableName(\Ruga\Party\Link\User\PartyHasUserTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhasuser}` (
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
  INDEX `{$partyhasuser}_valid_from_idx` (`valid_from`),
  INDEX `{$partyhasuser}_valid_thru_idx` (`valid_thru`),
  INDEX `fk_{$partyhasuser}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$partyhasuser}_User_id_idx` (`User_id` ASC),
  INDEX `fk_{$partyhasuser}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhasuser}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhasuser}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$party}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasuser}_User_id` FOREIGN KEY (`User_id`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasuser}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasuser}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

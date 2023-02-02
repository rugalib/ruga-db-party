<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$userTable = $resolver->getTableName(\Ruga\User\UserTable::class);

if ($partyRole_values = implode("','", \Ruga\Party\Role\PartyRole::getConstants())) {
    $partyRole_values = "'{$partyRole_values}'";
}


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyTable}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(190) NULL,
  `party_role` SET({$partyRole_values}) NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `{$partyTable}_fullname_idx` (`fullname`),
  INDEX `{$partyTable}_party_role_idx` (`party_role`),
  INDEX `fk_{$partyTable}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyTable}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyTable}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyTable}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE=InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

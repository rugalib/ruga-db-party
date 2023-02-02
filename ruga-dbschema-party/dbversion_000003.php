<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$personTable = $resolver->getTableName(\Ruga\Party\Subtype\Person\PersonTable::class);
$userTable = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhaspersonTable = $resolver->getTableName(\Ruga\Party\Link\Person\PartyHasPersonTable::class);

if ($personRole_values = implode("','", \Ruga\Party\Subtype\Person\PersonRole::getConstants())) {
    $personRole_values = "'{$personRole_values}'";
}

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhaspersonTable}` (
  `Party_id` INT NOT NULL,
  `Person_id` INT NOT NULL,
  `person_role` SET({$personRole_values}) NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`Party_id`, `Person_id`),
  INDEX `fk_{$partyhaspersonTable}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$partyhaspersonTable}_Person_id_idx` (`Person_id` ASC),
  INDEX `fk_{$partyhaspersonTable}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhaspersonTable}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhaspersonTable}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$partyTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhaspersonTable}_Person_id` FOREIGN KEY (`Person_id`) REFERENCES `{$personTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhaspersonTable}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhaspersonTable}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$person = $resolver->getTableName(\Ruga\Party\Subtype\Person\PersonTable::class);
$user = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasperson = $resolver->getTableName(\Ruga\Party\Link\Person\PartyHasPersonTable::class);

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhasperson}` (
  `Party_id` INT NOT NULL,
  `Person_id` INT NOT NULL,
  `person_role` SET('CONTACT', 'EMPLOYEE') NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`Party_id`, `Person_id`),
  INDEX `fk_{$partyhasperson}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$partyhasperson}_Person_id_idx` (`Person_id` ASC),
  INDEX `fk_{$partyhasperson}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhasperson}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhasperson}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$party}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasperson}_Person_id` FOREIGN KEY (`Person_id`) REFERENCES `{$person}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasperson}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasperson}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

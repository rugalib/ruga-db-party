<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$userTable = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhaspartyTable = $resolver->getTableName(\Ruga\Party\Relationship\PartyHasPartyTable::class);

if ($relationshipType_values = implode("','", \Ruga\Party\Relationship\PartyRelationshipType::getConstants())) {
    $relationshipType_values = "'{$relationshipType_values}'";
}

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhaspartyTable}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Party1_id` INT NOT NULL,
  `Party2_id` INT NOT NULL,
  `relationship_type` ENUM({$relationshipType_values}) NOT NULL,
  `valid_from` DATETIME NULL DEFAULT NULL,
  `valid_thru` DATETIME NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `{$partyhaspartyTable}_relationship_type_idx` (`relationship_type`),
  INDEX `{$partyhaspartyTable}_valid_from_idx` (`valid_from`),
  INDEX `{$partyhaspartyTable}_valid_thru_idx` (`valid_thru`),
  INDEX `fk_{$partyhaspartyTable}_Party1_id_idx` (`Party1_id` ASC),
  INDEX `fk_{$partyhaspartyTable}_Party2_id_idx` (`Party2_id` ASC),
  INDEX `fk_{$partyhaspartyTable}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhaspartyTable}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhaspartyTable}_Party1_id` FOREIGN KEY (`Party1_id`) REFERENCES `{$partyTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhaspartyTable}_Party2_id` FOREIGN KEY (`Party2_id`) REFERENCES `{$partyTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhaspartyTable}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhaspartyTable}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

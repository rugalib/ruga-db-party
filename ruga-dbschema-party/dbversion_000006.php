<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$user = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasparty = $resolver->getTableName(\Ruga\Party\Relationship\PartyHasPartyTable::class);


return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhasparty}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Party1_id` INT NOT NULL,
  `Party2_id` INT NOT NULL,
  `relationship_type` ENUM('CUSTOMER', 'EMPLOYEE', 'CONTRACTOR', 'SUPPLIER', 'CONTACT', 'DISTRIBUTOR', 'PARTNER', 'ORGANIZATION_UNIT') NOT NULL,
  `valid_from` DATETIME NULL DEFAULT NULL,
  `valid_thru` DATETIME NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `{$partyhasparty}_relationship_type_idx` (`relationship_type`),
  INDEX `{$partyhasparty}_valid_from_idx` (`valid_from`),
  INDEX `{$partyhasparty}_valid_thru_idx` (`valid_thru`),
  INDEX `fk_{$partyhasparty}_Party1_id_idx` (`Party1_id` ASC),
  INDEX `fk_{$partyhasparty}_Party2_id_idx` (`Party2_id` ASC),
  INDEX `fk_{$partyhasparty}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhasparty}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhasparty}_Party1_id` FOREIGN KEY (`Party1_id`) REFERENCES `{$party}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasparty}_Party2_id` FOREIGN KEY (`Party2_id`) REFERENCES `{$party}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasparty}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasparty}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

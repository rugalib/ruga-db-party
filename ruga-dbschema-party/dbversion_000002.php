<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$party = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$organization = $resolver->getTableName(\Ruga\Party\Subtype\Organization\OrganizationTable::class);
$user = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasorganization = $resolver->getTableName(\Ruga\Party\Link\Organization\PartyHasOrganizationTable::class);

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhasorganization}` (
  `Party_id` INT NOT NULL,
  `Organization_id` INT NOT NULL,
  `organization_role` SET('PARTNER', 'DEPARTMENT') NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`Party_id`, `Organization_id`),
  INDEX `fk_{$partyhasorganization}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$partyhasorganization}_Organization_id_idx` (`Organization_id` ASC),
  INDEX `fk_{$partyhasorganization}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhasorganization}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhasorganization}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$party}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasorganization}_Organization_id` FOREIGN KEY (`Organization_id`) REFERENCES `{$organization}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasorganization}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasorganization}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$user}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

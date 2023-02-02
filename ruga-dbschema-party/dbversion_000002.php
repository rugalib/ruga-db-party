<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$partyTable = $resolver->getTableName(\Ruga\Party\PartyTable::class);
$organizationTable = $resolver->getTableName(\Ruga\Party\Subtype\Organization\OrganizationTable::class);
$userTable = $resolver->getTableName(\Ruga\User\UserTable::class);
$partyhasorganizationTable = $resolver->getTableName(\Ruga\Party\Link\Organization\PartyHasOrganizationTable::class);

if ($organizationRole_values = implode("','", \Ruga\Party\Subtype\Organization\OrganizationRole::getConstants())) {
    $organizationRole_values = "'{$organizationRole_values}'";
}

return <<<"SQL"

SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `{$partyhasorganizationTable}` (
  `Party_id` INT NOT NULL,
  `Organization_id` INT NOT NULL,
  `organization_role` SET({$organizationRole_values}) NULL DEFAULT NULL,
  `remark` TEXT NULL,
  `created` DATETIME NOT NULL,
  `createdBy` INT NOT NULL,
  `changed` DATETIME NOT NULL,
  `changedBy` INT NOT NULL,
  PRIMARY KEY (`Party_id`, `Organization_id`),
  INDEX `fk_{$partyhasorganizationTable}_Party_id_idx` (`Party_id` ASC),
  INDEX `fk_{$partyhasorganizationTable}_Organization_id_idx` (`Organization_id` ASC),
  INDEX `fk_{$partyhasorganizationTable}_changedBy_idx` (`changedBy` ASC),
  INDEX `fk_{$partyhasorganizationTable}_createdBy_idx` (`createdBy` ASC),
  CONSTRAINT `fk_{$partyhasorganizationTable}_Party_id` FOREIGN KEY (`Party_id`) REFERENCES `{$partyTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasorganizationTable}_Organization_id` FOREIGN KEY (`Organization_id`) REFERENCES `{$organizationTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasorganizationTable}_changedBy` FOREIGN KEY (`changedBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_{$partyhasorganizationTable}_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `{$userTable}` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = InnoDB
;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

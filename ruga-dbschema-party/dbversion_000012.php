<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                   $comp_name
 */
$partyhaspartyTable = $resolver->getTableName(\Ruga\Party\Relationship\PartyHasPartyTable::class);

if ($partyRelationshipType_values = implode("','", \Ruga\Party\Relationship\PartyRelationshipType::getConstants())) {
    $partyRelationshipType_values = "'{$partyRelationshipType_values}'";
}

return <<<"SQL"
SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$partyhaspartyTable}` CHANGE COLUMN `relationship_type` `relationship_type` ENUM({$partyRelationshipType_values}) NOT NULL AFTER `Party2_id`;
SET FOREIGN_KEY_CHECKS = 1;
SQL;

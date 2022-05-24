<?php

declare(strict_types=1);

/**
 * @return string
 * @var \Ruga\Db\Schema\Resolver $resolver
 * @var string                    $comp_name
 */
$partyhasparty = $resolver->getTableName(\Ruga\Party\Relationship\PartyHasPartyTable::class);

if($values=implode("','", \Ruga\Party\Relationship\PartyRelationshipType::getConstants())) {
    $values="'{$values}'";
}

return <<<"SQL"
SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `{$partyhasparty}` CHANGE COLUMN `relationship_type` `relationship_type` ENUM({$values}) NOT NULL AFTER `Party2_id`;
SET FOREIGN_KEY_CHECKS = 1;
SQL;

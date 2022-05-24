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

$passwordHash = function(string $pwd): string {
    return password_hash($pwd, PASSWORD_DEFAULT);
};

return <<<"SQL"
SET FOREIGN_KEY_CHECKS = 0;

# Tenant Kaufmann AG
INSERT INTO `Party` (`fullname`, `party_role`, `party_subtype`, `Tenant_id`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Kaufmann AG', 'TENANT', 'ORGANIZATION', null, NOW(), 3, NOW(), 3);
SET @TENANT_PARTY_ID = LAST_INSERT_ID();
INSERT INTO `Tenant` (`fullname`, `Party_id`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Kaufmann AG', @TENANT_PARTY_ID, NOW(), 3, NOW(), 3);
SET @TENANT_ID = LAST_INSERT_ID();

SELECT @ADMIN_ROLE_ID:=`id` FROM `Role` WHERE `name`='admin';
SELECT @USER_ROLE_ID:=`id` FROM `Role` WHERE `name`='user';

# User Prisca Kaufmann, representative of Tenant Kaufmann AG
INSERT INTO `{$party}` (`fullname`, `party_role`, `party_subtype`, `Tenant_id`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Prisca Kaufmann', null, 'PERSON', null, NOW(), 3, NOW(), 3);
SET @PARTY_ID = LAST_INSERT_ID();
INSERT INTO `User` (username, password, fullname, email, mobile, created, createdBy, changed, changedBy) VALUES ('prisca.kaufmann', '{$passwordHash('5000')}', 'Prisca Kaufmann', 'prisca@example.com', '', NOW(), 3, NOW(), 3);
SET @USER_ID = LAST_INSERT_ID();
INSERT INTO `User_has_Role` (User_id, Role_id, created, createdBy, changed, changedBy) VALUES (@USER_ID, @USER_ROLE_ID, NOW(), 3, NOW(), 3);
INSERT INTO `Party_has_User` (Party_id, User_id, valid_from, valid_thru, created, createdBy, changed, changedBy) VALUES (@PARTY_ID, @USER_ID, null, null, NOW(), 3, NOW(), 3);
INSERT INTO `Party_has_Party` (Party1_id, Party2_id, relationship_type, valid_from, valid_thru, created, createdBy, changed, changedBy) VALUES (@PARTY_ID, @TENANT_PARTY_ID, 'REPRESENTATIVE', null, null, NOW(), 3, NOW(), 3);

SET FOREIGN_KEY_CHECKS = 1;
SQL;

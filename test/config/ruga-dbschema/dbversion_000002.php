<?php

return <<<"SQL"
SET FOREIGN_KEY_CHECKS = 0;

# Kunde Nadine Muster
INSERT INTO `Party` (`fullname`, `party_role`, `party_subtype`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Nadine Muster', 'CUSTOMER', 'PERSON', NULL, NOW(), 1, NOW(), 1);
SET @PARTY_ID = LAST_INSERT_ID();
INSERT INTO `Customer` (`fullname`, `customer_number`, `Party_id`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Nadine Muster', '12345', @PARTY_ID, NULL, NOW(), 1, NOW(), 1);
INSERT INTO `Person` (`fullname`, `salutation`, `first_name`, `title`, `honorific_prefix`, `last_name`, `honorific_suffix`, `middle_name`, `birth_name`, `religious_name`, `nick_name`, `gender`, `nationality`, `citizenship`, `migrationid`, `migrationid_until`, `religion`, `denomination`, `language`, `birth_date`, `death_date`, `birth_place`, `death_place`, `familystatus`, `spouse`, `height`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Nadine Muster', NULL, 'Nadine', NULL, NULL, 'Muster', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1977-03-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NOW(), 1, NOW(), 1);
INSERT INTO `Party_has_Person` (`Party_id`, `Person_id`, `person_role`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES (@PARTY_ID, LAST_INSERT_ID(), NULL, NULL, NOW(), 1, NOW(), 1);

# Kunde Hugentobler AG
INSERT INTO `Party` (`fullname`, `party_role`, `party_subtype`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Hugentobler AG', 'CUSTOMER', 'ORGANIZATION', NULL, NOW(), 1, NOW(), 1);
SET @PARTY_ID = LAST_INSERT_ID();
INSERT INTO `Customer` (`fullname`, `customer_number`, `Party_id`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Hugentobler AG', '67890', LAST_INSERT_ID(), NULL, NOW(), 1, NOW(), 1);
INSERT INTO `Organization` (`fullname`, `name`, `org_type`, `org_subtype`, `date_of_establishment`, `date_of_dissolution`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES ('Hugentobler AG', 'Hugentobler AG', 'LEGAL', NULL, '2010-02-27', NULL, NULL, NOW(), 1, NOW(), 1);
INSERT INTO `Party_has_Organization` (`Party_id`, `Organization_id`, `organization_role`, `remark`, `created`, `createdBy`, `changed`, `changedBy`) VALUES (@PARTY_ID, LAST_INSERT_ID(), NULL, NULL, NOW(), 1, NOW(), 1);

SET FOREIGN_KEY_CHECKS = 1;
SQL;

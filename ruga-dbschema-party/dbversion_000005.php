<?php

return <<<'SQL'

SET FOREIGN_KEY_CHECKS = 0;
# ALTER TABLE `Customer` CHANGE COLUMN `Party_id` `Party_id` INT(11) NOT NULL DEFAULT '-1' AFTER `customer_number`;
SET FOREIGN_KEY_CHECKS = 1;

SQL;

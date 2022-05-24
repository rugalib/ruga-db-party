<?php

declare(strict_types=1);

namespace Ruga\Party\Link;

/**
 * Abstract party link.
 * Links a party to either an organization or a person.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class AbstractLinkParty extends \Ruga\Db\Row\AbstractRugaRow
{
    public function getSubtypeKeyName(): string
    {
        return $this->getTableGateway()::PRIMARYKEY[1];
    }
    
    
    
    public function __get($name)
    {
        switch ($name) {
            case 'Subtype_id':
                return parent::__get($this->getSubtypeKeyName());
                break;
        }
        return parent::__get($name);
    }
    
    
    
    public function __set($name, $value)
    {
        switch ($name) {
            case 'Subtype_id':
                parent::__set($this->getSubtypeKeyName(), $value);
                return;
                break;
        }
        parent::__set($name, $value);
    }
    
    
}
<?php

namespace App\Attribute\Admin;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Editable
{
    private $entity;
    private $action;
    const ACTION_ADD = 'action_add';
    const ACTION_EDIT = 'action_edit';
    const ACTION_DELETE = 'action_delete';
    const ACTION_RECOVER = 'action_recover';

    public function __construct($entity, string $action)
    {
       $this->entity = $entity;
       switch ($action){
           case self::ACTION_ADD:
           case self::ACTION_EDIT:
           case self::ACTION_DELETE:
           case self::ACTION_RECOVER:
               $this->action = $action;
               break;
           default:
               throw new \Exception('Action not registered', '500');
       }
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
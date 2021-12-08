<?php

namespace Randomizer\DB;

class DbTableField
{

    private $type;
    private $lenght = '';
    private $name;
    private $key;

    private $requiredProperties = ['Type', 'Field', 'Key'];

    public function __construct(array $properties)
    {

        if (!$this->hasRequiredproperties($properties))
            throw new \InvalidArgumentException('Field has not required properties');

        $this->key = $properties['Key'];
        $this->name = $properties['Field'];
        $this->setTypeAndLenght($properties['Type']);
    }

    public function getLenght()
    {
        return $this->lenght;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isPrimaryKey(): bool
    {
        return ($this->key == "PRI") ? true : false;
    }

    public function isText(): bool
    {
        $textTypes = ['char', 'varchar', 'text'];
        return (in_array($this->type, $textTypes)) ? true : false;
    }

    private function  setTypeAndLenght(string $type): void
    {
        if (preg_match('#([a-z]+)\((\d+)\)#', $type, $matches)) {
            $this->lenght = $matches[2];
            $this->type = $matches[1];
        } else {
            $this->type = $type;
        }
    }


    private function hasRequiredProperties(array $properties): bool
    {
        foreach ($this->requiredProperties as $property)
            if (!array_key_exists($property, $properties))
                return false;

        return true;
    }
}

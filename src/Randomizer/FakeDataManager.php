<?php

namespace Randomizer;

use Faker\Factory;
use Randomizer\DB\DbTableField;
use Randomizer\Helpers\StringConverter;

class FakeDataManager
{

    private $faker;
    const NUMBER_OF_FIELDS = 50;
    private $posibleFormats = ['name', 'firstName', 'lastName', 'city', 'street', 'country', 'ip', 'phoneNumber'];

    public function __construct()
    {
        $this->faker = Factory::create('pl_PL');
    }

    public function getFakeData(DbTableField $field): array
    {
        $fakeData = [];
        for ($i = 0; $i < self::NUMBER_OF_FIELDS; $i++) {
            $fun = StringConverter::changeToCamelCase($field->getName());
            $len = (!empty($field->getLenght())) ? $field->getLenght() : 200;
            if ($this->isPosibleFormat($field->getName())) {
                $fakeData[] = $this->faker->$fun;
            } else {
                $fakeData[] = $this->faker->text($len);
            }
        }
        return $fakeData;
    }

    private function isPosibleFormat($name): bool
    {
        return (in_array(StringConverter::changeToCamelCase($name), $this->posibleFormats)) ? true : false;
    }
}

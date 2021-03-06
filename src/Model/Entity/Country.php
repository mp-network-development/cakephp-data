<?php
namespace Data\Model\Entity;

use Tools\Model\Entity\Entity;

/**
 * @property int $id
 * @property string $name
 * @property string $ori_name
 * @property string $iso2
 * @property string $iso3
 * @property int $country_code
 * @property bool $eu_member
 * @property string $special
 * @property int $zip_length
 * @property int $sort
 * @property \Cake\I18n\Time $modified
 * @property string $address_format
 * @property float $lng
 * @property float $lat
 * @property string $zip_regexp
 */
class Country extends Entity {
}

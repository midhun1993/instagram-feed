<?php

namespace Concrete\Package\InstagramFeed\Src\Instagram\Model;

use Concrete\Package\InstagramFeed\Src\Instagram\Traits\ArrayLikeTrait;
use Concrete\Package\InstagramFeed\Src\Instagram\Traits\InitializerTrait;

/**
 * Class AbstractModel
 * @package InstagramScraper\Model
 */
abstract class AbstractModel implements \ArrayAccess
{
    use InitializerTrait, ArrayLikeTrait;

    /**
     * @var array
     */
    protected static $initPropertiesMap = [];

    /**
     * @return array
     */
    public static function getColumns()
    {
        return \array_keys(static::$initPropertiesMap);
    }
}

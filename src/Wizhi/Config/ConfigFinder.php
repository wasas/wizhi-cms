<?php

namespace Wizhi\Config;

use Wizhi\Finder\Finder;

class ConfigFinder extends Finder
{
    /**
     * The file extensions.
     *
     * @var array
     */
    protected $extensions = ['config.php', 'php'];
}

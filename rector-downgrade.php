<?php

use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(DowngradeLevelSetList::DOWN_TO_PHP_72);
};

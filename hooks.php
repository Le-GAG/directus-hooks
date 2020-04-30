<?php

use Directus\Custom\Hooks\LeGAG\AfterCreateOrder;
use Directus\Custom\Hooks\LeGAG\BeforeInsertCommandesProduitsVariantes;

return [
    'actions' => [],
    'filters' => [
        'item.create._commandes_produits_variantes:before' => new BeforeInsertCommandesProduitsVariantes(),
        'response.POST' => new AfterCreateOrder(),
    ],
];

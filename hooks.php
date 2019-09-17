<?php

return [
    'filters' => [
        'item.create._commandes_produits_variantes:before' => new \Directus\Custom\Hooks\LeGAG\BeforeInsertCommandesProduitsVariantes()
    ]
];

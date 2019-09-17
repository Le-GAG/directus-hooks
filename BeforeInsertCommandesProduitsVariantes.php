<?php
/**
 * Directus hook to add the current price to every product variant in a new order.
 *
 * @author Yohann Bianchi<yohann.b@lahautesociete.com>
 * @date   26/08/2019 19:28
 */

namespace Directus\Custom\Hooks\LeGAG;

use Directus\Application\Application;
use Directus\Hook\HookInterface;
use Directus\Hook\Payload;
use Directus\Services\ItemsService;

class BeforeInsertCommandesProduitsVariantes implements HookInterface
{
    const FIELD_PRICE = 'prix';

    /**
     * @param Payload $payload
     *
     * @return Payload
     */
    public function handle($payload = null)
    {
        $container = Application::getInstance()->getContainer();
        $itemsService = new ItemsService($container);

        $productVariantId = $payload->get('produits_variantes_id');
        $fetchParams = [ 'fields' => [ 'id', self::FIELD_PRICE ] ];
        $productVariant = $itemsService->find('produits_variantes', $productVariantId, $fetchParams);

        $payload->set(self::FIELD_PRICE, $productVariant['data'][self::FIELD_PRICE]);

        return $payload;
    }
}

<?php
/**
 * @author Yohann Bianchi<yohann.b@lahautesociete.com>
 * @date   23/04/2020 23:39
 */

namespace Directus\Custom\Hooks\LeGAG;

use Directus\Application\Application;
use Directus\Application\Container;
use Directus\Application\Http\Response;
use Directus\Authentication\Provider;
use Directus\Authentication\User\UserInterface;
use Directus\Hook\HookInterface;
use Directus\Hook\Payload;
use Directus\Mail\Message;
use Directus\Services\ItemsService;
use RuntimeException;
use Slim\Views\Twig;
use function Directus\send_mail_with_content;
use function Directus\send_mail_with_template;

class AfterCreateOrder implements HookInterface
{
    /** @var Container */
    public $container;

    public function __construct()
    {
        $application = Application::getInstance();

        if (!$application) {
            throw new RuntimeException('Cannot retrieve an Application instance');
        }

        $this->container = $application->getContainer();
    }

    /**
     * @param Payload $payload
     */
    public function handle($payload = null)
    {
        if (
            $_SERVER["SCRIPT_URL"] !== '/_/items/commandes'
            || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST'
        ) {
            return $payload;
        }

        $order = $this->getOrder($payload->getData()['data']['id']);

        $user = $this->getCurrentUser();
        send_mail_with_content($body, $contentType, $callback);
        send_mail_with_template(
            'order-confirmation.twig',
            [ 'order' => $order ],
            function (Message $message) use ($user) {
                $message->setSubject('Votre commande est enregistrée');
                $message->setTo($user->getEmail(), sprintf("%s %s", $user->first_name, $user->last_name));
            }
        );

        return $payload;
    }

    protected function getCurrentUser(): UserInterface
    {
        /** @var Provider $authProvider */
        $authProvider = $this->container->get('auth');
        $user = $authProvider->getUser();

        if (!$user) {
            throw new RuntimeException('Cannot retrieve current user.');
        }

        return $user;
    }

    protected function getOrder($orderId)
    {
        $itemService = new ItemsService($this->container);
        $order = $itemService->find('commandes', $orderId, [ 'fields' => [
            'produits_variantes.produits_variantes_id.produit.nom',
            'produits_variantes.produits_variantes_id.prix',
            'produits_variantes.produits_variantes_id.conditionnement.nom',
            'produits_variantes.produits_variantes_id.contenance',
            'produits_variantes.quantite',
        ]]);

        return [
            'items' => array_map(function($item) {
                return [
                    'name'      => $item['produits_variantes_id']['produit']['nom'],
                    'price'     => $item['produits_variantes_id']['prix'],
                    'packaging' => $item['produits_variantes_id']['conditionnement']['nom'],
                    'capacity'  => $item['produits_variantes_id']['contenance'],
                    'quantity'  => $item['quantite'],
                ];
            }, $order['data']['produits_variantes']),
        ];
    }
}

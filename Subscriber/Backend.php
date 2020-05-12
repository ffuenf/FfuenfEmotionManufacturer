<?php
/**
 *
 * class FfuenfEmotionManufacturer
 *
 * @category   Shopware
 * @package    Shopware\Plugins\FfuenfEmotionManufacturer
 * @author     Achim Rosenhagen / ffuenf - Pra & Rosenhagen GbR
 * @copyright  Copyright (c) 2020, Achim Rosenhagen / ffuenf - Pra & Rosenhagen GbR (https://www.ffuenf.de)
 *
 */

namespace FfuenfEmotionManufacturer\Subscriber;

use Enlight\Event\SubscriberInterface;
use FfuenfCommon\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Enlight_Event_EventArgs;

class Backend extends AbstractService implements SubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Emotion' => 'onPostDispatchBackendEmotion'
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchBackendEmotion(Enlight_Event_EventArgs $args)
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->addTemplateDir($this->viewDirectory);
        $view->extendsTemplate($this->viewDirectory . '/backend/emotion/ffuenf_emotion_manufacturer/view/detail/elements/ffuenf_emotion_manufacturer.js');
    }
}
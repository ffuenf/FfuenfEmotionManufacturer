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
use Shopware\Components\Theme\LessDefinition;
use Enlight_Event_EventArgs;

class Frontend extends AbstractService implements SubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Controllers_Widgets_Emotion_AddElement' => 'onEmotionAddElement'
        ];
    }

    public function onEmotionAddElement(\Enlight_Event_EventArgs $args)
    {
        $element = $args->get('element');
        if ($element['component']['template'] !== 'ffuenf_emotion_manufacturer') {
            return;
        }
        $data = $args->getReturn();
        $data['headlineClickable'] = $this->config['headlineClickable'];
        $data['showHeader'] = (bool)$data['show_header'];
        $categoryId = $data['category_id'];
        $data['category'] = Shopware()->Modules()->Categories()->sGetCategoryContent($categoryId);

        if ($data['show_header'] && !empty($data['header'])) {
            $data['header'] = $data['header'];
        } else {
            $data['header'] = '';
        }
        $limitCategories = array();
        $limitManufacturers = array();
        if ((int)$data['category_id']) {
            $q = "SELECT id FROM s_categories WHERE path LIKE '%|" . (int)$data['category_id'] . "|%' LIMIT 1000";
            $limitCategories = Shopware()->Db()->query($q)->fetchAll(\PDO::FETCH_COLUMN);
            $limitCategories[] = (int)$data['category_id'];
            $q = "SELECT DISTINCT(a.supplierID)
                  FROM s_articles_categories_ro acro
                  JOIN s_articles_details ad ON ad.articleID = acro.articleID
                  JOIN s_articles a ON a.id = acro.articleID
                  WHERE acro.categoryID IN (" . implode(',', $limitCategories) . ") ";
            $limitManufacturers = Shopware()->Db()->query($q)->fetchAll(\PDO::FETCH_COLUMN);
        }
        switch ((int)$data['sort_order']) {
            case 1:
                // order by sales
                $q = 'SELECT sas.id, sas.name, sas.img, SUM(sod.quantity) quantity_sold FROM s_order so
                      JOIN s_order_details sod ON (sod.orderID = so.id )
                      JOIN s_articles sa ON (sa.id = sod.articleID )
                      JOIN s_articles_supplier sas ON (sas.id = sa.supplierID )
                      WHERE so.status IN ( 0,1,2,3,5,6,7 )
                      AND (sas.img IS NOT NULL OR sas.img <> "") ';
                if (count($limitManufacturers) > 0) {
                    $q .= ' AND sa.supplierID IN (' . implode(',', $limitManufacturers) . ') ';
                }
                if ((int)$data['hide_inactive'] == 1) {
                    $q .= ' AND sa.active = 1 ';
                }
                $q .= 'GROUP BY sas.id, sas.name, sas.img ORDER BY quantity_sold DESC ';
                break;
            case 2:
            default:
                // order by name ASC
                $q = 'SELECT sas.id, sas.name, sas.img FROM s_articles sa
                      JOIN s_articles_supplier sas ON (sas.id = sa.supplierID)
                      WHERE (sas.img IS NOT NULL OR sas.img <> "") ';
                if (count($limitManufacturers) > 0) {
                    $q .= ' AND sa.supplierID IN (' . implode(',', $limitManufacturers) . ') ';
                }
                if ((int)$data['hide_inactive'] == 1) {
                    $q .= ' AND sa.active = 1 ';
                }
                $q .= 'GROUP BY sas.id, sas.name, sas.img ORDER BY sas.name ASC ';
                break;
            case 3:
                // order by name DESC
                $q = 'SELECT sas.id, sas.name, sas.img FROM s_articles sa
                      JOIN s_articles_supplier sas ON (sas.id = sa.supplierID)
                      WHERE (sas.img IS NOT NULL OR sas.img <> "") ';
                if (count($limitManufacturers) > 0) {
                    $q .= ' AND sa.supplierID IN (' . implode(',', $limitManufacturers) . ') ';
                }
                if ((int)$data['hide_inactive'] == 1) {
                    $q .= ' AND sa.active = 1 ';
                }
                $q .= 'GROUP BY sas.id, sas.name, sas.img ORDER BY sas.name DESC ';
                break;
            case 4:
                // random
                $q = 'SELECT sas.id, sas.name, sas.img FROM s_articles sa
                      JOIN s_articles_supplier sas ON (sas.id = sa.supplierID)
                      WHERE (sas.img IS NOT NULL OR sas.img <> "") ';
                if (count($limitManufacturers) > 0) {
                    $q .= ' AND sa.supplierID IN (' . implode(',', $limitManufacturers) . ') ';
                }
                if ((int)$data['hide_inactive'] == 1) {
                    $q .= ' AND sa.active = 1 ';
                }
                $q .= 'GROUP BY sas.id, sas.name, sas.img ORDER BY RAND() ';
                break;
            case 5:
                // by newest articles
                $q = 'SELECT sas.id, sas.name, sas.img FROM s_articles sa
                      JOIN s_articles_supplier sas ON (sas.id = sa.supplierID)
                      WHERE (sas.img IS NOT NULL OR sas.img <> "") ';
                if (count($limitManufacturers) > 0) {
                    $q .= ' AND sa.supplierID IN (' . implode(',', $limitManufacturers) . ') ';
                }
                if ((int)$data['hide_inactive'] == 1) {
                    $q .= ' AND sa.active = 1 ';
                }
                $q .= 'GROUP BY sas.id, sas.name, sas.img ORDER BY sa.changetime DESC ';
                break;
            case 6:
                // by newest manufacturer
                $q = 'SELECT sas.id, sas.name, sas.img FROM s_articles sa
                      JOIN s_articles_supplier sas ON (sas.id = sa.supplierID)
                      WHERE (sas.img IS NOT NULL OR sas.img <> "") ';
                if (count($limitManufacturers) > 0) {
                    $q .= ' AND sa.supplierID IN (' . implode(',', $limitManufacturers) . ') ';
                }
                if ((int)$data['hide_inactive'] == 1) {
                    $q .= ' AND sa.active = 1 ';
                }
                $q .= 'GROUP BY sas.id, sas.name, sas.img ORDER BY sas.changed DESC ';
                break;
        }
        if ((int)$data['record_limit'] > 0) {
            $q .= ' LIMIT ' . (int)$data['record_limit'];
        }
        $rows = Shopware()->Db()->fetchAll($q);
        $mediaService = $this->container->get('shopware_media.media_service');
        $router = $this->container->get('router');
        $ret = array();
        foreach ($rows as $row) {
            $url = $router->assemble(
                array(
                    'sViewport' => 'listing',
                    'sSupplier' => $row['id'],
                    'sAction'   => 'manufacturer'
                )
            );
            $ret[] = array(
                'name' => $row['name'],
                'img'  => $mediaService->getUrl($row['img']),
                'url'  => $url
            );
        }
        $data['manufacturers'] = $ret;
        $args->setReturn($data);
    }
}
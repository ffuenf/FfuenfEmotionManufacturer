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

namespace FfuenfEmotionManufacturer;

use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Components\Plugin\Context\EnableContext;
use Shopware\Components\Plugin\Context\DisableContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Emotion\Library\Component;
use Shopware\Models\Plugin\Plugin;
use Shopware\Models\Widget\View;

class FfuenfEmotionManufacturer extends \Shopware\Components\Plugin
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('ffuenf_emotion_manufacturer.plugin_dir', $this->getPath());
        $container->setParameter('ffuenf_emotion_manufacturer.view_dir', $this->getPath() . '/Resources/views');
        parent::build($container);
    }

    /**
     * @param ActivateContext $context
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    /**
     * @param DeactivateContext $context
     */
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $this->createEmotionComponentHerstellerliste($context);
        parent::install($context);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        $em = $this->container->get('models');
        $component = $em->getRepository(Component::class)->findOneBy([
            'name'     => 'FfuenfEmotionManufacturer',
            'pluginId' => $context->getPlugin()->getId()
        ]);
        if (!$component) {
            return;
        }
        $em->remove($component);
        $em->flush();
        $context->scheduleClearCache(UninstallContext::CACHE_LIST_ALL);
    }

    /**
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        $this->createEmotionComponentHerstellerliste($context);
        parent::update($context);
    }

    /**
     * @param EnableContext $context
     */
    public function enable(EnableContext $context)
    {
        $context->scheduleClearCache(EnableContext::CACHE_LIST_ALL);
    }

    /**
     * @param DisableContext $context
     */
    public function disable(DisableContext $context)
    {
        $context->scheduleClearCache(DisableContext::CACHE_LIST_ALL);
    }

    private function createEmotionComponentHerstellerliste($context)
    {
        $component = $this->createEmotionComponent($context->getPlugin(), [
            'name'        => 'Herstellerliste',
            'xtype'       => 'emotion-components-ffuenf-emotion-manufacturer',
            'template'    => 'ffuenf_emotion_manufacturer',
            'cls'         => 'ffuenf-emotion-manufacturer',
            'description' => 'Herstellerliste esogjnbosigpeosr'
        ]);
        $component->createCheckboxField([
            'name'         => 'show_header',
            'fieldLabel'   => 'Zeige Überschrift',
            'defaultValue' => true
        ]);
        $component->createTextField([
            'name'        => 'header',
            'fieldLabel'  => 'Überschrift',
            'allowBlank'  => true
        ]);
        $component->createNumberField([
            'name'        => 'category_id',
            'fieldLabel'  => 'Kategorie ID',
            'supportText' => 'Die ID der Kategorie aus der die Hersteller extrahiert werden sollen.',
            'allowBlank'  => true
        ]);
        $component->createComboBoxField([
            'name'         => 'sort_order',
            'fieldLabel'   => 'Sortierung',
            'store'        => 'Shopware.apps.Emotion.store.FfuenfEmotionManufacturerSortOrder',
            'queryMode'    => 'local',
            'displayField' => 'name',
            'valueField'   => 'id',
            'defaultValue' => 2,
            'allowBlank'   => false
        ]);
        $component->createNumberField([
            'name'         => 'record_limit',
            'fieldLabel'   => 'Anzahl der angezegten Hersteller.',
            'defaultValue' => 30,
            'allowBlank'   => false
        ]);
        $component->createCheckboxField([
            'name'         => 'hide_inactive',
            'fieldLabel'   => 'Ignoriere Hersteller ohne aktive Artikel.',
            'defaultValue' => true
        ]);
        $component->createTextField([
            'name'        => 'landingpageLink',
            'fieldLabel'  => 'Hersteller-Landingpage URL',
            'allowBlank'  => true
        ]);
        $component->createTextField([
            'name'         => 'landingpageTitle',
            'fieldLabel'   => 'Hersteller-Landingpage Link-Titel',
            'defaultValue' => '...alle Hersteller anzeigen',
            'allowBlank'   => false
        ]);
        $em = $this->container->get('models');
        $em->persist($component);
        $em->flush();
    }

    /**
     * @param $options
     * @param Plugin $pluginModel
     * @return Component
     */
    protected function createEmotionComponent(Plugin $pluginModel, $options)
    {
        /** @var ModelManager $em */
        $em = $this->container->get('models');
        // if a component with this name already exists for this plugin, use that
        $component = $em->getRepository(Component::class)->findOneBy([
            'name'     => $options['name'],
            'pluginId' => $pluginModel->getId()
        ]);
        // else: create a new component
        if (!$component) {
            $component = new Component();
        }
        $component->fromArray($options);
        $component->setPluginId($pluginModel->getId());
        $component->setPlugin($pluginModel);
        return $component;
    }

    /**
     * returns an emotion component by xtype
     * @param $xtype
     * @return null|object
     */
    private function getEmotionComponent($xtype)
    {
        $models = $this->container->get('models');
        $repository = $models->getRepository('Shopware\Models\Emotion\Library\Component');
        $component = $repository->findOneBy([
            'xType' => $xtype,
        ]);
        if (!$component instanceof \Shopware\Models\Emotion\Library\Component) {
            return null;
        }
        return $component;
    }
}
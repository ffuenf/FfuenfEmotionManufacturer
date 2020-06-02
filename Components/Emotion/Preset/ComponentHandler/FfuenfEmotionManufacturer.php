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

namespace FfuenfEmotionManufacturer\Components\Emotion\Preset\ComponentHandler;

use Shopware\Bundle\MediaBundle\MediaService;
use Shopware\Components\Api\Resource\Media as MediaResource;
use Shopware\Components\DependencyInjection\Container;
use Shopware\Components\Emotion\Preset\ComponentHandler\ComponentHandlerInterface;
use Shopware\Models\Media\Media;
use Symfony\Component\HttpFoundation\ParameterBag;

class FfuenfEmotionManufacturer implements ComponentHandlerInterface
{
    const COMPONENT_TYPE = 'emotion-components-ffuenf-emotion-manufacturer';
    const ELEMENT_DATA_KEY = '';

    /**
     * @var MediaResource
     */
    protected $mediaResource;

    /**
     * @var MediaService
     */
    protected $mediaService;
    /**
     * @param MediaResource $mediaResource
     * @param MediaService  $mediaService
     * @param Container     $container
     */

    public function __construct(MediaResource $mediaResource, MediaService $mediaService, Container $container)
    {
        $this->mediaResource = $mediaResource;
        $this->mediaResource->setContainer($container);
        $this->mediaResource->setManager($container->get('models'));
        $this->mediaService = $mediaService;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($componentType)
    {
        return $componentType === self::COMPONENT_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function import(array $element, ParameterBag $syncData)
    {
        if (!isset($element['data'])) {
            return $element;
        }
        return $this->processElementData($element, $syncData);
    }

    /**
     * {@inheritdoc}
     */
    public function export(array $element, ParameterBag $syncData)
    {
        if (!isset($element['data'])) {
            return $element;
        }
        return $this->prepareElementExport($element, $syncData);
    }

    /**
     * @param string $assetPath
     * @param int    $albumId
     *
     * @return Media
     */
    protected function doAssetImport($assetPath, $albumId = -3)
    {
        $media = $this->mediaResource->internalCreateMediaByFileLink($assetPath, $albumId);
        if ($media) {
            $this->mediaResource->getManager()->flush($media);
        }
        return $media;
    }

    /**
     * @param array        $element
     * @param ParameterBag $syncData
     *
     * @return array
     */
    private function processElementData(array $element, ParameterBag $syncData)
    {
        /** @var array $data */
        $data = $element['data'];
        $assets = $syncData->get('assets', []);
        $importedAssets = $syncData->get('importedAssets', []);
        foreach ($data as &$elementData) {
            if ($elementData['key'] !== self::ELEMENT_DATA_KEY) {
                continue;
            }
            if (!array_key_exists($elementData['value'], $assets)) {
                break;
            }
            if (!array_key_exists($elementData['value'], $importedAssets)) {
                $assetPath = $assets[$elementData['value']];
                $media = $this->doAssetImport($assetPath);
                $importedAssets[$elementData['value']] = $media->getId();
            } else {
                $media = $this->getMediaById($importedAssets[$elementData['value']]);
            }
            // sideview component uses full url of media
            $elementData['value'] = $this->mediaService->getUrl($media->getPath());
            break;
        }
        unset($elementData);
        $syncData->set('importedAssets', $importedAssets);
        $element['data'] = $data;
        unset($element['assets']);
        return $element;
    }

    /**
     * @param array        $element
     * @param ParameterBag $syncData
     *
     * @return array
     */
    private function prepareElementExport(array $element, ParameterBag $syncData)
    {
        $assets = $syncData->get('assets', []);
        /** @var array $data */
        $data = $element['data'];
        foreach ($data as &$elementData) {
            if ($elementData['key'] !== self::ELEMENT_DATA_KEY) {
                continue;
            }
            $assetUrl = $elementData['value'];
            $assetPath = $this->mediaService->normalize($assetUrl);
            $media = $this->getMediaByPath($assetPath);
            if ($media) {
                $assetHash = md5($media->getId());
                $assets[$assetHash] = $assetUrl;
                $elementData['value'] = $assetHash;
            }
            break;
        }
        unset($elementData);
        $syncData->set('assets', $assets);
        $element['data'] = $data;
        return $element;
    }

    /**
     * @param int $id
     *
     * @return null|Media
     */
    private function getMediaById($id)
    {
        return $this->mediaResource->getRepository()->find($id);
    }

    /**
     * @param string $path
     *
     * @return null|Media
     */
    private function getMediaByPath($path)
    {
        return $this->mediaResource->getRepository()->findOneBy(['path' => $path]);
    }
}

<?php

namespace Drupal\paragraphs_paste\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\media\MediaSourceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Derives paragraph paste plugins handling OEmbed urls.
 */
class OEmbedUrlDeriver extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The media source manager.
   *
   * @var \Drupal\media\MediaSourceManager
   */
  protected $mediaSourceManager;

  /**
   * OEmbedUrlDeriver constructor.
   *
   * @param \Drupal\media\MediaSourceManager|null $mediaSourceManager
   *   The media source manager.
   */
  public function __construct(MediaSourceManager $mediaSourceManager = NULL) {
    $this->mediaSourceManager = $mediaSourceManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static($container->has('plugin.manager.media.source') ? $container->get('plugin.manager.media.source') : NULL);
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (!$this->mediaSourceManager) {
      return [];
    }

    $this->derivatives = [];
    if ($definition = $this->mediaSourceManager->getDefinition('oembed:video', FALSE)) {
      $this->derivatives[] = [
        'id' => 'oembed_url:video',
        'label' => $this->t('Remote video'),
        'description' => $this->t('Youtube or Vimeo URLs.'),
        'providers' => $definition['providers'],
        'allowed_field_types' => $definition['allowed_field_types'],
      ] + $base_plugin_definition;
    }
    if ($definition = $this->mediaSourceManager->getDefinition('twitter', FALSE)) {
      $this->derivatives[] = [
        'id' => 'oembed_url:twitter',
        'label' => $this->t('Twitter'),
        'description' => $this->t('Twitter URLs.'),
        'providers' => ['Twitter'],
        'allowed_field_types' => $definition['allowed_field_types'],
      ] + $base_plugin_definition;
    }
    if ($definition = $this->mediaSourceManager->getDefinition('instagram', FALSE)) {
      $this->derivatives[] = [
        'id' => 'oembed_url:instagram',
        'label' => $this->t('Instagram'),
        'description' => $this->t('Instagram URLs.'),
        'providers' => ['Instagram'],
        'allowed_field_types' => $definition['allowed_field_types'],
      ] + $base_plugin_definition;
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}

<?php

namespace Drupal\paragraphs_paste;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides a ParagraphsPaste Plugin plugin manager.
 *
 * @see \Drupal\paragraphs_paste\Annotation\ParagraphsPastePlugin
 * @see \Drupal\paragraphs_paste\ParagraphsPastePluginBase
 * @see \Drupal\paragraphs_paste\ParagraphsPastePluginInterface
 * @see \Drupal\paragraphs_paste\ParagraphsPastePluginManager
 * @see plugin_api
 */
class ParagraphsPastePluginManager extends DefaultPluginManager {

  /**
   * Constructs a ParagraphsPastePluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ParagraphsPastePlugin', $namespaces, $module_handler, 'Drupal\paragraphs_paste\ParagraphsPastePluginInterface', 'Drupal\paragraphs_paste\Annotation\ParagraphsPastePlugin');
    $this->alterInfo('paragraphs_paste_plugin_info');
    $this->setCacheBackend($cache_backend, 'paragraphs_paste_plugins');
  }

  /**
   * Return applicable plugins from user input.
   *
   * @param string $input
   *   Input string.
   *
   * @return \Drupal\paragraphs_paste\ParagraphsPastePluginInterface[]
   *   The available plugins.
   */
  public function getPluginsFromInput($input) {
    return array_filter($this->getDefinitions(), function ($definition) use ($input) {
      return $definition['class']::isApplicable($input, $definition);
    });
  }

}

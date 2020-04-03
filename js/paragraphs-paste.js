/**
 * @file
 * Paragraphs actions JS code for paragraphs actions button.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Handle event when "Paste" button is clicked.
   *
   * @param {event} event The event.
   */
  var pasteHandler = function (event) {
    var clipboardData;
    var pastedData;

    event.stopPropagation();
    event.preventDefault();

    // Get pasted data via clipboard API.
    clipboardData = event.originalEvent.clipboardData || window.clipboardData;
    pastedData = JSON.stringify(clipboardData.getData('Text'));

    var pasteTarget = $(event.currentTarget).data('paragraphs-paste-target');
    $('[data-drupal-selector="' + pasteTarget.replace(/action$/, 'content') + '"]').val(pastedData);
    $('[data-drupal-selector="' + pasteTarget + '"]').trigger('mousedown');
  };

  /**
   * Theme function for remove button.
   *
   * @param {object} options
   *   Options for delete confirmation button.
   *
   * @return {string}
   *   Returns markup.
   */
  Drupal.theme.paragraphsPasteActionArea = function (options) {
    return `<div class="paragraphs-paste-action" data-paragraphs-paste-target="${options.target}"><div class="paragraphs-paste-message"><p>${Drupal.t('Paste here.')}</p></div></div>`;
  };

  /**
   * Process paragraph_AddAboveButton elements.
   */
  Drupal.behaviors.paragraphsPasteAction = {
    attach: function (context) {
      var $buttons = $('[data-paragraphs-paste="enabled"]', context);
      $buttons.each(function () {
        var $this = $(this);
        var $wrapper = $this.closest('.form-wrapper').once('paragraphsPaste');

        $wrapper.find('> div').prepend(Drupal.theme('paragraphsPasteActionArea', {target: $this.data('drupalSelector')}));
        $wrapper.find('.paragraphs-paste-action')
          .on('paste', pasteHandler)
          .on('mousedown', function () { $(this).attr('contenteditable', true); });
      });
    },
    detach: function (context) {
      var $buttons = $('[data-paragraphs-paste="enabled"]', context);
      $buttons.each(function () {
        $(this).closest('.form-wrapper').removeOnce('paragraphsPaste');
      });
    }
  };

})(jQuery, Drupal);

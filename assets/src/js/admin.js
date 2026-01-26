/**
 * Admin JavaScript for My Plugin
 */

import '../scss/admin.scss';

(function ($) {
  'use strict';

  const MyPluginAdmin = {
    /**
     * Initialize the admin module.
     */
    init() {
      this.cacheElements();
      this.bindEvents();
      // Hide save button on Dashboard tab (default).
      $('.my-plugin-form-actions').hide();
    },

    /**
     * Cache DOM elements.
     */
    cacheElements() {
      this.$tabs = $('.my-plugin-tabs li');
      this.$tabContents = $('.my-plugin-tab-content');
      this.$form = $('#my-plugin-settings-form');
      this.$saveBtn = $('.my-plugin-save-btn');
      this.$toast = $('#my-plugin-toast');
    },

    /**
     * Bind event handlers.
     */
    bindEvents() {
      this.$tabs.on('click', this.handleTabClick.bind(this));
      this.$form.on('submit', this.handleFormSubmit.bind(this));
      $(document).on('click', '.my-plugin-accordion-header', this.handleAccordionClick);
    },

    /**
     * Handle tab click.
     *
     * @param {Event} e Click event.
     */
    handleTabClick(e) {
      const $tab = $(e.currentTarget);
      const tabId = $tab.data('tab');

      // Update active tab.
      this.$tabs.removeClass('active');
      $tab.addClass('active');

      // Show corresponding content.
      this.$tabContents.removeClass('active');
      $('#tab-' + tabId).addClass('active');

      // Hide save button on Dashboard tab.
      $('.my-plugin-form-actions').toggle(tabId !== 'dashboard');
    },

    /**
     * Handle accordion header click.
     *
     * @param {Event} e Click event.
     */
    handleAccordionClick(e) {
      const $header = $(e.currentTarget);
      const $content = $header.next('.my-plugin-accordion-content');

      $header.toggleClass('active');
      $content.toggleClass('active');
    },

    /**
     * Handle form submit (AJAX save).
     *
     * @param {Event} e Submit event.
     */
    handleFormSubmit(e) {
      e.preventDefault();

      if (this.$saveBtn.hasClass('is-loading')) {
        return;
      }

      this.setLoading(true);

      const formData = this.$form.serialize();

      $.ajax({
        url: myPluginAdmin.ajaxUrl,
        type: 'POST',
        data: formData + '&action=my_plugin_save_settings',
        success: this.handleSaveSuccess.bind(this),
        error: this.handleSaveError.bind(this),
        complete: () => this.setLoading(false),
      });
    },

    /**
     * Handle successful save.
     *
     * @param {Object} response AJAX response.
     */
    handleSaveSuccess(response) {
      if (response.success) {
        this.showToast(response.data.message, 'success');
      } else {
        this.showToast(response.data.message || myPluginAdmin.strings.error, 'error');
      }
    },

    /**
     * Handle save error.
     */
    handleSaveError() {
      this.showToast(myPluginAdmin.strings.error, 'error');
    },

    /**
     * Set loading state.
     *
     * @param {boolean} isLoading Loading state.
     */
    setLoading(isLoading) {
      this.$saveBtn.toggleClass('is-loading', isLoading);
    },

    /**
     * Show toast notification.
     *
     * @param {string} message Toast message.
     * @param {string} type    Toast type (success|error).
     */
    showToast(message, type = 'success') {
      const icon = type === 'success' ? 'yes-alt' : 'warning';
      const $toast = $('<div>', { class: `my-plugin-toast-item ${type}` }).append(
        $('<span>', { class: `dashicons dashicons-${icon}` }),
        $('<span>').text(message) // Use .text() to prevent XSS
      );

      this.$toast.append($toast);

      // Auto-remove after 3 seconds.
      setTimeout(() => {
        $toast.addClass('fade-out');
        setTimeout(() => $toast.remove(), 300);
      }, 3000);
    },
  };

  // Initialize when DOM ready.
  $(document).ready(() => {
    MyPluginAdmin.init();
  });
})(jQuery);

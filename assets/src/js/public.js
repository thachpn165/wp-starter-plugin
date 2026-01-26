/**
 * Public JavaScript for My Plugin
 */

import '../scss/public.scss';

(function () {
  'use strict';

  const MyPluginPublic = {
    init() {
      this.bindEvents();
    },

    bindEvents() {
      // Example event binding
      document.querySelectorAll('.my-plugin-widget').forEach((widget) => {
        widget.addEventListener('click', this.handleWidgetClick);
      });
    },

    handleWidgetClick(e) {
      // Handle widget click event
      e.preventDefault();
    },
  };

  // Initialize when DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => MyPluginPublic.init());
  } else {
    MyPluginPublic.init();
  }
})();

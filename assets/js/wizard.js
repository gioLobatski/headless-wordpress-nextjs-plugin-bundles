/**
 * WordPress Plugin Bundle - Installation Wizard JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Handle site type selection and installation
     */
    $('.wp-bundle-install-btn').on('click', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $option = $button.closest('.wp-bundle-site-option');
        var siteType = $button.data('site-type');
        var $progress = $('.wp-bundle-install-progress');
        var $progressBar = $progress.find('.progress-bar');
        var $statusText = $progress.find('.status-text');
        var $log = $progress.find('.progress-log');
        
        // Disable all buttons
        $('.wp-bundle-install-btn').prop('disabled', true);
        $('.wp-bundle-site-option').addClass('installing');
        
        // Show progress section
        $progress.slideDown();
        
        // Scroll to progress
        $('html, body').animate({
            scrollTop: $progress.offset().top - 100
        }, 500);
        
        // Update status
        $statusText.text('Preparing installation for ' + (siteType === 'basic' ? 'Basic/Portfolio' : 'Shop/Catalogue') + ' site...');
        
        // Make AJAX request
        $.ajax({
            url: wpBundleWizardAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wp_bundle_run_wizard_install',
                nonce: wpBundleWizardAjax.nonce,
                site_type: siteType
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                
                // Not using upload progress, but keeping for future use
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    $progressBar.css('width', '100%').text('100%');
                    $statusText.text('Installation complete!');
                    
                    // Add success log
                    addLog('✓ All plugins installed successfully!', 'success');
                    addLog('✓ Redirecting to dashboard...', 'info');
                    
                    // Redirect after delay
                    setTimeout(function() {
                        window.location.href = wpBundleWizardAjax.dashboardUrl;
                    }, 2000);
                } else {
                    $statusText.text('Installation completed with some errors');
                    addLog('✗ ' + response.data.message, 'error');
                }
            },
            error: function() {
                $statusText.text('Installation failed');
                addLog('✗ An error occurred during installation', 'error');
            },
            beforeSend: function() {
                // Log start
                addLog('Starting plugin installation...', 'info');
                addLog('Site type: ' + (siteType === 'basic' ? 'Basic/Portfolio' : 'Shop/Catalogue'), 'info');
                addLog('---', 'info');
            }
        });
    });
    
    /**
     * Add log entry
     */
    function addLog(message, type) {
        var $log = $('.progress-log');
        var $entry = $('<div class="log-entry ' + type + '">' + message + '</div>');
        $log.append($entry);
        
        // Scroll to bottom
        $log.scrollTop($log[0].scrollHeight);
    }
    
    /**
     * Simulate progress updates (optional enhancement)
     */
    function updateProgress(percent, message) {
        $('.progress-bar').css('width', percent + '%').text(percent + '%');
        if (message) {
            $('.status-text').text(message);
        }
    }
});

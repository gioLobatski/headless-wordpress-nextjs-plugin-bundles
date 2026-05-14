/**
 * WordPress Plugin Bundle - Admin JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Reinstall all bundled plugins
     */
    $('#wp-bundle-reinstall').on('click', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $message = $('#wp-bundle-ajax-message');
        var originalText = $button.text();
        
        // Show loading state
        $button.prop('disabled', true).html('<span class="wp-bundle-spinner"></span>' + wpBundleAjax.reinstallText);
        $message.removeClass('notice-success notice-error is-hidden').addClass('notice-info').find('p').text('Processing...');
        
        // Make AJAX request
        $.ajax({
            url: wpBundleAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wp_bundle_reinstall_plugins',
                nonce: wpBundleAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $message.removeClass('notice-info notice-error').addClass('notice-success');
                    $message.find('p').text(response.data.message);
                    
                    // Reload page after 2 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    $message.removeClass('notice-info notice-success').addClass('notice-error');
                    $message.find('p').text(response.data.message || wpBundleAjax.errorText);
                }
            },
            error: function() {
                $message.removeClass('notice-info notice-success').addClass('notice-error');
                $message.find('p').text(wpBundleAjax.errorText);
            },
            complete: function() {
                $button.prop('disabled', false).text(originalText);
                
                // Show message
                $message.removeClass('is-hidden');
                
                // Hide message after 5 seconds
                setTimeout(function() {
                    $message.addClass('is-hidden');
                }, 5000);
            }
        });
    });
    
    /**
     * Refresh plugin status
     */
    $('#wp-bundle-refresh-status').on('click', function(e) {
        e.preventDefault();
        location.reload();
    });
    
    /**
     * Auto-hide notices after 5 seconds
     */
    setTimeout(function() {
        $('.notice.is-dismissible').fadeOut();
    }, 5000);
});

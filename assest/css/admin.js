jQuery(document).ready(function($) {
    // Initialize color pickers
    $('.aio-cmp-color-picker').wpColorPicker({
        change: function(event, ui) {
            // You could add live preview updates here if needed
        }
    });
    
    // Refresh preview when settings are saved
    $(document).on('click', '.aio-cmp-admin #submit', function() {
        setTimeout(function() {
            refreshPreview();
        }, 1000); // Give the settings time to save
    });
    
    // Function to refresh the preview iframe
    function refreshPreview() {
        var previewFrame = document.getElementById('aio-cmp-preview-frame');
        if (previewFrame) {
            var currentSrc = previewFrame.src;
            previewFrame.src = currentSrc + (currentSrc.indexOf('?') >= 0 ? '&' : '?') + 'ts=' + new Date().getTime();
        }
    }
    
    // Handle the toggle in the admin bar
    $(document).on('click', '#wp-admin-bar-aio-cmp-toggle a', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'aio_cmp_toggle_status',
                security: aio_cmp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
});
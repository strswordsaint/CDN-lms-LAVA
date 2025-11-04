</div> </main> </div> <?php if (lava_instance()->session->has_userdata('user_id')): ?>
        <footer class="text-center text-sm bg-white text-neutral-600 py-3 border-t border-neutral-200" style="height: 50px;">
            <p class="font-medium">
                &copy; <?php echo date('Y'); ?> <span class="font-semibold text-primary-800">Colegio de Naujan</span> LMS. All rights reserved.
            </p>

            <?php if(config_item('ENVIRONMENT') === 'development'): ?>
                <p class="text-xs mt-1 text-neutral-400">
                    LavaLust v<?php echo config_item('VERSION'); ?> |
                    Page rendered in {elapsed_time}s |
                    Memory: {memory_usage}
                </p>
            <?php endif; ?>
        </footer>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
            // Flash message fade out
            $('.notice[style*="display:block"]').each(function() {
                var notice = $(this);
                setTimeout(function() {
                    notice.fadeOut('slow');
                }, 5000);
            });
        });
    </script>

    <script>
    $(document).ready(function() {
        // Use event delegation for buttons loaded in a loop
        $(document).on('click', '.copy-btn', function() {
            var $this = $(this);
            var targetSelector = $this.data('clipboard-target');
            var textToCopy = $(targetSelector).text().trim();
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    // Success!
                    var originalIcon = $this.html();
                    $this.html('<i class="fas fa-check"></i> Copied!');
                    $this.addClass('copied');
                    
                    setTimeout(function() {
                        $this.html(originalIcon);
                        $this.removeClass('copied');
                    }, 2000);
                }, function(err) {
                    // Error (less common)
                    alert('Failed to copy code.');
                });
            } else {
                // Fallback for older/insecure browsers
                alert('Clipboard access is not available in your browser.');
            }
        });
    });
    </script>
    </body>
</html>
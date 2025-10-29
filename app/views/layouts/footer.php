    </main> <!-- Close main container -->

    <footer class="text-center text-sm bg-gradient-to-b from-white to-blue-50 text-blue-700 py-4 mt-10 border-t border-blue-300 shadow-inner">
        <p class="font-medium">
            &copy; <?php echo date('Y'); ?> <span class="font-semibold text-blue-800">Colegio de Naujan</span> LMS. All rights reserved.
        </p>

        <!-- Optional: Display framework info in development -->
        <?php if(config_item('ENVIRONMENT') === 'development'): ?>
            <p class="text-xs mt-2 text-blue-500">
                LavaLust v<?php echo config_item('VERSION'); ?> |
                Page rendered in {elapsed_time}s |
                Memory: {memory_usage}
            </p>
        <?php endif; ?>
    </footer>

    <!-- jQuery script for flash message fade out -->
    <script>
        $(document).ready(function() {
            // Fade out notices after 5 seconds
            $('.notice[style*="display:block"]').each(function() {
                var notice = $(this);
                setTimeout(function() {
                    notice.fadeOut('slow');
                }, 5000);
            });
        });
    </script>
</body>
</html>

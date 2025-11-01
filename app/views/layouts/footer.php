</div> </main> </div> <?php if (lava_instance()->session->has_userdata('user_id')): ?>
        <footer class="text-center text-sm bg-white text-gray-600 py-3 border-t border-gray-200" style="height: 50px;">
            <p class="font-medium">
                &copy; <?php echo date('Y'); ?> <span class="font-semibold text-cdn-blue">Colegio de Naujan</span> LMS. All rights reserved.
            </p>

            <?php if(config_item('ENVIRONMENT') === 'development'): ?>
                <p class="text-xs mt-1 text-gray-400">
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

            // === NEW: Sidebar Toggle Script ===
            $('#sidebar-toggle').on('click', function() {
                // Toggle the 'collapsed' class on the sidebar
                $('#app-sidebar').toggleClass('collapsed');
            });
        });
    </script>
</body>
</html>
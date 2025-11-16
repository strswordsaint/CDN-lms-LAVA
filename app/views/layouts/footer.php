</div> </main> </div> 
    
    <?php // ?>
    
    <?php if (lava_instance()->session->has_userdata('user_id')): ?>
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

    <div id="replies-modal-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40" style="display: none;"></div>
    <div id="replies-modal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 bg-white rounded-lg shadow-xl w-full max-w-2xl" style="display: none;">
        
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold text-neutral-800">
                Replies for: <span id="replies-modal-title" class="text-primary-700">...</span>
            </h2>
            <button id="replies-modal-close" class="text-neutral-500 hover:text-neutral-800">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        
        <div id="replies-modal-body" class="p-6 h-96 overflow-y-auto space-y-4">
            <div id="replies-modal-loader" class="text-center py-10">
                <i class="fas fa-spinner fa-spin fa-2x text-primary-600"></i>
            </div>
            
            <div id="replies-list" class="space-y-4"></div>
        </div>
        
        <div class="p-4 bg-neutral-50 border-t">
            <form id="replies-modal-form" class="flex items-start space-x-3">
                <input type="hidden" name="post_id" id="replies-modal-post-id" value="">
                <?php echo csrf_field(); ?>
                <textarea id="replies-modal-textarea" name="reply_content" rows="2" class="form-input flex-1" placeholder="Write a reply..." required></textarea>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <div id="confirmation-modal-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40" style="display: none;"></div>
    <div id="confirmation-modal" class="fixed top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 bg-white rounded-lg shadow-xl w-full max-w-md" style="display: none;">
        
        <div class="p-4 flex justify-end">
            <button class="confirmation-modal-close-btn text-neutral-400 hover:text-neutral-600">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        
        <div id="confirmation-modal-body" class="px-6 pb-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <i id="confirmation-modal-icon" class=""></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-neutral-900" id="confirmation-modal-title"></h3>
                    <p class="text-sm text-neutral-600 mt-1" id="confirmation-modal-text"></p>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-neutral-50 border-t flex justify-end space-x-3">
            <button id="confirmation-modal-cancel-btn" class="btn btn-secondary confirmation-modal-close-btn">
                Cancel
            </button>
            <button id="confirmation-modal-confirm-btn" class="btn btn-primary">
                Confirm
            </button>
        </div>
    </div>
    <script>
        // === MODIFIED: Global Modal Functions ===
        
        // Function to show the modal with custom options
        function showConfirmationModal(options) {
            // Set default values
            options = $.extend({
                title: 'Confirm Action',
                body: 'Are you sure you want to proceed?',
                confirmText: 'Confirm',
                cancelText: 'Cancel',
                confirmClass: 'btn-primary', // e.g., 'btn-danger', 'btn-primary'
                iconClass: 'fas fa-question-circle fa-3x text-primary-500', // Default icon
                onConfirm: function() { } // Default empty callback
            }, options);

            // 1. Update modal content
            $('#confirmation-modal-title').text(options.title);
            $('#confirmation-modal-text').html(options.body); // Use .html() to allow simple <p> tags
            
            // 2. Update Icon
            var $icon = $('#confirmation-modal-icon');
            if (options.iconClass) {
                $icon.attr('class', options.iconClass).show();
            } else {
                $icon.hide();
            }
            
            // 3. Update buttons
            var $confirmBtn = $('#confirmation-modal-confirm-btn');
            $confirmBtn.text(options.confirmText);
            // Reset classes and apply new one
            $confirmBtn.attr('class', 'btn ' + options.confirmClass); 
            
            $('#confirmation-modal-cancel-btn').text(options.cancelText);
            
            // 4. Unbind any previous 'click' handlers to prevent multiple fires
            $confirmBtn.off('click');
            
            // 5. Bind the new onConfirm callback
            $confirmBtn.on('click', function() {
                options.onConfirm();
                closeConfirmationModal(); // Close modal after confirm
            });
            
            // 6. Show the modal
            $('#confirmation-modal-backdrop').show();
            $('#confirmation-modal').show();
        }

        // Function to close the modal
        function closeConfirmationModal() {
            $('#confirmation-modal-backdrop').hide();
            $('#confirmation-modal').hide();
        }
        // === END: Global Modal Functions ===

        $(document).ready(function() {
            // Flash message fade out
            $('.notice[style*="display:block"]').each(function() {
                var notice = $(this);
                if (!notice.closest('#profile-menu-dropdown').length) {
                    setTimeout(function() {
                        notice.fadeOut('slow');
                    }, 5000);
                }
            });

            // Clipboard copy button
            $(document).on('click', '.copy-btn', function() {
                var $this = $(this);
                var targetSelector = $this.data('clipboard-target');
                var textToCopy = $(targetSelector).text().trim();
                
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        var originalIcon = $this.html();
                        $this.html('<i class="fas fa-check"></i> Copied!');
                        $this.addClass('copied');
                        setTimeout(function() {
                            $this.html(originalIcon);
                            $this.removeClass('copied');
                        }, 2000);
                    });
                } else {
                    alert('Clipboard access is not available in your browser.');
                }
            });
            
            // ===================================
            // === REPLIES MODAL SCRIPT ===
            // ===================================

            var $modal = $('#replies-modal');
            var $backdrop = $('#replies-modal-backdrop');
            var $loader = $('#replies-modal-loader');
            var $list = $('#replies-list');
            var $title = $('#replies-modal-title');
            var $form = $('#replies-modal-form');
            var $textarea = $('#replies-modal-textarea');
            var $postIdInput = $('#replies-modal-post-id');

            // Function to fetch and display replies
            function loadReplies(postId) {
                $list.empty();
                $loader.show();
                
                $.ajax({
                    url: '<?php echo site_url('/post/'); ?>' + postId + '/replies',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $loader.hide();
                        if (data.replies && data.replies.length > 0) {
                            $.each(data.replies, function(index, reply) {
                                var replyHtml = `
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-neutral-200 flex items-center justify-center">
                                            <i class="fas fa-user text-neutral-500"></i>
                                        </div>
                                        <div class="flex-1 bg-neutral-100 p-3 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-sm text-neutral-800">
                                                    ${escapeHTML(reply.first_name)} ${escapeHTML(reply.last_name)}
                                                    ${reply.role === 'teacher' ? '<span class="text-xs text-white bg-primary-600 px-2 py-0.5 rounded-full ml-1">Teacher</span>' : ''}
                                                </span>
                                                <span class="text-xs text-neutral-500">${formatReplyDate(reply.created_at)}</span>
                                            </div>
                                            <p class="text-sm text-neutral-700 mt-1">${escapeHTML(reply.content)}</p>
                                        </div>
                                    </div>
                                `;
                                $list.append(replyHtml);
                            });
                        } else {
                            $list.html('<p class="text-sm text-neutral-500 text-center">No replies yet. Be the first to comment!</p>');
                        }
                    },
                    error: function(xhr) {
                        $loader.hide();
                        $list.html('<p class="text-sm text-error-600 text-center">Error loading replies.</p>');
                    }
                });
            }

            // 1. Open Replies Modal
            $(document).on('click', '.btn-replies', function() {
                var postId = $(this).data('post-id');
                var postTitle = $(this).data('post-title');
                
                $title.text(postTitle);
                $postIdInput.val(postId);
                
                $modal.show();
                $backdrop.show();
                
                loadReplies(postId);
            });

            // 2. Close Replies Modal
            $('#replies-modal-close, #replies-modal-backdrop').on('click', function() {
                $modal.hide();
                $backdrop.hide();
                $list.empty();
                $textarea.val('');
                $title.text('...');
                $postIdInput.val('');
            });

            // 3. Submit New Reply
            $form.on('submit', function(e) {
                e.preventDefault();
                var postId = $postIdInput.val();
                var content = $textarea.val();
                var csrfToken = $(this).find('input[name="csrf_token"]').val();
                
                if (!content.trim()) return;

                $.ajax({
                    url: '<?php echo site_url('/post/'); ?>' + postId + '/reply',
                    type: 'POST',
                    data: {
                        reply_content: content,
                        csrf_token: csrfToken
                    },
                    dataType: 'json',
                    success: function(response) {
                        $textarea.val(''); // Clear textarea
                        loadReplies(postId); // Refresh the list
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON ? xhr.responseJSON.error : 'Failed to post reply.');
                    }
                });
            });

            // === NEW: Confirmation Modal Close Triggers ===
            // Close when clicking the 'X', the backdrop, or the 'Cancel' button
            $('#confirmation-modal-backdrop, .confirmation-modal-close-btn').on('click', function() {
                closeConfirmationModal();
            });
            
            // --- Helper Functions ---
            function escapeHTML(str) {
                if (!str) return '';
                return str.replace(/[&<>"']/g, function(m) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[m];
                });
            }
            
            function formatReplyDate(dateString) {
                var date = new Date(dateString);
                return date.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
            }

        });
    </script>
</body>
</html>
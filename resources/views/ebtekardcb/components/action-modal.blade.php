<!-- Action Confirmation Modal -->
<div id="action-modal" class="ebtekar-modal" style="display: none;">
    <div class="ebtekar-modal-backdrop" onclick="closeActionModal()"></div>
    <div class="ebtekar-modal-content animate-slide-up">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="action-modal-title" class="text-xl font-semibold text-gray-800">
                <!-- Title will be set dynamically -->
            </h3>
            <button onclick="closeActionModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>

                <p id="action-modal-message" class="text-gray-700 text-lg leading-relaxed">
                    <!-- Message will be set dynamically -->
                </p>
            </div>

            <div class="flex space-x-4 {{ ($locale ?? 'en') === 'ar' ? 'space-x-reverse' : '' }}">
                <button onclick="closeActionModal()"
                        class="flex-1 ebtekar-btn ebtekar-btn-outline">
                    @if(($locale ?? 'en') === 'ar')
                        إلغاء
                    @else
                        Cancel
                    @endif
                </button>

                <button id="action-confirm-btn"
                        onclick="confirmAction()"
                        class="flex-1 ebtekar-btn ebtekar-btn-primary">
                    <span id="action-confirm-text">
                        <!-- Text will be set dynamically -->
                    </span>
                    <div id="action-confirm-spinner" class="ebtekar-spinner hidden"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Action modal functions
    function openActionModal(title, message, confirmText) {
        document.getElementById('action-modal-title').textContent = title;
        document.getElementById('action-modal-message').textContent = message;
        document.getElementById('action-confirm-text').textContent = confirmText;

        document.getElementById('action-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeActionModal() {
        document.getElementById('action-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
        currentAction = null;
    }

    function confirmAction() {
        if (typeof executeAction === 'function') {
            setActionLoading(true);
            executeAction();
        }
    }

    function setActionLoading(isLoading) {
        const btn = document.getElementById('action-confirm-btn');
        const text = document.getElementById('action-confirm-text');
        const spinner = document.getElementById('action-confirm-spinner');

        btn.disabled = isLoading;
        if (isLoading) {
            text.classList.add('hidden');
            spinner.classList.remove('hidden');
            btn.classList.add('opacity-75');
        } else {
            text.classList.remove('hidden');
            spinner.classList.add('hidden');
            btn.classList.remove('opacity-75');
        }
    }

    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('ebtekar-modal-backdrop')) {
            closeActionModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeActionModal();
        }
    });
</script>
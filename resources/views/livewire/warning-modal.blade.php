<div>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">{{ $title }}</h2>
                <p class="text-gray-600">{{ $message }}</p>
                <div class="mt-6 flex justify-end space-x-2">
                    <button
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                        wire:click="close">
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

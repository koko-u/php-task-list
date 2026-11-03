@php
  $messageTypes = [
      'success' => 'bg-green-500 text-white',
      'error' => 'bg-red-500 text-white',
      'info' => 'bg-blue-500 text-white',
      'warning' => 'bg-yellow-300 text-gray-800'
 ];
@endphp
<div class="fixed top-4 right-4 z-50 flex flex-col gap-3">
    @foreach ($messageTypes as $type => $colors)
        @if (session()->has($type))
            <div
                x-data="{ visible: true }"
                x-init="setTimeout(() => visible = false, 4000)"
                x-show="visible"
                x-transition.opacity.duration.300ms
                class="rounded-lg px-5 py-3 shadow-lg {{ $colors }}"
                role="{{ in_array($type, ['error', 'warning']) ? 'alert' : 'status' }}"
            >
                {{ session($type) }}

                <button
                    type="button"
                    @click="visible = false"
                    class="ml-4"
                    aria-label="Close"
                >
                    &times;
                </button>
            </div>
        @endif
    @endforeach
</div>

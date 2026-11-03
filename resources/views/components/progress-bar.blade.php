@php
    use App\Features\Tasks\Models\TaskStatus;
    $color = match ($status) {
       TaskStatus::Pending => 'bg-amber-400',
       TaskStatus::InProgress=> 'bg-indigo-400',
       TaskStatus::Completed => 'bg-green-400',
    };
    $width = match ($status) {
       TaskStatus::Pending => 'w-2.5',
       TaskStatus::InProgress=> 'w-1/4',
       TaskStatus::Completed => 'w-3/4',
    };
@endphp
<div class="group relative inline-block w-full">
    <div class="flex-start flex h-2.5 w-full overflow-hidden rounded-full bg-blue-gray-50 font-sans text-xs font-medium"
         aria-describedby="help-tip"
    >
        <div
            class="flex h-full {{ $width }} items-center justify-center overflow-hidden break-all rounded-full {{ $color }} text-white"></div>
    </div>

    <div id="help-tip" role="tooltip"
         class="whitespace-nowrap pointer-events-none invisible absolute bottom-full left-1/2 mb-2 -translate-x-1/2 rounded bg-gray-800 px-3 py-2 text-white opacity-0 transition-opacity group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
        {{ $status->toLabel() }}
    </div>
</div>

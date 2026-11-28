<a href="{{ route('tech-corner.show', $article['slug']) }}" class="tilt-card rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group flex flex-col" data-tilt>
    <div class="h-48 bg-[#F8FAFC] overflow-hidden relative shrink-0">
        <img
            src="{{ asset($article['image']) }}"
            alt="{{ $article['title'] }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
            onerror="this.src='/images/placeholder.svg'"
        >
    </div>
    <div class="p-5 space-y-2 flex-1 flex flex-col justify-between">
        <div class="space-y-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                {{ $article['category_label'] }}
            </span>
            <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                {{ $article['title'] }}
            </h3>
            @if(! empty($showExcerpt))
                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                    {{ $article['excerpt'] }}
                </p>
            @endif
        </div>
        <div class="pt-3 text-[11px] text-slate-400 font-medium flex items-center justify-between">
            <span>{{ $article['read_time'] }} &bull; {{ $article['tag_label'] }}</span>
            <span class="group-hover:text-blue-600 transition-colors">&nearr;</span>
        </div>
    </div>
</a>

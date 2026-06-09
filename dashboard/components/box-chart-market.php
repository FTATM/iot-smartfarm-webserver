<div
    class="col-span-3 bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
    <div class="flex justify-between items-center mb-2 shrink-0">
        <div>
            <h2
                class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                <span class="w-[0.25vw] h-4 2xl:h-6 bg-primary rounded-full"></span>
                แนวโน้มราคาตลาด
            </h2>
            <p
                class="text-[0.5vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
                Market Price Trend
            </p>
        </div>
        <div id="types-MarketChart" class="flex items-center gap-2 flex-wrap"></div>
    </div>
    <div
        class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md overflow-hidden">
        <canvas id="marketPriceChart" class="absolute inset-0 w-full h-full"></canvas>
    </div>
</div>
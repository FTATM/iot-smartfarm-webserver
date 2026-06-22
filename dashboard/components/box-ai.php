<div class="col-span-<?php echo $col ?? 2; ?> bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-700
                            rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400
                            transition-all duration-200">

    <!-- Header AI -->
    <div class="flex flex-col items-center pb-2.5 border-b border-stone-200 dark:border-stone-700 flex-shrink-0">
        <div class="flex justify-between w-full items-center">
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[1vw] font-semibold text-stone-800 dark:text-stone-100">AI Assistant</span>
            </div>

            <!-- Mode badge -->
            <span id="ai-mode-badge"
                class="text-[0.5vw] px-1.5 py-0.5 rounded-full font-medium">
                -
            </span>
        </div>

        <div class="flex gap-3 justify-start w-full items-center">
            <!-- Model badge -->
            <span id="ai-model-badge"
                class="text-[0.5vw] px-1.5 py-0.5 rounded-full bg-stone-100 dark:bg-stone-700 text-stone-500 dark:text-stone-300 font-mono">
                -
            </span>

        </div>

    </div>

    <!-- Messages -->
    <div id="chatMessages"
        class="flex-1 overflow-y-auto py-3 flex flex-col gap-3 scrollbar-thin scrollbar-thumb-stone-200 dark:scrollbar-thumb-stone-700">
        <!-- AI welcome -->
        <div class="flex items-end gap-2">
            <div
                class="w-6 h-6 rounded-full bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 flex items-center justify-center text-[0.5vw] font-semibold text-stone-500 flex-shrink-0">
                AI</div>
            <div
                class="max-w-[78%] px-3 py-2 rounded-2xl rounded-bl-sm bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-stone-100 text-[0.65vw] leading-relaxed">
                สวัสดีครับ ผมช่วยอะไรได้บ้าง?
            </div>
        </div>
    </div>

    <!-- Input -->
    <div
        class="flex items-center gap-2 pt-2.5 border-t border-stone-200 dark:border-stone-700 flex-shrink-0">
        <textarea id="chatInput" rows="1" placeholder="พิมพ์ข้อความ..."่
            onkeydown="chatOnKey(event)" oninput="chatResize(this)"
            class="flex-1 bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl px-3 py-2 text-[0.5vw] text-stone-800 dark:text-stone-100 placeholder-stone-400 resize-none outline-none leading-relaxed max-h-[90px] focus:border-orange-400 dark:focus:border-orange-500 transition-colors duration-150"></textarea>
        <button id="chatSend" onclick="chatSend()"
            class="w-[1vw] h-[1vw] rounded-full bg-orange-500 hover:bg-orange-600 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center flex-shrink-0 transition-all duration-150">
            <svg class="w-3.5 h-3.5 fill-white" viewBox="0 0 24 24">
                <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
            </svg>
        </button>
    </div>
</div>
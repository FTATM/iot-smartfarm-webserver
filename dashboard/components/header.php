<!-- Header -->
<header
    class="flex items-center p-[0.5vw] border-b border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 shrink-0 transition-colors duration-300">
    <div class="flex items-center gap-3">
        <?php include 'navbar.php'; ?>
        <div class="size-9 md:size-10 lg:size-11 xl:size-12 2xl:size-14 bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20"
            style="width: clamp(2.25rem, 3.5vw, 5.5rem); height: clamp(2.25rem, 3.5vw, 5.5rem);">
            <span class="<?php echo $classIconHeader; ?> text-white"
                style="font-size: clamp(1.25rem, 2vw, 5rem);"></span>
        </div>
        <div>
            <h1 class="text-[#1d130c] dark:text-orange-300 text-[1.5vw] font-bold leading-none">
                <?php echo isset($Title) ? $Title : "Not Set Name in param \$Title"; ?>
            </h1>
            <p class="text-[0.75vw] text-stone-500 dark:text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                <?php echo isset($subTitle) ? $subTitle : "Not Set Name in param \$subTitle"; ?>
            </p>
        </div>
    </div>

    <div class="flex items-center gap-4 ml-auto">
        <!-- Dark Mode Toggle Button -->
        <div class="flex flex-col items-end">
            <button onclick="toggleDarkMode()"
                class="flex items-center gap-2 bg-stone-100 hover:bg-stone-200 dark:bg-stone-700 dark:hover:bg-stone-600 border border-stone-200 dark:border-stone-600 px-3 py-2 rounded-xl transition-all duration-200">
                <!-- Moon icon (แสดงตอน Light mode) -->
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="text-stone-600 dark:text-stone-300"
                    style="width: clamp(1rem, 1.5vw, 2.5rem); height: clamp(1rem, 1.5vw, 2.5rem);" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                </svg>

                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="text-yellow-400"
                    style="display:none; width: clamp(1rem, 1.5vw, 2.5rem); height: clamp(1rem, 1.5vw, 2.5rem);"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                <span id="mode-label" class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300">Dark</span>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <div
                class="flex flex-col items-center gap-2 bg-stone-100 dark:bg-stone-800 box p-1 px-3 rounded-xl border border-stone-200 dark:border-stone-700">
                <div class="flex items-center gap-2">
                    <span class="text-[0.75vw] font-bold text-primary leading-none">รอบการเลี้ยง:</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none" id="day-age">--</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none">วัน</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[0.75vw] font-bold text-primary leading-none">อายุปัจจุบัน:</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none" id="day-age">--</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none">วัน</span>
                    <div class="w-px h-2.5 bg-stone-300 dark:bg-stone-600"></div>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">เหลือ</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none"
                        id="remain-live">-</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">ตัว</span>
                </div>
                <div class="h-px w-full bg-stone-300 dark:bg-stone-600"></div>
                <div class="flex items-center gap-2">
                    <span class="text-[0.75vw] font-bold text-primary leading-none">คาดการณ์รายได้:</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none" id="forecast-income">--</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none">บาท</span>
                    <div class="w-px h-2.5 bg-stone-300 dark:bg-stone-600"></div>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">น้ำหนัก</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none"
                        id="kilogram">-</span>
                    <span
                        class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">กิโล/ตัว</span>
                </div>
            </div>
            <div class="flex flex-col items-end border-l border-stone-200 dark:border-stone-700 pl-4">
                <span
                    class="text-[0.75vw] font-bold text-stone-400 uppercase tracking-widest leading-none mb-0.5">อัปเดตล่าสุด</span>
                <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 text-center" id="last-update">
                    --:--:--</span>
                <span class="text-[0.75vw] text-stone-600 dark:text-stone-300 font-bold leading-none" id="start-date">
                    -- --- ---- </span>
            </div>
        </div>
    </div>
</header>
<script>
module.exports = {
    theme: {
        extend: {
            screens: {
                '3xl': '1920px',
                '4xl': '2560px',
                '5xl': '3840px',
            }
        }
    }
}
</script>
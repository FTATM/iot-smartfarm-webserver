<!DOCTYPE html>
<html class="light" lang="en">
<?php include(__DIR__ . "/dashboard/scripts/ref.html"); ?>
<?php include(__DIR__ . "/dashboard/styles/css-default.html"); ?>
<?php include(__DIR__ . "/dashboard/styles/css-icon.html"); ?>

<head>
  <title>Menu</title>
</head>


<body class="bg-white dark:bg-black text-white h-screen flex items-center justify-center overflow-hidden relative">
  <!-- Background Elements -->
  <div class="fixed inset-0 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-[#0d4c4f]/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#0d4c4f]/10 rounded-full blur-[120px]"></div>
  </div>

  <div class="relative z-10 w-full max-w-5xl px-8 flex justify-around items-center">

    <!-- Dashboard Card -->
    <a href="/iotsf/dashboard/" class="glass-card group w-[40%] aspect-[4/3] rounded-3xl overflow-hidden flex flex-col cursor-pointer">
      <div class="flex-1 flex flex-col justify-center items-center p-10 space-y-6">
        <div class="p-4 rounded-2xl bg-[#0d4c4f]/20 group-hover:bg-[#0d4c4f]/40 group-hover:dark:bg-[#00F2FF]/40 transition-colors duration-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#2dd4bf]">
            <rect width="7" height="9" x="3" y="3" rx="1"></rect>
            <rect width="7" height="5" x="14" y="3" rx="1"></rect>
            <rect width="7" height="9" x="14" y="12" rx="1"></rect>
            <rect width="7" height="5" x="3" y="15" rx="1"></rect>
          </svg>
        </div>
        <div class="space-y-3">
          <h2 class="text-2xl font-bold tracking-tight text-stone-800 dark:text-white text-center">Dashboard</h2>
          <p class="text-stone-400 text-sm leading-relaxed max-w-[200px] group-hover:text-stone-600 group-hover:dark:text-stone-200 transition-colors">
            View real-time analytics and system status.
          </p>  
        </div>
        <div class="w-12 h-1 bg-[#2dd4bf]/30 rounded-full group-hover:w-24 group-hover:bg-[#2dd4bf] transition-all duration-500"></div>
      </div>
    </a>

    <!-- Configuration Card -->
    <a href="/iotsf/dashboard_view_v3.php" class="glass-card group w-[40%] aspect-[4/3] rounded-3xl overflow-hidden flex flex-col cursor-pointer">
      <div class="flex-1 flex flex-col justify-center items-center p-10 space-y-6">
        <div class="p-4 rounded-2xl bg-[#0d4c4f]/20 group-hover:bg-[#0d4c4f]/40 transition-colors duration-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#2dd4bf]">
            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </div>
        <div class="space-y-3">
          <h2 class="text-2xl font-bold tracking-tight text-stone-800 dark:text-white text-center">Configuration</h2>
          <p class="text-stone-400 text-sm leading-relaxed max-w-[200px] group-hover:text-stone-600 group-hover:dark:text-stone-200 dark:hover:text-stone-600 transition-colors">
            Manage device settings and parameters.
          </p>
        </div>
        <div class="w-12 h-1 bg-[#2dd4bf]/30 rounded-full group-hover:w-24 group-hover:bg-[#2dd4bf] transition-all duration-500"></div>
      </div>
    </a>
  </div>
  <div class="absolute top-0 right-0">
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
        <!-- <span id="mode-label" class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300">Dark</span> -->
      </button>
    </div>
  </div>



  <script>
    function toggleDarkMode() {
      const isDark = document.documentElement.classList.toggle("dark");
      document.documentElement.classList.toggle("light", !isDark);
      document.getElementById("icon-moon").style.display = isDark ?
        "none" :
        "inline";
      document.getElementById("icon-sun").style.display = isDark ?
        "inline" :
        "none";
      document.getElementById("mode-label").textContent = isDark ?
        "Light" :
        "Dark";
      localStorage.setItem("theme", isDark ? "dark" : "light");
    }
  </script>
</body>

</html>
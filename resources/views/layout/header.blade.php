
<header class="bg-white border-b border-gray-200 px-4 py-4 flex items-center justify-between">
    <div class="hidden md:block">
        <button @click="toggleSidebar" class="text-gray-600 hover:text-black"
            :class="sidebarOpen ? 'hidden' : 'block'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-panel-right-open-icon lucide-panel-right-open">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path d="M15 3v18" />
                <path d="m10 15-3-3 3-3" />
            </svg>
        </button>
    </div>
    <div class="block md:hidden">
        <button @click="toggleSidebarMobile" class="text-gray-600 hover:text-black"
            :class="sidebarOpen ? 'hidden' : 'block'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-panel-right-open-icon lucide-panel-right-open">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path d="M15 3v18" />
                <path d="m10 15-3-3 3-3" />
            </svg>
        </button>
    </div>

    <div class="text-lg font-semibold">Dashboard</div>
    <a class="text-sm text-gray-500" href="{{ url('/logout') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out">
            <path d="m16 17 5-5-5-5" />
            <path d="M21 12H9" />
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
        </svg>
    </a>
</header>

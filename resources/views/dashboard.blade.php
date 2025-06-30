@extends('layout.base')
@section('title', 'Dashboard')
@section('content')
    <div x-data="layout()" x-init="init()" class="flex h-screen">

        {{-- Sidebar --}}
        <aside x-show="sidebarOpen"
            class="absolute md:relative h-screen w-64 bg-gray-800 text-white flex-shrink-0 transition-all duration-300">
            <div class="flex items-center justify-between mx-3 text-white">
                <div class="p-4 font-bold text-xl border-b border-gray-700">Menu</div>
                <button @click="toggleSidebar" class="hover:text-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-app-window-icon lucide-app-window">
                        <rect x="2" y="4" width="20" height="16" rx="2" />
                        <path d="M10 4v4" />
                        <path d="M2 8h20" />
                        <path d="M6 4v4" />
                    </svg>
                </button>
            </div>
            <nav class="p-4 space-y-2">
                <a href="#" class="block px-4 py-2 hover:bg-gray-700 rounded">Home</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-700 rounded">Reports</a>
                <a href="#" class=" px-4 py-2 hover:bg-gray-700 rounded flex gap-1 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-settings-icon lucide-settings">
                        <path
                            d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <div>Settings</div>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Navbar --}}
            <header class="bg-white border-b border-gray-200 px-4 py-4 flex items-center justify-between">
                <div class="hidden md:block">
                    <button @click="toggleSidebar" class="text-gray-600 hover:text-black"
                        :class="sidebarOpen ? 'hidden' : 'block'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-panel-left-icon lucide-panel-left">
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                            <path d="M9 3v18" />
                        </svg>
                    </button>
                </div>
                <div class="block md:hidden">
                    <button @click="toggleSidebarMobile" class="text-gray-600 hover:text-black"
                        :class="sidebarOpen ? 'hidden' : 'block'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-panel-left-icon lucide-panel-left">
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                            <path d="M9 3v18" />
                        </svg>
                    </button>
                </div>

                <div class="text-lg font-semibold">Dashboard</div>
                <a class="text-sm text-gray-500" href="{{ url('/logout') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-log-out-icon lucide-log-out">
                        <path d="m16 17 5-5-5-5" />
                        <path d="M21 12H9" />
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    </svg>
                </a>
            </header>

            <main class="flex-1 overflow-y-auto py-6" @click="closeSidebarOnMobile">
                <div class="text-gray-800 text-lg bg-gray-50 rounded-2xl shadow-xl max-w-3xl mx-auto">
                    <div x-data='dataTable(@json($users, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))' class="p-4 max-w-4xl mx-auto">
                        <div class="mb-4 flex justify-between">
                            <input type="text" x-model="search" placeholder="Search..."
                                class="border border-gray-900 bg-gray-50 ring-0 rounded-xl p-1 w-full md:w-1/3" />
                        </div>

                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th @click="sortBy('name')" class="cursor-pointer px-4 py-2">Name</th>
                                    <th @click="sortBy('email')" class="cursor-pointer px-4 py-2">Email</th>
                                    <th @click="sortBy('age')" class="cursor-pointer px-4 py-2">Age</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in paginatedData()" :key="row.id">
                                    <tr class="border-t">
                                        <td class="px-4 py-2" x-text="row.name"></td>
                                        <td class="px-4 py-2" x-text="row.email"></td>
                                        <td class="px-4 py-2" x-text="row.age"></td>
                                    </tr>
                                </template>
                                <tr x-show="filteredData().length === 0">
                                    <td colspan="3" class="text-center px-4 py-2 text-gray-500">No results found.</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="flex justify-between items-center mt-4">
                            <button @click="prevPage()" :disabled="currentPage === 1"
                                class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200 disabled:opacity-50">Prev</button>

                            <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages()"></span></span>

                            <button @click="nextPage()" :disabled="currentPage === totalPages()"
                                class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200 disabled:opacity-50">Next</button>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
@endsection

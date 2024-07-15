<nav class="bg-light-gray">
    <div class="container mx-auto px-2 sm:px-4 py-3 sm:py-2">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <div class="flex justify-center mb-8 sm:mb-0 w-full sm:w-auto">
                <a href="/">
                    <img src="{{ url('logo.svg') }}" alt="Logo" class="h-12">
                </a>
            </div>
            <ul class="flex justify-center space-x-5 sm:space-x-6 w-full sm:w-auto" id="menu">
                <li class="flex items-center py-2 sm:py-2 relative hover-underline {{ Request::is('/') ? 'active' : '' }}">
                    <a href="/" class="flex items-center text-dark-gray text-sm">
                        <img src="{{ url('home.svg') }}" alt="Home Icon" class="h-4 mr-2">
                        <span>Home</span>
                    </a>
                </li>
                <li class="flex items-center py-2 sm:py-2 relative hover-underline {{ Request::is('jadwal') ? 'active' : '' }}">
                    <a href="/jadwal" class="flex items-center text-dark-gray text-sm">
                        <img src="{{ url('date.svg') }}" alt="Date Icon" class="h-4 mr-2">
                        <span>Jadwal Praktik</span>
                    </a>
                </li>
                <li class="flex items-center py-2 sm:py-2 relative hover-underline {{ Request::is('info') ? 'active' : '' }}">
                    <a href="/info" class="flex items-center text-dark-gray text-sm">
                        <img src="{{ url('info.svg') }}" alt="Info Icon" class="h-4 mr-2">
                        <span>Info Kuota</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
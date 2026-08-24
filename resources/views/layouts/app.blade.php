<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hydroponic Environment Control</title>

     <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#eef2ec] font-sans overflow-x-hidden">

<div class="flex min-h-screen">

    <!-- 🌱 SIDEBAR -->
<div id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 h-screen
           bg-[#546B41] text-white px-5 py-8
           flex flex-col shadow-xl overflow-y-auto
           transform -translate-x-full transition-transform duration-300
          lg:translate-x-0 lg:sticky lg:top-0 lg:flex lg:shrink-0">

        <!-- LOGO -->
       <div class="flex items-center gap-5 mb-16 pl-2">
            <img src="{{ asset('images/logo.png') }}"
                class="w-14 h-14 bg-white rounded-full p-1 shadow-md">

            <div>
                <h2 class="text-sm font-semibold">Hydroponic</h2>
                <p class="text-xs text-white/70">Environment Control System</p>
            </div>
        </div>

        <!-- MENU -->
       <ul class="space-y-2 text-[15px] font-medium">

          <li>
    <a href="/dashboard"
class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->is('dashboard')
            ? 'bg-white/20 font-semibold'
            : 'hover:bg-white/15' }}">

        <i class="fas fa-chart-line w-6 text-center shrink-0"></i>
        <span class="ml-1">Dashboard</span>

    </a>
</li>

<li>
    <a href="{{ route('predictive.monitoring') }}"

class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->routeIs('predictive.monitoring')
    ? 'bg-white/20 font-semibold'
    : 'hover:bg-white/15' }}">

        <i class="fas fa-triangle-exclamation w-6 text-center shrink-0"></i>

        <span>
            Predictive Monitoring
        </span>

    </a>
</li>

<li>
    <a href="{{ route('simulator') }}"
class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->routeIs('simulator')
            ? 'bg-white/20 font-semibold'
            : 'hover:bg-white/15' }}">

        <i class="fas fa-sliders w-6 text-center shrink-0"></i>

        <span>Simulator</span>

    </a>
</li>

 <li>
    <a href="{{ route('staff.settings') }}"
class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->routeIs('staff.settings') ? 'bg-white/20 font-medium' : 'hover:bg-white/20' }}">

        <i class="fas fa-user-gear w-6 text-center shrink-0"></i>
        Profile Settings

    </a>
</li>

<li>

<a href="{{ route('change.password') }}"

class="flex items-center gap-4 px-4 py-3 rounded-xl
{{ request()->routeIs('change.password')
? 'bg-white/20 font-medium'
: 'hover:bg-white/20' }}">

<i class="fas fa-key w-6 text-center shrink-0"></i>

Change Password

</a>

</li>

    @if(auth()->user()->role === 'admin')

<li>
    <a href="{{ route('admin.manage.staff') }}"
class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->routeIs('admin.manage.staff')
            ? 'bg-white/20 font-semibold'
            : 'hover:bg-white/15' }}">

        <i class="fas fa-users w-6 text-center shrink-0"></i>
<span class="ml-1">Manage Staff</span>

    </a>
</li>

 <li>

    <li>
   <a href="{{ route('admin.crop.database') }}"
class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->routeIs(
            'admin.crop.database',
            'admin.add.crop',
            'admin.store.crop',
            'admin.edit.crop',
            'admin.update.crop',
            'admin.delete.crop'
        )
            ? 'bg-white/20 font-semibold'
            : 'hover:bg-white/15' }}">

        <i class="fas fa-seedling w-6 text-center shrink-0"></i>
<span class="ml-1">Manage Crop</span>

    </a>
</li>

@endif


<li>
    <a href="{{ route('sensor.data') }}"
class="flex items-center gap-4 px-4 py-3 rounded-xl
       {{ request()->routeIs('sensor.data')
    ? 'bg-white/20 font-semibold'
    : 'hover:bg-white/15' }}">
    
        <i class="fas fa-microchip w-6 text-center shrink-0"></i>

        <span class="leading-[1.4]">
    Sensor & Actuator Records
</span>

    </a>
</li>

          @if(auth()->user()->role === 'admin')

<li>
    <a href="{{ route('admin.audit.trail') }}"
class="flex items-center gap-4 px-4 py-3 rounded-xl
{{ request()->routeIs('admin.audit.trail')
? 'bg-white/20 font-semibold'
: 'hover:bg-white/15' }}">

        <i class="fas fa-clipboard-list w-6 text-center shrink-0"></i>

        Audit Trail

    </a>
</li>

@endif


        </ul>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" class="mt-12">
            @csrf
            <button class="w-full bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg text-sm transition">
                Logout
            </button>
        </form>

        <!-- USER INFO -->
<div class="mt-auto pt-6 border-t border-white/20 text-sm">

    <p class="text-white/70">Logged in as</p>

    <p class="font-semibold">
        {{ Auth::user()->name }}
    </p>

    @if(auth()->user()->role === 'admin')

        <p class="text-xs text-green-200 mt-1 font-medium">
            Role: Administrator
        </p>

    @else

        <p class="text-xs text-blue-200 mt-1 font-medium">
            Role: Monitoring Staff
        </p>

    @endif

    <p class="text-xs text-white/60 mt-1">
        ID: {{ auth()->user()->staff->staff_id ?? 'N/A' }}
    </p>

</div>

    </div>

<!-- 📱 MOBILE OVERLAY -->
<div
    id="sidebarOverlay"
    onclick="toggleSidebar()"
    class="fixed inset-0 z-40 bg-black/40 hidden lg:hidden">
</div>

<!-- 📊 MAIN -->
<div class="flex-1 min-w-0 w-full p-4 sm:p-6">

    <!-- 📱 MOBILE HEADER -->
    <div class="lg:hidden flex items-center justify-between mb-4">
        <button
            onclick="toggleSidebar()"
            class="p-2 rounded-lg bg-[#546B41] text-white shadow">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <h1 class="text-sm font-semibold text-[#546B41]">
            Hydroponic Environment Control
        </h1>
    </div>

    {{ $slot }}

</div>


</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>

</body>
</html>
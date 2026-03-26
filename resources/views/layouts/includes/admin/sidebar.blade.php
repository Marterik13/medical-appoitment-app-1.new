@php
   $links = [
      [
         'name' => 'Dashboard',
         'icon' => 'fa-solid fa-gauge',
         'href' => route('admin.dashboard'),
         'active' => request()->routeIs('admin.dashboard')
      ],
      [
         'header' => 'Gestión',
      ],
      [
         'name' => 'Roles y Permisos',
         'icon' => 'fa-solid fa-shield-halved',
         'href' => route('admin.roles.index'),
         'active' => request()->routeIs('admin.roles.*'),
      ],
      [
         'name' => 'Usuarios',
         'icon' => 'fa-solid fa-users',
         'href' => route('admin.users.index'),
         'active' => request()->routeIs('admin.users.*'),
      ],
           [
      'name' => 'Pacientes',
      'icon' => 'fa-solid  fa-user-injured',
      'href' => route('admin.patients.index'),
      'active' => request()->routeIs('admin.patients.*'),
   ],
   
   ];
@endphp

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
      <a href="/" class="flex items-center ps-2.5 mb-5">
         <img src="{{ asset('imagenes/ejemplo.jpg') }}" class="h-6 me-3" alt="Logo" />
         <span class="self-center text-lg font-semibold whitespace-nowrap text-gray-800">Healtify</span>
      </a>
      <ul class="space-y-2 font-medium">
         @foreach ($links as $link)
         <li>
            @isset($link['header'])
               <div class="px-2 py-2 text-xs font-semibold text-gray-400 uppercase">
                  {{ $link['header'] }}
               </div>
            @else
               @isset($link['submenu'])
                  @php $id = Str::slug($link['name']); @endphp
                  <button type="button" class="flex items-center w-full justify-between px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-100 group" data-collapse-toggle="dropdown-{{ $id }}">
                     <span class="w-6 h-6 inline-flex items-center justify-center text-gray-500">
                        <i class="{{ $link['icon'] }}"></i>
                     </span>
                     <span class="flex-1 ms-3 text-left whitespace-nowrap">{{ $link['name'] }}</span>
                     <i class="fa-solid fa-chevron-down text-xs"></i>
                  </button>
                  <ul id="dropdown-{{ $id }}" class="hidden py-2 space-y-2">
                     @foreach ($link['submenu'] as $item)
                        <li>
                           <a href="{{ $item['href'] }}" class="pl-11 flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100">
                              {{ $item['name'] }}
                           </a>
                        </li>
                     @endforeach
                  </ul>
               @else
                  <a href="{{ $link['href'] }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-100 {{ $link['active'] ? 'bg-gray-100 font-bold' : '' }}">
                     <span class="w-6 h-6 inline-flex items-center justify-center text-gray-500">
                        <i class="{{ $link['icon'] }}"></i>
                     </span>
                     <span class="flex-1 ms-3 whitespace-nowrap">{{ $link['name'] }}</span>
                  </a>
               @endisset
            @endisset
         </li>
         @endforeach
      </ul>
   </div>
</aside>
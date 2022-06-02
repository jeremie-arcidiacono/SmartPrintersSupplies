<nav class="d-flex flex-column flex-shrink-0 p-3 bg-light float-start" style="width: 17%; position: fixed; height: 100%;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <x-application-logo class="bi pe-none me-2" width="40" height="32"/>
        <span class="fs-4">Smart Printers Supplies</span>
    </a>
    <hr />
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <x-nav-link :href="route('printers.index')" :active="request()->routeIs('printers.index')" logoName="printer-fill">
              {{ __('Imprimantes') }}
            </x-nav-link>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
              <x-nav-link :href="route('models.index')" :active="request()->routeIs('models.index')" logoName="bounding-box-circles">
                {{ __('Modèles d\'imprimantes') }}
              </x-nav-link>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
              <x-nav-link :href="route('printers.index')" :active="request()->routeIs('printers.index')" logoName="printer-fill">
                {{ __('Imprimantes') }}
              </x-nav-link>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
              <x-nav-link :href="route('printers.index')" :active="request()->routeIs('printers.index')" logoName="printer-fill">
                {{ __('Imprimantes') }}
              </x-nav-link>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#people-circle"></use>
                </svg>
                Customers
            </a>
        </li>
    </ul>
    <hr />
    <div>
        <div class="ps-2">
            <i class="bi bi-person-circle"></i>
            <strong>{{ Auth::user()->username }}</strong>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-dropdown-link :href="route('logout')"
                  onclick="event.preventDefault();
                              this.closest('form').submit();">
              {{ __('Log Out') }}
          </x-dropdown-link>
      </form>
    </div>
  </nav>

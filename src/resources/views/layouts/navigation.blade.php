<nav class="d-flex flex-column flex-shrink-0 p-3 bg-light float-start bg-white shadow" style="width: 17%; position: fixed; height: 100%;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <x-application-logo class="bi pe-none me-2" width="40" height="32"/>
        <span class="fs-4">Smart Printers Supplies</span>
    </a>
    <hr />
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="mb-2 py-1">
            <x-nav-link :href="route('printers.index')" :active="request()->routeIs('printers.index')" logoName="printer-fill">
              {{ __('Imprimantes') }}
            </x-nav-link>
        </li>
        <li class="mb-2 py-1">
            <x-nav-link :href="route('models.index')" :active="request()->routeIs('models.index')" logoName="bounding-box-circles">
              {{ __('Modèles d\'imprimantes') }}
            </x-nav-link>
        </li>
        <li class="mb-2 py-1">
            <x-nav-link :href="route('supplies.index')" :active="request()->routeIs('supplies.index')" logoName="box-seam">
              {{ __('Consommables') }}
            </x-nav-link>
        </li>
        <li class="mb-2 py-1 ps-1">
          <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 py-0" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="true">
            <i class="bi bi-card-list me-1" width="16" height="16"></i>
            Historique
          </button>
          <div class="collapse" id="orders-collapse" style="">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
              <li class="ms-3">
                <x-nav-link :href="route('events.consumption')" :active="request()->routeIs('events.consumption')">
                {{ __(' Consommables') }}
                </x-nav-link>
              </li>
              <!--<li class="ms-3">
                <x-nav-link :href="route('events.object')" :active="request()->routeIs('events.object')">
                  {{ __(' Créations/suppressions') }}
                  </x-nav-link>
                </li>-->
            </ul>
          </div>
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

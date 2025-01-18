<!--begin::User Menu Dropdown-->
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
      <img
        src="{!! asset(config('layout.auth_logo.img.path')) !!}"
        class="user-image rounded-circle shadow"
        alt="User Image"
      />
      <span class="d-none d-md-inline">{{optional(auth()->user())->name}}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
      <!--begin::User Image-->
      <li class="user-header text-bg-primary">
        <img
          src="{!! asset(config('layout.auth_logo.img.path')) !!}"
          class="rounded-circle shadow"
          alt="User Image"
        />
        <p>
          {{optional(auth()->user())->name}}
          <small>{{ __('messages.member_since', ['date' => optional(auth()->user())->created_at?->format('M Y')]) }}</small>
        </p>
      </li>
      <!--end::User Image-->
      <!--begin::Menu Body-->
      <li class="user-body">
        <!--begin::Row-->
        {{-- <div class="row">
          <div class="col-4 text-center"><a href="#">Option1</a></div>
          <div class="col-4 text-center"><a href="#">Option2</a></div>
          <div class="col-4 text-center"><a href="#">Option3</a></div>
        </div> --}}
        <!--end::Row-->
      </li>
      <!--end::Menu Body-->
      <!--begin::Menu Footer-->
      <li class="user-footer">
        <a href="#" class="btn btn-default btn-flat">{{ __('messages.profile') }}</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-default btn-flat float-end">{{ __('messages.sign_out') }}</button>
        </form>
      </li>
      <!--end::Menu Footer-->
    </ul>
  </li>
  <!--end::User Menu Dropdown-->

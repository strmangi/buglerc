<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a href="{{url('admin/users')}}" class="brand-link">

      <img src="{{asset('admin_asset/dist/img/admin.jpg')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"

           style="opacity: .8">

      <span class="brand-text font-weight-light">Bugler Coaches</span>

    </a>

    <!-- Sidebar -->

    <div class="sidebar">

      <!-- Sidebar user panel (optional) -->

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">

        <div class="image">

          <img src="{{asset('admin_asset/dist/img/admin-user.jpg')}}" class="img-circle elevation-2" alt="User Image">

        </div>

        <div class="info">

          <a href="#" class="d-block">{{ucfirst($currentUser->name)}}</a>

        </div>

      </div>



      <!-- Sidebar Menu -->

      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <!-- Add icons to the links using the .nav-icon class

               with font-awesome or any other icon font library -->

          <li class="nav-item has-treeview menu-open">

            <a href="{{url('admin/users')}}" class="nav-link {{ (request()->is('admin')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-tachometer-alt"></i>

              <p>

                Dashboard

              </p>

            </a>

            

          </li>

          {{-- Coach type START --}}

          @can('manage-user')

          <li class="nav-item">

            <a href="{{route('admin.type.index')}}" class="nav-link {{ (request()->is('admin/type*')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-bars"></i>&nbsp;&nbsp;

              <p>Coach Types</p>

            </a>

          </li>

          @endcan

          {{-- Coach Type END --}}

          {{-- Coach START --}}

          @can('manage-user')

          <li class="nav-item">

            <a href="{{route('admin.coach.index')}}" class="nav-link {{ (request()->is('admin/coach*')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-bus"></i>&nbsp;&nbsp;

              <p>Coach</p>

            </a>

          </li>

          @endcan

          {{-- Coach END --}}

          {{-- user deta --}}

          <li class="nav-item has-treeview {{ (request()->is('admin/drivers*')) ? 'menu-open' : '' }} {{ (request()->is('admin/customer*')) ? 'menu-open' : '' }}">

            <a href="#" class="nav-link">

              <i class="nav-icon fas fa-users-cog"></i>

              <p>

                Users

                <i class="fas fa-angle-left right"></i>

              </p>

            </a>

            <ul class="nav nav-treeview">

              <li class="nav-item">

                <a href="{{url('admin/drivers')}}" class="nav-link {{ (request()->is('admin/drivers*')) ? 'active' : '' }}">

                  <i class="fas fa-biking nav-icon"></i>

                  <p>Drivers</p>

                </a>

              </li>

              <li class="nav-item">

                <a href="{{route('admin.customer.index')}}" class="nav-link {{ (request()->is('admin/customer*')) ? 'active' : '' }}">

                  <i class="fas fa-user-tie nav-icon"></i>

                  <p>Customers</p>

                </a>

              </li>

              

            </ul>

          </li>

          {{-- user deta --}}

          {{-- Coach type START --}}

          @can('manage-user')

          <li class="nav-item">

            <a href="{{route('admin.inquiry.index')}}" class="nav-link {{ (request()->is('admin/inquiry')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-headset"></i>&nbsp;&nbsp;

              <p>Enquiries</p>

            </a>

          </li>
           <li class="nav-item">

            <a href="{{url('admin/inquiry_school')}}" class="nav-link {{ (request()->is('admin/inquiry_school')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-headset"></i>&nbsp;&nbsp;

              <p>Regular Journeys </p>

            </a>

          </li>

          @endcan

          {{-- Coach Type END --}}



          {{-- Assign Drive START --}}

          @can('manage-user')

          <li class="nav-item">

            <a href="{{route('admin.assign.trip')}}" class="nav-link {{ (request()->is('admin/trip/assign')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-users"></i>&nbsp;&nbsp;

              <p>Assigned Trips</p>

            </a>

          </li>

          @endcan

          {{-- Coach Type END --}}

          {{-- Purches Order --}}

          @can('manage-user')

          {{-- <li class="nav-item">

            <a href="{{route('admin.purchase_order.index')}}" class="nav-link {{ (request()->is('admin/purchase-order')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-usersfas fa-file-invoice"></i>&nbsp;&nbsp;

              <p>Purchase Order</p>

            </a>

          </li> --}}



          {{-- user deta --}}

          <li class="nav-item has-treeview {{ (request()->is('admin/purchase-order*')) ? 'menu-open' : '' }} {{ (request()->is('admin/purchase-order*')) ? 'menu-open' : '' }}">

            <a href="#" class="nav-link">

              <i class="nav-icon fas fa-usersfas fa-file-invoice"></i>

              <p>

                Purchase Order

                <i class="fas fa-angle-left right"></i>

              </p>

            </a>

            <ul class="nav nav-treeview">

              <li class="nav-item">

                <a href="{{route('admin.purchase_order.index')}}" class="nav-link {{ (request()->is('admin/purchase-order')) ? 'active' : '' }}">

                  <i class="fas fa-usersfas fa-file-invoice nav-icon"></i>

                  <p>Purchase Order</p>

                </a>

              </li>

              <li class="nav-item">

                <a href="{{route('admin.purchase_order.supplier')}}" class="nav-link {{ (request()->is('admin/purchase-order/supplier/details')) ? 'active' : '' }}">

                  <i class="fas fa-user-tie nav-icon"></i>

                  <p>Supplier details</p>

                </a>

              </li>

              

            </ul>

          </li>

          {{-- user deta --}}









          @endcan

          {{-- Purches Order END --}}

          {{-- Purches Order --}}

          @can('manage-user')

          <li class="nav-item">

            <a href="{{route('admin.defect_notice')}}" class="nav-link {{ (request()->is('admin/defect-notice')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-file-signature"></i>&nbsp;&nbsp;

              <p>Defect Notice</p>

            </a>

          </li>

          <li class="nav-item">

            <a href="{{route('admin.school.index')}}" class="nav-link {{ (request()->is('admin/school')) ? 'active' : '' }}">

              <i class="nav-icon fas fa-file-signature"></i>&nbsp;&nbsp;

              <p>School</p>

            </a>

          </li>

          @endcan

          {{-- Purches Order END --}}



          {{-- Dirver Instruction Sheet download --}}

         {{--  @can('manage-user')

          <li class="nav-item">

            <a href="{{asset('admin_asset/dist/pdf/Bugler.pdf')}}" class="nav-link"> 

              <i class="nav-icon fas fa-file-download"></i>&nbsp;&nbsp;

              <p>Dirver Instruction Sheet</p>

            </a>

          </li>

          @endcan --}}

          {{-- Dirver Instruction Sheet download end --}}

        </ul>

      </nav>

      <!-- /.sidebar-menu -->

    </div>

    <!-- /.sidebar -->

  </aside>
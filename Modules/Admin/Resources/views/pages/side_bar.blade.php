<?php $urlName = Request::segment(2); ?>
<div class="dashboard-menu">
  <div class="nav-menu">
      <div class="user-info">
          <div class="user-icon"><img src="{{auth()->user()->image}}" alt="img"></div>
          <div class="user-name">
              <h5>{{auth()->user()->first_name.' '.auth()->user()->last_name}}</h5>
              <!-- <span class="h6 text-muted">{{auth()->user()->roles[0]->name}}</span> -->
          </div>
      </div>
      <ul class="list-unstyled nav">
        <!-- <li class="nav-item"><span class="menu-title text-muted">NAVIGATION</span></li> -->
          <li class="nav-item <?php if($urlName == '' || $urlName == 'dashboard'){ echo "active"; } ?>">
          <a href="{{url('/admin')}}" class="nav-link"><i class="fad fa-home-alt"></i> Dashboard</a>
        </li>
        <li class="nav-item <?php if($urlName == 'user' || $urlName=='provider' || $urlName=='profile'){ echo "active"; } ?>"><a href="#" class="nav-link <?php if($urlName == 'user'){ echo "active"; } ?>"><i class="fad fa-user-cog"></i> User Management </a>
            <ul class="sub-menu">
                <li class="nav-item <?php if($urlName == 'user'){ echo "active"; } ?>"><a href="{{url('/admin/user')}}" class="nav-link">Customers Management</a></li>
                <li class="nav-item <?php if($urlName == 'provider' && Request::segment(3) == 'provider'){ echo "active"; } ?>"><a href="{{url('/admin/provider/provider')}}" class="nav-link">Service provider Management</a></li>
                <li class="nav-item <?php if($urlName == 'profile' && Request::segment(3) == 'request'){ echo "active"; } ?>"><a href="{{url('/admin/profile/request')}}" class="nav-link">Update Profile Request</a></li>
            </ul>
        </li>
      
        <li class="nav-item <?php if($urlName == 'service' || $urlName=='category'){ echo "active"; } ?>"><a href="#" class="nav-link <?php if($urlName == 'service' || $urlName == 'category'){ echo "active"; } ?>"><i class="fad fa-server"></i>Category Management</a>
            <ul class="sub-menu">
                <li class="nav-item <?php if($urlName == 'category' && Request::segment(2) == 'category'){ echo "active"; } ?>"><a href="{{url('/admin/category/category')}}" class="nav-link">Category List</a></li>
                <li class="nav-item <?php if($urlName == 'service' && Request::segment(2) == 'service'){ echo "active"; } ?>"><a href="{{url('/admin/service/service')}}" class="nav-link">Service List</a></li>
           
            </ul>
        </li>

        <li class="nav-item <?php if($urlName == 'price' || $urlName=='varient' || $urlName=='base-fare' ||$urlName == 'tax' || $urlName=='cancel-master' || Request::segment(2) == 'city'){ echo "active"; } ?>"><a href="#" class="nav-link <?php if($urlName == 'price' || $urlName == 'varient'){ echo "active"; } ?>"><i class="fad fa-money"></i>Price Management</a>
            <ul class="sub-menu">
                <!-- <li class="nav-item <?php if($urlName == 'price' && Request::segment(2) == 'price'){ echo "active"; } ?>"><a href="{{url('/admin/price/price')}}" class="nav-link">Price List</a></li> -->
                <!-- <li class="nav-item <?php if($urlName == 'varient' && Request::segment(2) == 'varient'){ echo "active"; } ?>"><a href="{{url('/admin/varient/varient')}}" class="nav-link">Varient List</a></li>
                <li class="nav-item <?php if($urlName == 'tax' && Request::segment(2) == 'tax'){ echo "active"; } ?>"><a href="{{url('/admin/tax/tax')}}" class="nav-link">Master Taxes</a></li>
                <li class="nav-item <?php if($urlName == 'cancel-master' && Request::segment(2) == 'cancel-master'){ echo "active"; } ?>"><a href="{{url('/admin/cancel-master/master')}}" class="nav-link">Cancellation Manager</a></li> -->
                <li class="nav-item <?php  if($urlName == 'base-fare' && Request::segment(2) == 'base-fare'){ echo "active"; } ?>"><a href="{{url('/admin/base-fare/base-fare')}}" class="nav-link">Base Fare</a></li>
                <!-- <li class="nav-item <?php if($urlName == 'city' && Request::segment(2) == 'city'){ echo "active"; } ?>"><a href="{{url('/admin/city/city')}}" class="nav-link">City</a></li> -->
            </ul>
        </li>
        <li class="nav-item <?php if($urlName == 'job' || $urlName=='request' || $urlName=='reschedule' || $urlName=='track'){ echo "active"; } ?>"><a href="#" class="nav-link <?php if($urlName == 'job' || $urlName == 'request'){ echo "active"; } ?>"><i class="fad fa-address-card"></i>Booking Manager</a>
            <ul class="sub-menu">
                <li class="nav-item <?php if($urlName == 'job' && Request::segment(2) == 'request'){ echo "active"; } ?>"><a href="{{url('/admin/job/request')}}" class="nav-link">Job Request</a></li>
                <li class="nav-item <?php if($urlName == 'job' && Request::segment(2) == 'reschedule'){ echo "active"; } ?>"><a href="{{url('/admin/reschedule/request')}}" class="nav-link">Reschedule Request</a></li>
            </ul>
        </li>

        <!-- <li class="nav-item <?php if(Request::segment(2) == 'finances'){ echo "active"; } ?>">
          <a href="{{url('/admin/finances/manage')}}" class="nav-link"><i class="fa fa-cc-stripe"></i>Manage Finances</a>
        </li> -->
        <!-- <li class="nav-item <?php if(Request::segment(2) == 'discount'){ echo "active"; } ?>">
          <a href="{{url('/admin/discount/all')}}" class="nav-link"><i class="fa fa-money"></i>Discount Manager</a>
        </li> -->
        <li class="nav-item <?php if(Request::segment(2) == 'cms'){ echo "active"; } ?>">
          <a href="{{url('/admin/cms/cms')}}" class="nav-link"><i class="fad fa-clipboard-list"></i>CMS MANAGEMENT</a>
        </li>
        <!-- <li class="nav-item <?php if(Request::segment(2) == 'bank'){ echo "active"; } ?>">
          <a href="{{url('/admin/bank/list')}}" class="nav-link"><i class="fad fa-file-alt"></i>Bank Detail</a>
        </li> -->

        <li class="nav-item <?php if($urlName == 'walkthrough' ||Request::segment(3) == 'walkthrough'){ echo "active"; } ?>">
          <a href="{{url('/admin/walkthrough/walkthrough')}}" class="nav-link"><i class="fad fa-file-alt"></i>Walkthrough</a>
        </li>
        <li class="nav-item <?php if($urlName == 'contact' ){ echo "active"; } ?>">
          <a href="{{url('/admin/contact/all')}}" class="nav-link"><i class="fad fa-address-card"></i>Contact Us</a>
        </li>

        <!-- <li class="nav-item <?php if($urlName == 'dispute' ||Request::segment(3) == 'manager'){ echo "active"; } ?>">
          <a href="{{url('/admin/dispute/manager')}}" class="nav-link"><i class="fad fa-address-card"></i>Dispute Manager</a>
        </li> -->
        <li class="nav-item <?php if($urlName == 'banner' ||Request::segment(3) == 'list'){ echo "active"; } ?>">
          <a href="{{url('/admin/banner/list')}}" class="nav-link"><i class="fad fa-picture-o"></i>Banner</a>
        </li>


        <li class="nav-item <?php if(Request::segment(2) == 'logout'){ echo "active"; } ?>">
        <a class="nav-link" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                     <i class="fad fa-sign-out"></i> Logout
                  </a>
        </li>  
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

      </ul>
  </div>
</div>


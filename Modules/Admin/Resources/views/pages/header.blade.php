<div class="navbar navbar-expand flex-column flex-md-row align-items-center navbar-custom">
  <div class="container-fluid">
      <a href="{{url('/admin')}}" class="navbar-brand mr-0 mr-md-2 logo">
          <img src="{{asset('images/logo.png') }}" alt="Logo">
      </a>
      <button type="button" class="navigation-btn d-lg-none"><i class="fal fa-bars"></i></button>
      
      <ul class="navbar-nav flex-row ml-auto d-flex align-items-center list-unstyled topnav-menu mb-0">
          <li>
            <h4 class="m-0 user-name">Welcome <span>{{auth()->user()->first_name}}</span></h4>
          </li>
          <li class="dropdown notification-list">
              <!-- <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                  aria-haspopup="false" aria-expanded="false">
                  <i class="far fa-bell"></i>
                  <span class="noti-icon-badge"></span>
              </a> -->
              <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                  <div class="dropdown-item noti-title border-bottom">
                      <h5 class="m-0 font-size-16">
                          <span class="float-right">
                              <a href="" class="text-dark">
                                  <small>Clear All</small>
                              </a>
                          </span>Notification
                      </h5>
                  </div>
                  <div class="noti-scroll">

                      <!-- item-->
                      <a href="javascript:void(0);" class="dropdown-item notify-item">
                          <div class="notify-icon btn btn-primary"><i class="far fa-user-plus"></i></div>
                          <p class="notify-details">New user registered.<small class="text-muted">5 hours
                                  ago</small>
                          </p>
                      </a>

                      <!-- item-->
                      <a href="javascript:void(0);" class="dropdown-item notify-item">
                          <div class="notify-icon">
                              <img src="{{asset('images/avatar-1.jpg')}}" class="img-fluid rounded-circle" alt="">
                          </div>
                          <p class="notify-details">Karen Robinson <small class="text-muted">Wow ! this admin
                                  looks good and awesome design</small>
                          </p>
                      </a>

                      <!-- item-->
                      <a href="javascript:void(0);" class="dropdown-item notify-item">
                          <div class="notify-icon">
                              <img src="{{asset('images/avatar-2.jpg')}}" class="img-fluid rounded-circle" alt="">
                          </div>
                          <p class="notify-details">Cristina Pride <small class="text-muted">Hi, How are you? What
                                  about our next meeting</small></p>
                      </a>

                      <!-- item-->
                      <a href="javascript:void(0);" class="dropdown-item notify-item">
                          <div class="notify-icon bg-success"><i class="uil uil-comment-message"></i> </div>
                          <p class="notify-details">Jaclyn Brunswick commented on Dashboard<small
                                  class="text-muted">1 min ago</small></p>
                      </a>

                      <!-- item-->
                      <a href="javascript:void(0);" class="dropdown-item notify-item">
                          <div class="notify-icon bg-danger"><i class="uil uil-comment-message"></i></div>
                          <p class="notify-details">Caleb Flakelar commented on Admin<small class="text-muted">4
                                  days ago</small></p>
                      </a>

                      <!-- item-->
                      <a href="javascript:void(0);" class="dropdown-item notify-item">
                          <div class="notify-icon bg-primary">
                              <i class="uil uil-heart"></i>
                          </div>
                          <p class="notify-details">Carlos Crouch liked
                              <b>Admin</b>
                              <small class="text-muted">13 days ago</small>
                          </p>
                      </a>

                  </div>

                
                  <a href="javascript:void(0);"
                      class="dropdown-item align-items-center justify-content-center notify-item border-top">View
                      all</a>
              </div>
          </li>
          <li class="dropdown user-link">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                  aria-haspopup="false" aria-expanded="false">
                  <i class="far fa-cog"></i>
                  <span class="noti-icon-badge"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                   <a href="{{ url('/admin/profile/view') }}" class="dropdown-item <?php if(Request::segment(2) == 'profile'){ echo "active"; } ?>"> <i class="fal fa-user"></i> My Profile</a>
                  <!-- <div class="dropdown-divider"></div> -->
                  <!-- <a href="#" class="dropdown-item"> <i class="fal fa-headset"></i> Support</a>
                  <div class="dropdown-divider"></div> -->
                 <!-- <div class="dropdown-divider"></div> -->
                  <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                     <i class="fal fa-sign-out"></i> Logout
                  </a>
                  
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </div>
          </li>
      </ul>
  </div>
</div>

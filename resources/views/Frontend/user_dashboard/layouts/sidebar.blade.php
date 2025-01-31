<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ (request()->is('user/dashboard')) ? 'active' : '' }}">
        <a class="nav-link" href="{{url('user/dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ (request()->is('user/post_blog')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{route('user.post_blog')}}" 
          >
            <i class="fas fa-fw fa-cog"></i>
            <span>Post Blog</span>
        </a>
       
    </li>
    <li class="nav-item {{ (request()->is('user/my_blog')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{route('user.my_blog')}}" 
          >
            <i class="fas fa-fw fa-cog"></i>
            <span>My Blogs</span>
        </a>
       
    </li>

   

   

</ul>
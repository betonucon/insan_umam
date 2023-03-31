    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="@if(Request::is('home')==1 || Request::is('/')==1) active @endif"><a href="{{url('home')}}"><i class="fa fa-home text-white"></i> <span>Home</span></a></li>
        @if(Auth::user()->role_id==1)
          <li class="treeview  @if(Request::is('master')==1 || Request::is('master/*')==1) menu-open @endif">
            <a href="#">
              <i class="fa fa-database text-white"></i> <span>Master</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display:@if(Request::is('master')==1 || Request::is('master/*')==1) block @endif">
              <li ><a href="{{url('master')}}">&nbsp;<i class="fa  fa-sort-down"></i> Dokumen</a></li>
              <li ><a href="{{url('master/lemari')}}">&nbsp;<i class="fa  fa-sort-down"></i> Lemari</a></li>
              <li ><a href="{{url('master/rak')}}">&nbsp;<i class="fa  fa-sort-down"></i> Rak</a></li>
              
            </ul>
          </li>
          <li class="@if(Request::is('pengajuan')==1 ) active @endif"><a href="{{url('pengajuan')}}"><i class="fa fa-clone text-white"></i> <span>Pengajuan</span></a></li>
          <li class="@if(Request::is('arsip')==1 ) active @endif"><a href="{{url('arsip')}}"><i class="fa fa-database text-white"></i> <span>Arsip</span></a></li>
        @endif

      </ul>
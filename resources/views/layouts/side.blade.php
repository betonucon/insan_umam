    <ul class="sidebar-menu" data-widget="tree">
        <li class="header text-white" style="color:yellow">Time {{date('d-m-y H:i:s')}}</li>
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
              <li ><a href="{{url('master/dokumen')}}">&nbsp;<i class="fa  fa-sort-down"></i> Dokumen</a></li>
              <li ><a href="{{url('master/lemari')}}">&nbsp;<i class="fa  fa-sort-down"></i> Lemari</a></li>
              <li ><a href="{{url('master/rak')}}">&nbsp;<i class="fa  fa-sort-down"></i> Rak</a></li>
              
            </ul>
          </li>
          <li class="@if(Request::is('pengajuan')==1 || Request::is('pengajuan/view')==1 ) active @endif"><a href="{{url('pengajuan')}}"><i class="fa  fa-file-text-o text-white"></i> <span>SKMHT</span></a></li>
          <li class="@if(Request::is('pengajuan/riwayat')==1 ) active @endif"><a href="{{url('pengajuan/riwayat')}}"><i class="fa fa-history text-white"></i> <span>Riwayat SKMHT</span></a></li>
          <li class="treeview  @if(Request::is('arsip')==1 || Request::is('arsip/*')==1) menu-open @endif">
            <a href="#">
              <i class="fa fa-paperclip text-white"></i> <span>Arsip</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display:@if(Request::is('arsip')==1 || Request::is('arsip/*')==1) block @endif">
              <li ><a href="{{url('arsip')}}">&nbsp;<i class="fa  fa-sort-down"></i> Arsip Masuk</a></li>
              <li ><a href="{{url('arsip/out')}}">&nbsp;<i class="fa  fa-sort-down"></i> Arsip Keluar</a></li>
              
            </ul>
          </li>
        @endif

      </ul>
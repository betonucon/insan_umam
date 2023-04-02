@extends('layouts.app')

@push('style')
  <style>
    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: normal;
    }
  </style>
@endpush
@push('datatable')
    <script type="text/javascript">
        /*
        Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
        Version: 4.6.0
        Author: Sean Ngu
        Website: http://www.seantheme.com/color-admin/admin/
        */
        
        var handleDataTableFixedHeader = function() {
            "use strict";
            
            if ($('#data-table-fixed-header').length !== 0) {
                var table=$('#data-table-fixed-header').DataTable({
                    lengthMenu: [20,50,100],
                    searching:true,
                    lengthChange:false,
                    ordering:false,
                    fixedHeader: {
                        header: true,
                        headerOffset: $('#header').height()
                    },
                    dom: 'lrtip',
                    responsive: true,
                    ajax:"{{ url('pengajuan/getdatadashboard')}}?id=1",
                      columns: [
                        { data: 'id', render: function (data, type, row, meta) 
                            {
                              return meta.row + meta.settings._iDisplayStart + 1;
                            } 
                        },
                        
                        { data: 'action' },
                        { data: 'no_register' },
                        { data: 'nik' },
                        { data: 'nama' },
                        { data: 'sampai' },
                        { data: 'prosesnya' },
                        { data: 'waktunya' },
                        { data: 'nama_status' },
                        
                      ],
                      
                });
                $('#cari_datatable').keyup(function(){
                  table.search($(this).val()).draw() ;
                })

                
            }
        };

        var TableManageFixedHeader = function () {
            "use strict";
            return {
                //main function
                init: function () {
                    handleDataTableFixedHeader();
                }
            };
        }();

        function pilih_jenis(id){
          var tables=$('#data-table-fixed-header').DataTable();
          tables.ajax.url("{{ url('pengajuan/getdatadashboard')}}?id="+id).load();
          
        }
        
        $(document).ready(function() {
          TableManageFixedHeader.init();
              
        });

        function show_hide(){
            var tables=$('#data-table-fixed-header').DataTable();
                tables.ajax.url("{{ url('project/getdata')}}?hide=1").load();
        }
        function refresh_data(){
            var tables=$('#data-table-fixed-header').DataTable();
                tables.ajax.url("{{ url('project/getdata')}}").load();
        }
    </script>
@endpush
@section('content')
<div class="content-wrapper" style="min-height: 926.281px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control Transaksi</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    
    <section class="content">
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{total_dokumen(1)}}</h3>

              <p>Total Proses</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" onclick="pilih_jenis(1)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{total_dokumen(2)}}</h3>

              <p>Total Selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" onclick="pilih_jenis(2)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{total_dokumen(3)}}</h3>

              <p>Mendekati Tempo</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#"  onclick="pilih_jenis(3)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{total_dokumen(4)}}</h3>

              <p>Lewat Tempo</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#"  onclick="pilih_jenis(4)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
       
      <div class="box box-default">
        
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-5">
              
              
            </div>
            <div class="col-md-3">
              <!-- <div class="form-group">
                <label>Status Progres</label>
                  <select onchange="pilih_jenis(this.value)" class="form-control  input-sm">
                    <option value="">All Data</option>
                    
                  </select>
               
              </div> -->
            </div>
            <div class="col-md-4">
              <div class="form-group">
                  <label>Cari data</label>
                  <input type="text" id="cari_datatable" placeholder="Search....." class="form-control input-sm">
                  
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
           
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="data-table-fixed-header" width="100%" class="display">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            
                            <th width="5%"></th>
                            <th width="15%">No Register</th>
                            <th width="15%">NO KTP</th>
                            <th >Nama</th>
                            <th width="10%">Duedate</th>
                            <th width="6%">Ke</th>
                            <th width="14%">waktu</th>
                            <th width="10%">status</th>
                        </tr>
                    </thead>
                    
                </table>
              </div>
            </div>
            
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
      </div>
    </section>
  </div>
@endsection

@push('ajax')
    
@endpush

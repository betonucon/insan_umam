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
                    ajax:"{{ url('arsip/getdata')}}",
                      columns: [
                        { data: 'id', render: function (data, type, row, meta) 
                            {
                              return meta.row + meta.settings._iDisplayStart + 1;
                            } 
                        },
                        
                        { data: 'icon' },
                        { data: 'no_register' },
                        { data: 'nama' },
                        { data: 'dokumen' },
                        { data: 'lemari' },
                        { data: 'rak' },
                        { data: 'action' },
                        
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

        function pilih_jenis(KD_Divisi){
          var tables=$('#data-table-fixed-header').DataTable();
          tables.ajax.url("{{ url('project/getdata')}}?status_id="+KD_Divisi).load();
          tables.on( 'draw', function () {
              var count=tables.data().count();
                $('#count_data').html('Total data :'+count)  
          } );
              
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
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dokumen Arsip Masuk
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dokumen Arsip</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row" id="tampil-dashboard-role">
        
      </div>
      <div class="box box-default">
        
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-5">
              <!-- <div class="btn-group" style="margin-top:5%">
                <button type="button" class="btn btn-success btn-sm" onclick="location.assign(`{{url('pengajuan/view')}}?id={{encoder(0)}}`)"><i class="fa fa-plus"></i> Buat Baru</button>
                <button type="button" class="btn btn-info btn-sm"><i class="fa fa-print"></i> Cetak</button>
              </div> -->
              
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
                            <th >Nama</th>
                            <th width="20%">Dokumen</th>
                            <th width="10%">Lemari</th>
                            <th width="10%">Rak</th>
                            <th width="20%">file</th>
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
      <!-- /.box -->

    </section>
    
      <div class="modal file" id="modal-file" style="display: none;">
        <div class="modal-dialog modal-lg" style="max-width:70%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">File Kontrak</h4>
            </div>
            <div class="modal-body">
                <div id="tampil_file"></div>
               
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal file" id="modal-send" style="display: none;">
        <div class="modal-dialog" style="max-width:70%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Send To Komersil</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" id="mydatakirim" method="post" action="{{ url('project/kirim_komersil') }}" enctype="multipart/form-data" >
                  @csrf
                  <div id="tampil_form"></div>
              </form>
               
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary pull-right" onclick="kirim_data()" >Kirim</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal file" id="modal-timeline" style="display: none;">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Time Line</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" id="" method="post" action="{{ url('project/kirim_komersil') }}" enctype="multipart/form-data" >
                  @csrf
                  <div id="tampil_timeline"></div>
              </form>
               
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

  </div>
@endsection

@push('ajax')
    <script> 
      function send_data(id){
          $('#modal-send').modal('show')
          $('#tampil_form').load("{{url('project/form_send')}}?id="+id)
      }
      function show_timeline(id){
          $('#modal-timeline').modal('show')
          $('#tampil_timeline').load("{{url('project/timeline')}}?id="+id)
      }

      function proses_dokumen(id){
            
            swal({
                title: "Yakin mengeluarkan dokumen ini ?",
                text: "dokumen akan keluar dari arsip masuk",
                type: "warning",
                icon: "error",
                showCancelButton: true,
                align:"center",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }).then((willDelete) => {
                if (willDelete) {
                      
                        $.ajax({
                            type: 'GET',
                            url: "{{url('arsip/proses_dokumen')}}",
                            data: "id="+id,
                            success: function(msg){
                                swal("Success! berhasil diproses!", {
                                    icon: "success",
                                });
                                var tables=$('#data-table-fixed-header').DataTable();
                                    tables.ajax.url("{{ url('arsip/getdata')}}").load();
                            }
                        });
                    
                      
                } else {
                    
                }
            });
            
        } 
      function send_data_to(id){
            
            swal({
                title: "Yakin akan mengirim data keporses berikutnya ?",
                text: "",
                type: "warning",
                icon: "info",
                showCancelButton: true,
                align:"center",
                confirmButtonClass: "btn-info",
                confirmButtonText: "Yes, cancel it!",
                closeOnConfirm: false
            }).then((willDelete) => {
                if (willDelete) {
                      
                        $.ajax({
                            type: 'GET',
                            url: "{{url('project/kirim_kadis_komersil')}}",
                            data: "id="+id,
                            success: function(msg){
                                swal("Success! berhasil terkirim!", {
                                    icon: "success",
                                });
                                var tables=$('#data-table-fixed-header').DataTable();
                                    tables.ajax.url("{{ url('project/getdata')}}").load();
                            }
                        });
                    
                      
                } else {
                    
                }
            });
            
      } 
        function show_file(file){
          $('#modal-file').modal('show');
          $('#tampil_file').show();
          $('#tampil_file').html('<iframe src="{{url_plug()}}/_file_kontrak/'+file+'" height="500px" width="100%"></iframe>');
        }  

        function kirim_data(){
            
            var form=document.getElementById('mydatakirim');
            
                
                $.ajax({
                    type: 'POST',
                    url: "{{ url('project/kirim_komersil') }}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function() {
                        document.getElementById("loadnya").style.width = "100%";
                    },
                    success: function(msg){
                        var bat=msg.split('@');
                        if(bat[1]=='ok'){
                            document.getElementById("loadnya").style.width = "0px";
                            swal({
                              title: "Success! berhasil dikirim!",
                              icon: "success",
                            });
                            $('#modal-send').modal('hide');
                            $('#tampil_form').hide();
                            
                            var tables=$('#data-table-fixed-header').DataTable();
                                tables.ajax.url("{{ url('project/getdata')}}").load();
                        }else{
                            document.getElementById("loadnya").style.width = "0px";
                            swal({
                                title: 'Notifikasi',
                               
                                html:true,
                                text:'ss',
                                icon: 'error',
                                buttons: {
                                    cancel: {
                                        text: 'Tutup',
                                        value: null,
                                        visible: true,
                                        className: 'btn btn-dangers',
                                        closeModal: true,
                                    },
                                    
                                }
                            });
                            $('.swal-text').html('<div style="width:100%;background:#f2f2f5;padding:1%;text-align:left;font-size:13px">'+msg+'</div>')
                        }
                        
                        
                    }
                });
        };
    </script>   
@endpush

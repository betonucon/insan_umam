<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

use App\Models\User;
use App\Models\Pengajuan;
use App\Models\Dokumen;
use App\Models\VRak;
use App\Models\VDokumenPengajuan; 
use App\Models\DokumenPengajuan; 
use App\Models\VLemari; 
use App\Models\VPengajuan; 
use App\Models\LogPengajuan; 

class PengajuanController extends Controller
{
    
    public function index(request $request)
    {
        error_reporting(0);
        $template='top';
        return view('pengajuan.index',compact('template'));
        
    }
    public function index_log(request $request)
    {
        error_reporting(0);
        $template='top';
        return view('pengajuan.index_log',compact('template'));
        
    }
    public function index_arsip(request $request)
    {
        error_reporting(0);
        $template='top';
        return view('pengajuan.index_arsip',compact('template'));
        
    }
    public function index_arsip_keluar(request $request)
    {
        error_reporting(0);
        $template='top';
        return view('pengajuan.index_arsip_keluar',compact('template'));
        
    }

    public function view_data(request $request)
    {
        error_reporting(0);
        $template='top';
        $id=decoder($request->id);
        
        $data=Pengajuan::where('id',$id)->first();
        if($request->tab==""){
            $tab=1;
        }else{
            $tab=$request->tab;
        }
        
        if($id==0){
            $disabled='';
            $no_register=penomoran_register();
        }else{
            $disabled='readonly';
            $no_register=$data->no_register;
        }
        return view('pengajuan.view',compact('template','data','disabled','id','no_register'));
       
    }
    public function tampil_form(request $request)
    {
        error_reporting(0);
        $template='top';
        $id=$request->id;
        $dokumen_id=$request->dokumen_id;
        
        $data=Dokumen::where('id',$dokumen_id)->first();
        $mst=DokumenPengajuan::where('pengajuan_id',$id)->where('dokumen_id',$dokumen_id)->first();
        
        return view('pengajuan.form_dokumen',compact('template','data','dokumen_id','id','mst'));
       
    }
    public function tampil_perpanjang(request $request)
    {
        error_reporting(0);
        $template='top';
        $id=$request->id;
        
        $data=Pengajuan::where('id',$id)->first();
        
        return view('pengajuan.form_perpanjang',compact('template','data','id'));
       
    }

    
    public function rak(request $request)
    {
        $act='<option value="">Pilih----</option>';
        foreach(get_rak($request->id) as $rak){
            $act.='<option value="'.$rak->id.'">'.$rak->rak.'</option>';
        }
        return $act;
    }
    public function get_data(request $request)
    {
        error_reporting(0);
        $query = VPengajuan::query();
        if($request->kate==1){
            $data = $query->where('status',3);
        }else{
            $data = $query->whereIn('status',array(1,2));
        }
        $data = $query->orderBy('waktu','Asc')->get();

        return Datatables::of($data)
            ->addColumn('waktunya', function ($row) {
                if($row->waktu>30){
                    $btn='<font color="#000"><b>'.$row->waktu.' Hari</b></font>';
                }else{
                    $btn='<font color="red"><b>'.$row->waktu.' Hari</b></font>';
                }
                return $btn;
            })
            ->addColumn('prosesnya', function ($row) {
                if($row->proses>1){
                    $btn='<font color="red"><b>'.$row->proses.'X</b></font>';
                }else{
                    $btn='<font color="#000"><b>'.$row->proses.'X</b></font>';
                }
                
                return $btn;
            })
            ->addColumn('action', function ($row) {
                if($row->status!=3){
                    
                        $btn='
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Act <i class="fa fa-sort-desc"></i> 
                                </button>
                                <ul class="dropdown-menu">
                                        <li><a href="javascript:;" onclick="location.assign(`'.url('pengajuan/view').'?id='.encoder($row->id).'`)">View</a></li>
                                        <li><a href="javascript:;"  onclick="delete_data(`'.encoder($row->id).'`)">Delete</a></li>
                                        
                                </ul>
                            </div>
                        ';
                   
                }else{
                    $btn='
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Act <i class="fa fa-sort-desc"></i> 
                        </button>
                        <ul class="dropdown-menu">
                                <li><a href="javascript:;" onclick="location.assign(`'.url('pengajuan/view').'?id='.encoder($row->id).'`)">View</a></li>
                        </ul>
                    </div>
                    ';
                }
                return $btn;
            })
           
            ->rawColumns(['action','waktunya','prosesnya'])
            ->make(true);
    }
    public function get_data_dashboard(request $request)
    {
        error_reporting(0);
        $id=$request->id;
        if($id==1){
            $data=VPengajuan::whereIn('status',array(1,2))->orderBy('waktu','Asc')->get();
        }
        if($id==2){
            $data=VPengajuan::whereIn('status',array(3))->orderBy('waktu','Asc')->get();
        }
        if($id==3){
            $data=VPengajuan::whereIn('status',array(1,2))->where('waktu','<=',7)->where('waktu','>=',0)->orderBy('waktu','Asc')->get();
        }
        if($id==4){
            $data=VPengajuan::whereIn('status',array(1,2))->where('waktu','<',0)->orderBy('waktu','Asc')->get();
        }

        return Datatables::of($data)
            ->addColumn('waktunya', function ($row) {
                if($row->waktu>30){
                    $btn='<font color="#000"><b>'.$row->waktu.' Hari</b></font>';
                }else{
                    $btn='<font color="red"><b>'.$row->waktu.' Hari</b></font>';
                }
                return $btn;
            })
            ->addColumn('prosesnya', function ($row) {
                if($row->proses>1){
                    $btn='<font color="red"><b>'.$row->proses.'X</b></font>';
                }else{
                    $btn='<font color="#000"><b>'.$row->proses.'X</b></font>';
                }
                
                return $btn;
            })
            ->addColumn('action', function ($row) {
                if($row->status!=3){
                    
                        $btn='
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Act <i class="fa fa-sort-desc"></i> 
                                </button>
                                <ul class="dropdown-menu">
                                        <li><a href="javascript:;" onclick="location.assign(`'.url('pengajuan/view').'?id='.encoder($row->id).'`)">View</a></li>
                                        <li><a href="javascript:;"  onclick="delete_data(`'.encoder($row->id).'`)">Delete</a></li>
                                        
                                </ul>
                            </div>
                        ';
                   
                }else{
                    $btn='
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Act <i class="fa fa-sort-desc"></i> 
                        </button>
                        <ul class="dropdown-menu">
                                <li><a href="javascript:;" onclick="location.assign(`'.url('pengajuan/view').'?id='.encoder($row->id).'`)">View</a></li>
                        </ul>
                    </div>
                    ';
                }
                return $btn;
            })
           
            ->rawColumns(['action','waktunya','prosesnya'])
            ->make(true);
    }
    public function proses_dokumen(request $request){
        $mst=DokumenPengajuan::where('id',$request->id)->first();
        if($mst->sts==1){
            $data=DokumenPengajuan::where('id',$request->id)->update(['sts'=>2,'out_at'=>date('Y-m-d H:i:s')]);
        }else{
            $data=DokumenPengajuan::where('id',$request->id)->update(['sts'=>1,'out_in'=>date('Y-m-d H:i:s')]);
        }
    }
    public function get_data_arsip(request $request)
    {
        error_reporting(0);
        $query = VDokumenPengajuan::query();
        if($request->kate==1){
            $data = $query->where('sts',2);
        }else{
            $data = $query->where('sts',1);
        }
        $data = $query->orderBy('no_register','Asc')->get();

        return Datatables::of($data)
            
            ->addColumn('action', function ($row) {
                
                $btn='<a href="'.url_plug().'/dokumen/'.$row->file.'" target="_blank">'.$row->file.'</a>';
                   
               
                return $btn;
            })
            ->addColumn('icon', function ($row) {
                if($row->sts==1){
                    $btn='<span  class="btn btn-block btn-danger btn-xs" onclick="proses_dokumen('.$row->id.')">Out</span>';
                }else{
                    $btn='<span  class="btn btn-block btn-info btn-xs" onclick="proses_dokumen('.$row->id.')">In</span>';
                }
                  
               
                return $btn;
            })
           
            ->rawColumns(['action','icon','prosesnya'])
            ->make(true);
    }
    
    public function tampil_dokumen(request $request)
    {
        $act='';
        $sum=0;
        foreach(get_dokumen() as $no=>$o){
            $sum+=$o->total;
            if($request->act==1){
                $act.='
                <tr style="background:#fff">
                    <td style="padding: 2px 2px 2px 8px;">'.($no+1).'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->kode_material.'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->nama_material.'</td>
                    <td style="padding: 2px 2px 2px 8px;" class="text-right">'.uang($o->biaya).'</td>
                    
                    <td style="padding: 2px 2px 2px 8px;">'.$o->qty.'</td>
                    <td style="padding: 2px 2px 2px 8px;" class="text-right">'.uang($o->total).'</td>
                </tr>';
            }else{
                $act.='
                <tr style="background:#fff" >
                    <td style="padding: 2px 2px 2px 8px;">'.($no+1).'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->dokumen.'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.sts_dok($request->id,$o->id).'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.lemari($request->id,$o->id).'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.rak($request->id,$o->id).'</td>
                    <td style="padding: 2px 2px 2px 8px;"><a href="'.url_plug().'/dokumen/'.file_dokumen($request->id,$o->id).'" target="_blank">'.file_dokumen($request->id,$o->id).'</a></td>
                    <td style="padding: 2px 2px 2px 8px;">
                        <span class="btn btn-success btn-xs" onclick="update_dokumen('.$o->id.')"><i class="fa fa-cloud-upload"></i> upload</span>
                    </td>
                        
                </tr>';
            }
            
        }
        $act.='</table>';
        return $act;
    }
    public function tampil_log(request $request)
    {
        $act='';
        $sum=0;
        foreach(get_log_pengajuan($request->id) as $no=>$o){
            
                $act.='
                <tr style="background:#fff" >
                    <td style="padding: 2px 2px 2px 8px;">'.($no+1).'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->alasan.'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->proses_ke.'X</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->sebelumnya.'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->berikutnya.'</td>
                    <td style="padding: 2px 2px 2px 8px;">'.$o->created_at.'</td>
               </tr>';
            
        }
        $act.='</table>';
        return $act;
    }
    

    public function delete(request $request)
    {
        $id=decoder($request->id);
        
        $data=Pengajuan::where('id',$id)->update(['status'=>0]);
    }

    public function store(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        
        $rules['nik']= 'required|numeric';
        $messages['nik.required']= 'Masukan Nomor KTP ';
        $messages['nik.numeric']= 'Masukan Nomor KTP dengan karakter angka';
        
        $rules['nama']= 'required';
        $messages['nama.required']= 'Masukan Nama Lengkap ';
        
        $rules['alamat']= 'required';
        $messages['alamat.required']= 'Masukan Alamat ';
        
        $rules['kategori_id']= 'required';
        $messages['kategori_id.required']= 'Pilih Kategori Notaris ';

        $rules['nomor_jaminan']= 'required';
        $messages['nomor_jaminan.required']= 'Masukan nomor jaminan ';

        $rules['nik_pemilik']= 'required|numeric';
        $messages['nik_pemilik.required']= 'Masukan Nomor KTP Pemilik ';
        $messages['nik_pemilik.numeric']= 'Masukan Nomor KTP Pemilik dengan karakter angka';
        
        $rules['nama_pemilik']= 'required';
        $messages['nama_pemilik.required']= 'Masukan Nama Pemilik Lengkap ';
        
        $rules['alamat_pemilik']= 'required';
        $messages['alamat_pemilik.required']= 'Masukan alamat pemilik ';

        $rules['lokasi']= 'required';
        $messages['lokasi.required']= 'Masukan nama mitra tujuan ';
        
        
        $rules['mulai']= 'required';
        $messages['mulai.required']= 'Masukan tanggal mulai ';

        $rules['sampai']= 'required';
        $messages['sampai.required']= 'Masukan tanggal sampai ';
        
        
        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
            if($request->id=='0'){
                
                
                $data=Pengajuan::create([
                    'no_register'=>$request->no_register,
                    'nik'=>$request->nik,
                    'nama'=>$request->nama,
                    'alamat'=>$request->alamat,
                    'nik_pemilik'=>$request->nik_pemilik,
                    'nama_pemilik'=>$request->nama_pemilik,
                    'alamat_pemilik'=>$request->alamat_pemilik,
                    'kategori_id'=>$request->kategori_id, 
                    'lokasi'=>$request->lokasi, 
                    'nomor_jaminan'=>$request->nomor_jaminan, 
                    'nilai'=>ubah_uang($request->nilai),
                    'mulai'=>$request->mulai,
                    'sampai'=>$request->sampai,
                    'active'=>1,
                    'status'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);

                echo'@ok@'.encoder($data->id);
                   
                
            }else{
                $data=Pengajuan::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'nik'=>$request->nik,
                    'nama'=>$request->nama,
                    'alamat'=>$request->alamat,
                    'nik_pemilik'=>$request->nik_pemilik,
                    'nama_pemilik'=>$request->nama_pemilik,
                    'alamat_pemilik'=>$request->alamat_pemilik,
                    'kategori_id'=>$request->kategori_id, 
                    'lokasi'=>$request->lokasi, 
                    'nomor_jaminan'=>$request->nomor_jaminan, 
                    'nilai'=>ubah_uang($request->nilai),
                    'mulai'=>$request->mulai,
                    'sampai'=>$request->sampai,
                    'updated_at'=>date('Y-m-d H:i:s'),
                ]);

                
                echo'@ok@'.encoder($request->id);
                
            }
           
        }
    }
   
    public function proses_data(request $request){
        error_reporting(0);
       
        $cek=Pengajuan::where('id',$request->id)->where('status',1)->count();
        if($cek>0){
            $data=Pengajuan::UpdateOrcreate([
                'id'=>$request->id,
            ],[
                'proses'=>1,
                'status'=>2,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
            $log=LogPengajuan::create([
                'pengajuan_id'=>$request->id,
                'sebelumnya'=>$request->sampai,
                'berikutnya'=>$request->sampai,
                'proses_ke'=>1,
                'alasan'=>'Proses awal',
                'created_at'=>date('Y-m-d H:i:s'),
            ]);
            echo'@ok';
        }else{
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">Kesalah proses, silahkan reload halaman</div></div>';
        }
                
    }
    public function selesai_data(request $request){
        error_reporting(0);
       
        $cek=Pengajuan::where('id',$request->id)->where('status',2)->count();
        $mst=Pengajuan::where('id',$request->id)->first();
        if($cek>0){
            $data=Pengajuan::UpdateOrcreate([
                'id'=>$request->id,
            ],[
                'status'=>3,
                'selesai_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
            
            echo'@ok';
        }else{
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">Kesalah proses, silahkan reload halaman</div></div>';
        }
                
    }

    public function perpanjang_data(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        if($request->sebelumnya>=$request->sampai){
            $rules['status_id']= 'required';
            $messages['status_id.required']= 'Tanggal perpanjang harus lebih besar dari sebelumnya ';
        }
        
       
        $rules['alasan']= 'required';
        $messages['alasan.required']= 'Masukan alasan perpanjang';
        

        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
                $mst=Pengajuan::where('id',$request->id)->first();
                $proses=($mst->proses+1);
                $data=Pengajuan::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'sampai'=>$request->sampai,
                    'proses'=>$proses,
                    'updated_at'=>date('Y-m-d H:i:s'),
                ]);
                $log=LogPengajuan::create([
                    'pengajuan_id'=>$request->id,
                    'sebelumnya'=>$request->sebelumnya,
                    'berikutnya'=>$request->sampai,
                    'proses_ke'=>$proses,
                    'alasan'=>$request->alasan,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
        }
               
        
        
    }

    public function store_dokumen(request $request){
        error_reporting(0);
        $id=$request->id;
        $rules = [];
        $messages = [];
        
        $rules['lemari_id']= 'required';
        $messages['lemari_id.required']= 'Pilih Lemari';
        $rules['rak_id']= 'required';
        $messages['rak_id.required']= 'Pilih rak';

        $rules['file']= 'required|mimes:jpg,jpeg,gif,png,pdf';
        $messages['file.required']= 'Upload dokumen';
        $messages['file.mimes']= 'Format dokumen (jpg,jpeg,gif,png,pdf)';
        
        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
            $image = $request->file('file');
            $nama='dokumen_'.$request->pengajuan_id.'_'.$request->dokumen_id;
            $imageFileName =$nama.'.'.$image->getClientOriginalExtension();
            $filePath =$imageFileName;
            $file =\Storage::disk('public_dokumen');
            if($file->put($filePath, file_get_contents($image))){
                $data=DokumenPengajuan::UpdateOrcreate([
                    'pengajuan_id'=>$request->pengajuan_id,
                    'dokumen_id'=>$request->dokumen_id,
                    'sts'=>1,
                ],[
                    'out_in'=>date('Y-m-d H:i:s'),
                    'lemari_id'=>$request->lemari_id,
                    'rak_id'=>$request->rak_id,
                    'file'=>$filePath,
                ]);
                echo'@ok';
            }
                        
        }    
        
    }

    public function store_risiko(request $request){
        error_reporting(0);
        $id=$request->id;
        $rules = [];
        $messages = [];
        if($id==0){
            $rules['risiko']= 'required';
            $messages['risiko.required']= 'Harap isi rencana kerja terlebih dahulu';
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
            $count=(int) count($request->risiko);
            if($count>0){
                $cek=0;
                for($x=0;$x<$count;$x++){
                    if($request->risiko[$x]==""  || $request->status_risiko[$x]==""){
                        $cek+=0;
                    }else{
                        $cek+=1;
                    }
                }

                if($cek==$count){
                    
                        $tab=5;
                    
                    $header=HeaderProject::where('id',$id)->update(['tab'=>$tab]);
                    for($x=0;$x<$count;$x++){
                        
                            $data=ProjectRisiko::UpdateOrcreate([
                                'project_header_id'=>$id,
                                'risiko'=>$request->risiko[$x],
                            ],[
                                'status_risiko'=>$request->status_risiko[$x],
                            ]);
                        
                    }
                    echo'@ok';  
                }else{
                    echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof"> Lengkapi semua kolom</div></div>';
                }
            }else{
                echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof"> Lengkapi semua kolom</div></div>';
            }
        }    
        
    }

    public function store_material(request $request){
        error_reporting(0);
        $id=$request->id;
        $rules = [];
        $messages = [];
        if($id==0){
            $rules['catatan']= 'required';
            $messages['catatan.required']= 'Harap isi rencana kerja terlebih dahulu';
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
            $count=(int) count($request->kode_material);
            if($count>0){
                $cek=0;
                for($x=0;$x<$count;$x++){
                    if($request->kode_material[$x]==""  || $request->qty[$x]==""   || $request->total[$x]=="" || $request->total[$x]==0){
                        $cek+=0;
                    }else{
                        $cek+=1;
                    }
                }

                if($cek==$count){
                    if($mst->tab>2){
                        $tab=$mst->tab;
                    }else{
                        $tab=4;
                    }
                    $header=HeaderProject::where('id',$id)->update(['tab'=>$tab]);
                    for($x=0;$x<$count;$x++){
                        
                            $data=ProjectMaterial::UpdateOrcreate([
                                'project_header_id'=>$id,
                                'kode_material'=>$request->kode_material[$x],
                                'status'=>1,
                                
                            ],[
                                'biaya'=>ubah_uang($request->biaya[$x]),
                                'qty'=>ubah_uang($request->qty[$x]),
                                'total'=>ubah_uang($request->total[$x]),
                                'nama_material'=>$request->nama_material[$x],
                                'created_at'=>date('Y-m-d H:i:s'),
                            ]);
                        
                    }
                    echo'@ok';  
                }else{
                    echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof"> Lengkapi semua kolom</div></div>';
                }
            }else{
                echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof"> Lengkapi semua kolom</div></div>';
            }
        }
        
               
        
        
    }

    public function kirim_kadis_komersil (request $request){
        error_reporting(0);
        $id=decoder($request->id);
        $data=HeaderProject::UpdateOrcreate([
            'id'=>$id,
        ],[
            'status_id'=>2,
            'update'=>date('Y-m-d H:i:s'),
        ]);
        $log=LogPengajuan::create([
            'project_header_id'=>$id,
            'deskripsi'=>'Pengajuan telah dikirim kekadis operasional',
            'status_id'=>2,
            'nik'=>Auth::user()->username,
            'role_id'=>Auth::user()->role_id,
            'revisi'=>$revisi,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        echo'@ok';
    }

    public function approve_kadis_operasional(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        
        $rules['status_id']= 'required';
        $messages['status_id.required']= 'Pilih  status approve ';
       
        if($request->status_id==1){
            $rules['catatan']= 'required';
            $messages['catatan.required']= 'Masukan alasan pengembalian';
        }else{

        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>$request->status_id,
                    'approve_kadis_operasional'=>date('Y-m-d H:i:s'),
                    'update'=>date('Y-m-d H:i:s'),
                ]);

                if($request->status_id==1){
                    $catatan=$request->catatan;
                    $revisi=2;
                }else{
                    $catatan='Pengajuan telah disetujui ke kadis operasional';
                    $revisi=1;
                }
                $log=LogPengajuan::create([
                    'project_header_id'=>$request->id,
                    'deskripsi'=>$catatan,
                    'status_id'=>$request->status_id,
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>$revisi,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
        }
               
        
        
    }

    public function approve_mgr_operasional(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        
        $rules['status_id']= 'required';
        $messages['status_id.required']= 'Pilih  status approve ';
       
        if($request->status_id==1){
            $rules['catatan']= 'required';
            $messages['catatan.required']= 'Masukan alasan pengembalian';
        }else{

        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>$request->status_id,
                    'approve_mgr_operasional'=>date('Y-m-d H:i:s'),
                    'update'=>date('Y-m-d H:i:s'),
                ]);

                if($request->status_id==1){
                    $catatan=$request->catatan;
                    $revisi=2;
                }else{
                    $catatan='Pengajuan telah disetujui ke manager operasional';
                    $revisi=1;
                }
                $log=LogPengajuan::create([
                    'project_header_id'=>$request->id,
                    'deskripsi'=>$catatan,
                    'status_id'=>$request->status_id,
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>$revisi,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
        }
               
        
        
    }

    public function approve_direktur_operasional(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        
        $rules['status_id']= 'required';
        $messages['status_id.required']= 'Pilih  status approve ';
       
        if($request->status_id==1){
            $rules['catatan']= 'required';
            $messages['catatan.required']= 'Masukan alasan pengembalian';
        }else{

        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>$request->status_id,
                    'approve_direktur_operasional'=>date('Y-m-d H:i:s'),
                    'update'=>date('Y-m-d H:i:s'),
                ]);

                if($request->status_id==1){
                    $catatan=$request->catatan;
                    $revisi=2;
                }else{
                    $catatan='Pengajuan telah disetujui ke direktur operasional';
                    $revisi=1;
                }
                $log=LogPengajuan::create([
                    'project_header_id'=>$request->id,
                    'deskripsi'=>$catatan,
                    'status_id'=>$request->status_id,
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>$revisi,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
        }
               
        
        
    }
    

    public function kembali_komersil(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        $rules['catatan']= 'required';
        $messages['catatan.required']= 'Masukan catatan';


       
        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
            
                $mst=HeaderProject::where('id',$request->id)->first();
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>(((int)$mst->status_id)-1),
                    'update'=>date('Y-m-d H:i:s'),
                ]);

                $log=LogPengajuan::create([
                    'cost_center'=>$mst['cost_center'],
                    'project_header_id'=>$request->id,
                    'deskripsi'=>$request->catatan,
                    'status_id'=>(((int)$mst->status_id)-1),
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>2,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
               
           
        }
        
    }

    public function kirim_procurement(request $request){
        error_reporting(0);
        
            
            $count=ProjectMaterial::where('project_header_id',$request->id)->count();
            if($count>0){
                $mst=HeaderProject::where('id',$request->id)->first();
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>(((int)$mst->status_id)+1),
                    'tgl_send_procurement'=>date('Y-m-d H:i:s'),
                    'update'=>date('Y-m-d H:i:s'),
                ]);

                $log=LogPengajuan::create([
                    'cost_center'=>$mst['cost_center'],
                    'project_header_id'=>$request->id,
                    'deskripsi'=>'Selesai dikonfirmasi oleh petugas komersil dan dikirim ke procurement',
                    'status_id'=>(((int)$mst->status_id)+1),
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
            }else{
                echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                echo'-Lengakapi material yang dibutuhkah';
                echo'</div></div>';
            }
               
        
        
    }

    
    public function store_bidding(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        
        $rules['nilai_bidding']= 'required|min:0|not_in:0';
        $messages['nilai_bidding.required']= 'Masukan nilai bidding';
        $messages['nilai_bidding.not_in']= 'Masukan nilai bidding';
        
        $rules['terbilang']= 'required';
        $messages['terbilang.required']= 'Masukan terbilang';
       
        
        $rules['bidding_date']= 'required';
        $messages['bidding_date.required']= 'Masukan tanggal bidding';

        $rules['status_id']= 'required';
        $messages['status_id.required']= 'Masukan status';

        $rules['hasil_bidding']= 'required';
        $messages['hasil_bidding.required']= 'Masukan hasil bidding';
        

        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>$request->status_id,
                    'bidding_date'=>$request->bidding_date,
                    'hasil_bidding'=>$request->hasil_bidding,
                    'nilai_bidding'=>ubah_uang($request->nilai_bidding),
                    'update'=>date('Y-m-d H:i:s'),
                ]);

                if($request->status_id==50){
                    $revisi=2;
                }else{
                    $revisi=1;
                }
                $log=LogPengajuan::create([
                    'project_header_id'=>$request->id,
                    'deskripsi'=>$request->hasil_bidding,
                    'status_id'=>$request->status_id,
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>$revisi,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
        }
               
        
        
    }

    public function store_negosiasi(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        
        
        $rules['nilai']= 'required|min:0|not_in:0';
        $messages['nilai.required']= 'Masukan nilai kontrak';
        $messages['nilai.not_in']= 'Masukan nilai kontrak';
        

        $validator = Validator::make($request->all(), $rules, $messages);
        $val=$validator->Errors();


        if ($validator->fails()) {
            echo'<div class="nitof"><b>Oops Error !</b><br><div class="isi-nitof">';
                foreach(parsing_validator($val) as $value){
                    
                    foreach($value as $isi){
                        echo'-&nbsp;'.$isi.'<br>';
                    }
                }
            echo'</div></div>';
        }else{
                $data=HeaderProject::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'status_id'=>8,
                    'nilai'=>ubah_uang($request->nilai),
                    'update'=>date('Y-m-d H:i:s'),
                ]);
                $mst=HeaderProject::where('id',$request->id)->first();
                for($x=0;$x<total_bulan($mst->start_date,$mst->end_date);$x++){
                    $per=ProjectPeriode::UpdateOrcreate([
                        'project_header_id'=>$request->id,
                        'bulan'=>nextbulan($mst->start_date,$x),
                        'tahun'=>nexttahun($mst->start_date,$x),
                    ],[
                        'not'=>null,
                    ]);
                }
                $log=LogPengajuan::create([
                    'project_header_id'=>$request->id,
                    'deskripsi'=>'Proses negosiasi dan lanjut keproses kontrak',
                    'status_id'=>8,
                    'nik'=>Auth::user()->username,
                    'role_id'=>Auth::user()->role_id,
                    'revisi'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                echo'@ok';
        }
               
        
        
    }
}

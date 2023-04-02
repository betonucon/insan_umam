<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Models\Dokumen;
use App\Models\VLemari;
use App\Models\VRak;
use App\Models\Rak;
use App\Models\Lemari;
use App\Models\User;

class MasterController extends Controller
{
    
    public function index(request $request)
    {
        error_reporting(0);
        $template='top';
        
        return view('dokumen.index',compact('template'));
    }
    public function index_lemari(request $request)
    {
        error_reporting(0);
        $template='top';
        
        return view('lemari.index',compact('template'));
    }
    public function index_rak(request $request)
    {
        error_reporting(0);
        $template='top';
        
        return view('rak.index',compact('template'));
    }

    public function view_data(request $request)
    {
        error_reporting(0);
        $template='top';
        $id=decoder($request->id);
        
        $data=Dokumen::where('id',$id)->first();
        
        if($id==0){
            $disabled='';
        }else{
            $disabled='readonly';
        }
        return view('dokumen.view_data',compact('template','data','disabled','id'));
    }
    public function view_data_lemari(request $request)
    {
        error_reporting(0);
        $template='top';
        $id=decoder($request->id);
        
        $data=Lemari::where('id',$id)->first();
        
        if($id==0){
            $disabled='';
        }else{
            $disabled='readonly';
        }
        return view('lemari.view_data',compact('template','data','disabled','id'));
    }
    public function view_data_rak(request $request)
    {
        error_reporting(0);
        $template='top';
        $id=decoder($request->id);
        
        $data=Rak::where('id',$id)->first();
        
        if($id==0){
            $disabled='';
        }else{
            $disabled='readonly';
        }
        return view('rak.view_data',compact('template','data','disabled','id'));
    }
   

    public function delete(request $request)
    {
        $id=decoder($request->id);
        
        $data=Dokumen::where('id',$id)->update(['active'=>0]);
    }

    public function delete_lemari(request $request)
    {
        $id=decoder($request->id);
        
        $data=Lemari::where('id',$id)->update(['active'=>0]);
    }
    public function delete_rak(request $request)
    {
        $id=decoder($request->id);
        
        $data=Rak::where('id',$id)->update(['active'=>0]);
    }

    public function get_data(request $request)
    {
        error_reporting(0);
        $query = Dokumen::query();
        if($request->hide==1){
            $data = $query->where('active',0);
        }else{
            $data = $query->where('active',1);
        }
        $data = $query->orderBy('dokumen','Asc')->get();

        return Datatables::of($data)
            ->addColumn('action', function ($row) {
                $btn='
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Act <i class="fa fa-sort-desc"></i> 
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;" onclick="location.assign(`'.url('master/dokumen/view').'?id='.encoder($row->id).'`)">View</a></li>
                            <li><a href="javascript:;"  onclick="delete_data(`'.encoder($row->id).'`)">Delete</a></li>
                        </ul>
                    </div>
                ';
                return $btn;
            })
            
            ->rawColumns(['action','seleksi'])
            ->make(true);
    }

    public function get_data_lemari(request $request)
    {
        error_reporting(0);
        $query = VLemari::query();
        if($request->hide==1){
            $data = $query->where('active',0);
        }else{
            $data = $query->where('active',1);
        }
        $data = $query->orderBy('lemari','Asc')->get();

        return Datatables::of($data)
            ->addColumn('action', function ($row) {
                $btn='
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Act <i class="fa fa-sort-desc"></i> 
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;" onclick="location.assign(`'.url('master/lemari/view').'?id='.encoder($row->id).'`)">View</a></li>
                            <li><a href="javascript:;"  onclick="delete_data(`'.encoder($row->id).'`)">Delete</a></li>
                        </ul>
                    </div>
                ';
                return $btn;
            })
            
            ->rawColumns(['action','seleksi'])
            ->make(true);
    }
    public function get_data_rak(request $request)
    {
        error_reporting(0);
        $query = VRak::query();
        if($request->hide==1){
            $data = $query->where('active',0);
        }else{
            $data = $query->where('active',1);
        }
        $data = $query->orderBy('lemari_id','Asc')->get();

        return Datatables::of($data)
            ->addColumn('action', function ($row) {
                $btn='
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Act <i class="fa fa-sort-desc"></i> 
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;" onclick="location.assign(`'.url('master/rak/view').'?id='.encoder($row->id).'`)">View</a></li>
                            <li><a href="javascript:;"  onclick="delete_data(`'.encoder($row->id).'`)">Delete</a></li>
                        </ul>
                    </div>
                ';
                return $btn;
            })
            
            ->rawColumns(['action','seleksi'])
            ->make(true);
    }

   
    public function store(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
       
        $rules['dokumen']= 'required';
        $messages['dokumen.required']= 'Masukan nama dokumen';
       
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
                
                    $data=Dokumen::create([
                        'dokumen'=>$request->dokumen,
                        'active'=>1,
                    ]);
                    echo'@ok';
                
            }else{
                $data=Dokumen::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'dokumen'=>$request->dokumen,
                ]);
                echo'@ok';
            }
           
        }
    }
    public function store_lemari(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
       
        $rules['lemari']= 'required';
        $messages['lemari.required']= 'Masukan nama lemari';
       
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
                
                    $data=Lemari::create([
                        'lemari'=>$request->lemari,
                        'active'=>1,
                    ]);
                    echo'@ok';
                
            }else{
                $data=Lemari::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'lemari'=>$request->lemari,
                ]);
                echo'@ok';
            }
           
        }
    }
    public function store_rak(request $request){
        error_reporting(0);
        $rules = [];
        $messages = [];
        if($request->id=='0'){
        $rules['lemari_id']= 'required';
        $messages['lemari_id.required']= 'Pilih Lemari';
        }

        $rules['rak']= 'required';
        $messages['rak.required']= 'Masukan nama rak';
       
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
                
                    $data=Rak::create([
                        'lemari_id'=>$request->lemari_id,
                        'rak'=>$request->rak,
                        'active'=>1,
                    ]);
                    echo'@ok';
                
            }else{
                $data=Rak::UpdateOrcreate([
                    'id'=>$request->id,
                ],[
                    'rak'=>$request->rak,
                ]);
                echo'@ok';
            }
           
        }
    }
}

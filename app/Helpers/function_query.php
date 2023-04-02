<?php

function get_jabatan(){
    $data=App\Models\Jabatan::orderBy('id','Asc')->get();
    return $data;
}
function get_lemari(){
    $data=App\Models\Lemari::orderBy('id','Asc')->get();
    return $data;
}
function get_rak($id){
    $data=App\Models\Rak::where('lemari_id',$id)->orderBy('id','Asc')->get();
    return $data;
}
function total_dokumen($id){
    if($id==1){
        $data=App\Models\VPengajuan::whereIn('status',array(1,2))->count();
    }
    if($id==2){
        $data=App\Models\VPengajuan::whereIn('status',array(3))->count();
    }
    if($id==3){
        $data=App\Models\VPengajuan::whereIn('status',array(1,2))->where('waktu','<=',7)->where('waktu','>=',0)->count();
    }
    if($id==4){
        $data=App\Models\VPengajuan::whereIn('status',array(1,2))->where('waktu','<',0)->count();
    }
    
    return $data;
}
function get_log_pengajuan($id){
    $data=App\Models\VLogPengajuan::where('pengajuan_id',$id)->orderBy('id','Desc')->get();
    return $data;
}
function file_dokumen($pengajuan_id,$dokumen_id){
    $data=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->count();
    if($data>0){
        $file=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->first();
        return $file->file;
    }else{
        return "";
    }
    
}
function lemari($pengajuan_id,$dokumen_id){
    $data=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->count();
    if($data>0){
        $file=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->first();
        return $file->lemari;
    }else{
        return "";
    }
    
}
function rak($pengajuan_id,$dokumen_id){
    $data=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->count();
    if($data>0){
        $file=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->first();
        return $file->rak;
    }else{
        return "";
    }
    
}
function doc_id($pengajuan_id,$dokumen_id){
    $data=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->count();
    if($data>0){
        $file=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->first();
        return $file->id;
    }else{
        return 0;
    }
    
}
function sts_dok($pengajuan_id,$dokumen_id){
    $data=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->count();
    if($data>0){
        $file=App\Models\VDokumenPengajuan::where('pengajuan_id',$pengajuan_id)->where('dokumen_id',$dokumen_id)->first();
        return $file->tipe_dok;
    }else{
        return "";
    }
    
}
function get_dokumen(){
    $data=App\Models\Dokumen::orderBy('id','Asc')->get();
    return $data;
}
function get_kategori(){
    $data=App\Models\Kategori::orderBy('id','Asc')->get();
    return $data;
}

?>
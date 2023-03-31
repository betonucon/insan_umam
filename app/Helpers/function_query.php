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
function get_dokumen(){
    $data=App\Models\Dokumen::orderBy('id','Asc')->get();
    return $data;
}
function get_kategori(){
    $data=App\Models\Kategori::orderBy('id','Asc')->get();
    return $data;
}

?>
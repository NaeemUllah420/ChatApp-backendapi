<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model implements Authenticatable
{
    use HasFactory, Notifiable, AuthAuthenticatable,SoftDeletes;
    protected $fillable=['name','path','type','user_id','message_id','attachmentable_id','attachmentable_type'];

    public function attachmentable()
    {
        return $this->morphTo();
    }

    public function validExtensions()
    {
        return [
            'mp3','mp4','png','jpg','jpeg','pdf','docs','txt','pptx','pages'
        ];
    }
    public static function formDateFile($request,$message)
    {
        $files=$request->attachments;
        $attachments_data=[];
        foreach($files as $file)
        {
            if($file->isValid())
            {
                $name=$file->getClientOriginalName();
                $extension=$file->getClientOriginalExtension();
                $type=$file->getMimeType();
                $path=(new Attachment)->getPath($request);
                $validExtensions=(new Attachment)->validExtensions();
                $encrypted_file_name=generate_random_string().".".$extension;
                if(in_array($extension,$validExtensions))
                {
                    $file->move(storage_path("app/public")."/".$path,$encrypted_file_name);
                    $path="storage/".$path."/".$encrypted_file_name;

                }else{
                    $file->move(storage_path("app/private")."/".$path,$encrypted_file_name);
                    @list($file_name,$extension)=explode(".",$encrypted_file_name);
                    $path="get-files/".$path."/".$file_name."/".$extension;
                }
                $attachments_data[]=[
                    'name'=>$name,
                    'type'=>$type,
                    'path'=>$path,
                ];
            }
        }
        !count($attachments_data)?:$message->attachmentable()->createMany($attachments_data);
    }
    public function getPath($request)
    {
        if(isset($request['group_id']) && !empty($request['group_id']))
            $path=$request['group_id'].'/'.generate_random_string();
        else if(isset($request['receiver_id']) && !empty($request['receiver_id']))
            $path=$request['receiver_id'].'/'. generate_random_string();
        return $path;
    }
}

<?php
class Controller_V1_Hotgirl extends Controller_V1
{

    public function action_update(){
        $ids = DB::select('id','real_id')->from('channel')
            ->where('status','=','labelList')->execute('hotgirl');
        echo json_encode($ids->as_array());exit;
        foreach ($ids as $id) {
            DB::update('album')->set(array('cate_id'=>$id['id']))->where('label_id','=',$id['real_id'])->execute('hotgirl');
        }
    }
    public function action_get_latest_version(){
        $resp = $this->buildResp();
        try{
            $version = DB::select(array('name','new_version'),array('url','new_down_url'))->from('version')->order_by('id','desc')->limit(1)->execute('hotgirl')->offsetGet(0);
            $resp->data($version);
        }catch (Database_Exception $e){
            $resp->ret = 409;
            $resp->msg = "数据库错误($e)";
        }
        echo $resp;
    }
    public function action_get_app_list(){
        $resp = $this->buildResp();
        try{
            $apps = DB::select(array('download','down_url'),array('app_name','title'),array('package_name','package_name'),array('ad_slogan','ad_slogan'),array('icon','icon_url'))
                    ->from('app')->where('status','=','actived')->execute('hotgirl')->as_array();
            $resp->data($apps);
        }catch (Database_Exception $e){
            $resp->ret = 409;
            $resp->msg = "数据库错误($e)";
        }
        echo $resp;
    }

}


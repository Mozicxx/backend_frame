<?php
class Controller_V1_Indoorsman extends Controller_V1
{

//    public function action_update(){
//        $ids = DB::select('id','real_id')->from('channel')
//            ->where('status','=','labelList')->execute('hotgirl');
//        echo json_encode($ids->as_array());exit;
//        foreach ($ids as $id) {
//            DB::update('album')->set(array('cate_id'=>$id['id']))->where('label_id','=',$id['real_id'])->execute('hotgirl');
//        }
//    }


    public function action_get_latest_version(){
        $resp = $this->buildResp();
        try{
            $version = DB::select(array('name','new_version'),array('url','new_down_url'))->from('version')->order_by('id','desc')->limit(1)->execute('indoorsman')->offsetGet(0);
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
                    ->from('app')->where('status','=','actived')->execute('indoorsman')->as_array();
            $resp->data($apps);
        }catch (Database_Exception $e){
            $resp->ret = 409;
            $resp->msg = "数据库错误($e)";
        }
        echo $resp;
    }

    public function action_get_main_list(){
        $resp = $this->buildResp();
        try{
            $data = [];
            $data['module1_entity'] = ['class_name'=>'','class_icon_url'=>''];
            $data['module1_entity']['list'] = DB::select(array('a.icon','icon_url'),array('a.url','jump_url'),array('a.name','title'))
                        ->from(array('links','a'))->join(array('recommend','b'))->on('a.id','=','b.app_id')
                        ->limit(10)->execute('indoorsman')->as_array();
            $blocks = DB::select(array('a.icon','icon_url'),array('a.url','jump_url'),array('a.name','title'),array('b.name','class_name'),array('b.icon','class_icon_url'))
                    ->from(array('links','a'))->join(array('category','b'))->on('a.cate_id','=','b.id')
                    ->order_by('b.sort','asc')->order_by('a.id','asc')->execute('indoorsman');
            for($i=0,$requireIndex=0;$i<32;$i++){
                $requireIndexRange = range(2,5);
                $linkList[] = array('icon_url'=>$blocks[$i]['icon_url'],'jump_url'=>$blocks[$i]['jump_url'],'title'=>$blocks[$i]['title']);
                if(($i+1) % 8 == 0){
                    $entityName = "module$requireIndexRange[$requireIndex]_entity";
                    $requireIndex++;
                    $data[$entityName]['class_name'] = $blocks[$i]['class_name'];
                    $data[$entityName]['class_icon_url'] = $blocks[$i]['class_icon_url'];
                    $data[$entityName]['list'] = $linkList;
                    unset($linkList);
                }
            }
//            exit(Debug::dump($linkList));
            $resp->data($data);
        }catch (Database_Exception $e){
            $resp->ret = 409;
            $resp->msg = "数据库错误($e)";
        }
        echo $resp;
    }

}


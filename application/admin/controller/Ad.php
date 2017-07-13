<?php
/** .-------------------------------------------------------------------
 * |    Software: []
 * | Description:
 * |        Site: www.jechorn.cn
 * |-------------------------------------------------------------------
 * |      Author: 王志传
 * |      Email : <jechorn@163.com>
 * |  CreateTime: 2017/6/11-14:43
 * | Copyright (c) 2016-2019, www.jechorn.cn. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace app\admin\controller;

use app\admin\common\Base;
use think\Config;
use think\Db;
use think\Session;
use think\Validate;


/**
 * Class Ad 广告位类
 * @package app\admin\controller
 */
class Ad extends Base
{
    //保存照片的名称
    private $imageName;
    //照片的短路径包括名称
    private $imageUrl;
    private $path;
    private  $adModel;

   public function _initialize()
   {
       parent::_initialize(); // TODO: Change the autogenerated stub
       $this->setPath();
       $this->adModel = new \app\admin\model\Ad();
   }

    public function index()
    {

        $cur = $this->request->param('page',1,'intval');
        $total = $this->adModel->adCount();
        $page = ceil($total/config('paginate.list_rows'));
        if($cur >$page){
            $cur = $page;
        }elseif ($cur < 1){
            $cur = 1;
        }
        $info = [];
        if($total > 0){
            $info = $this->adModel->getAd('',$cur);
        }
        $this->assign('adList',$info);
        $this->assign('pageInfo',['page'=>$page,'cur'=>$cur,'total'=>$total]);
        return $this->fetch();
    }


    public function search()
    {
        $data = $this->request->param('','','htmlspecialchars');
        $where = [];
        if(empty($data['start_time']) && !empty($data['end_time'])){
            $where['end_time'] = ['<=',$data['end_time']];
        }elseif (!empty($data['start_time']) && empty($data['end_time'])){
            $where['start_time'] =['>=',$data['start_time']];
        }elseif (!empty($data['start_time']) && !empty($data['end_time'])){

            if (intval(strtotime($data['start_time'])) >=intval(strtotime($data['end_time']))){

                $where['start_time'] = ['>=',$data['end_time']];
                $where['end_time'] = ['<=',$data['start_time']];
            }else{
                $where['start_time'] = ['>=',$data['start_time']];
                $where['end_time'] = ['<=',$data['end_time']];
            }
        }
        if(!empty($data['ad_name'])){
            $where['ad_name'] = ['like',"%{$data['ad_name']}%"];
        }
        $total = $this->adModel->adCount($where);
        if (isset($data['page'])){
            $cur = intval($data['page']) >0 ||intval($data['page']) ? intval($data['page']) :1;
        }else{
            $cur = 1;
        }
        $page = ceil($total/config('paginate.list_rows'));
        if ($cur >=$page){
            $cur = $page;
        }
        $this->assign('pageInfo',['page'=>$page,'cur'=>$cur,'total'=>$total]);
        $this->assign('search',$data);
        $info = $this->adModel->getAd($where,$cur);
        $this->assign('adList',$info);
        return $this->fetch('index');

    }

    /**
     * 添加广告
     */
    public function add()
    {
        return $this->fetch();

    }

    public function addHandle()
    {
        //$this->jump();
        if (!Session::has('uploadImg.imageUrl') || !Session::has('uploadImg.imageName')){
            return $res = [
                'status' => 'error',
                'errorMsg' => '图片尚没有上传',
                'result' => ''
            ];
        }
        $data = $this->request->post('', '', 'htmlspecialchars');
        //$data['start_time'] = strtotime();

        $rules = [
            'ad_name|广告名称' => 'require|length:2,10',
            'ad_type|广告位类型' => 'require|in:1,2,3,4',
            'ad_sort|广告排序' => 'require|integer|egt:0|lt:30000',
            'start_time|广告开始时间' => 'require|date',
            'end_time|广告结束时间' => 'require|date',
            'link_url' => 'require',
            'ad_description' => 'max:200',
        ];
        $msg = [
            'start_time.date' => '广告开始时间日期的格式不正确',
            'end_time.date' => '广告开始时间日期的格式不正确',
            'ad_sort.integer' => '广告排序请填写非负整数，不能大于3000',
            'ad_sort.egt' => '广告排序请填写非负整数，不能大于3000',
            'ad_sort.lt' => '广告排序请填写非负整数，不能大于3000',


        ];
        $validate = new Validate($rules,$msg);
        if (!$validate->check($data)) {
            return $res = [
                'status' => 'error',
                'errorMsg' => $validate->getError(),
                'result' => ''
            ];
        }
        $addData = [];
        if (strtotime($data['start_time']) >= strtotime($data['end_time'])){
            $addData['start_time'] = date('Y-m-d H:i:s',strtotime($data['end_time']));
            $addData['end_time'] = date('Y-m-d H:i:s',strtotime($data['start_time']));
        }else{
            $addData['start_time'] = date('Y-m-d H:i:s',strtotime($data['start_time']));
            $addData['end_time'] = date('Y-m-d H:i:s',strtotime($data['end_time']));
        }
        $addData['ad_name'] = $data['ad_name'];
        $addData['sort'] = $data['ad_sort'];
        $addData['image_url'] = Session::get('uploadImg.imageUrl').'/'.Session::get('uploadImg.imageName');
        $addData['link_url'] = $data['link_url'];
        $addData['type'] = $data['ad_type'];
        $addData['status'] = isset($data['ad_status']) ? 1 :0;
        $addData['ad_description'] = isset($data['ad_description']) ? $data['ad_description'] :'';

        $info = $this->adModel->saveAd($addData);
        if ($info){
            $res = [
                'status' => 'ok',
                'errorMsg' => '',
                'result' => ''
            ];
            Session::delete('uploadImg');
        }else{
            $res = [
                'status' => 'error',
                'errorMsg' => '数据添加失败，请重试',
                'result' => ''
            ];
        }
        return json($res);

        //print_r($data);
    }

    public function uploadImg()
    {
        $this->path = ROOT_PATH . 'public' . DS . 'uploads';
        $files = $this->request->file('file');
        $info = $files->validate(['size' => 16777216, 'ext' => 'jpg,png,gif'])->move($this->path);
        if (!$info) {
            $res = [
                'status' => 'error',
                'errorMsg' => $files->getError(),
            ];

        } else {
            $this->imageUrl = $info->getSaveName();
            $this->imageName = $info->getFilename();
            Session::set('uploadImg' , [
                    'imageUrl' => dirname($info->getSaveName()),
                    'imageName' => $info->getFilename(),
                ]

            );
            $res = [
                'status' => 'ok',
                'errorMsg' => ''
            ];
        }
        return json($res);

    }

    public function adShow()
    {
        $this->jump();

        $data = $this->request->post('','','intval');



        if (empty($data['id']) ||!in_array($data['status'],[0,1])){
            return json([
                'status' => 'error',
                'errorMsg' => '请检查字段的合法性'
            ]);
        }
        $data['status'] = ($data['status'] == 1) ? 0 : 1;
        $info = $this->adModel->setStatus($data);
        if ($info) {
            return json([
                'status' => 'ok',
                'errorMsg' => ''
            ]);
        }else{
            return json([
                'status' => 'error',
                'errorMsg' => '数据没被修改'
            ]);
        }

    }

    public function delete()
    {
        $this->jump();
        $id = $this->request->param('id','','intval');
        $result = Db::name('ad')->where(['id'=>$id])->field('image_url')->find();
        if ($result){
            unlink($this->path.DS.$result['image_url']);
        }
        $info = Db::name('ad')->where(['id'=>$id])->delete();
        if ($info){
            return json([
                'status' => 'ok',
                'errorMsg' => ''
            ]);
        }else{
            return json([
                'status' => 'error',
                'errorMsg' => '数据没被删除'
            ]);
        }
    }

    public function deleteAll()
    {
        $this->jump();
        $data = $this->request->param('','','intval');
        if (!empty($data['ids'])){
            $info = Db::name('ad')->delete($data['ids']);
            if ($info){
                return json([
                    'status' => 'ok',
                    'errorMsg' => ''
                ]);
            }else{
                return json([
                    'status' => 'error',
                    'errorMsg' => '数据没被删除'
                ]);
            }
        }

    }
    public function cancel()
    {
        $this->jump();
        unlink($this->path.DS.Session::get('uploadImg.imageUrl'));
        Session::delete('uploadImg');
    }

    private function setPath()
    {
        $this->path = ROOT_PATH . 'public' . DS . 'uploads';
    }

}
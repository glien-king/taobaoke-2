<?php
/** .-------------------------------------------------------------------
 * |    Software: []
 * | Description:
 * |        Site: www.jechorn.cn
 * |-------------------------------------------------------------------
 * |      Author: 王志传
 * |      Email : <jechorn@163.com>
 * |  CreateTime: 2017/6/28-18:57
 * | Copyright (c) 2016-2019, www.jechorn.cn. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace app\admin\controller;


use api\taobao\top\request\TbkDgItemCouponGetRequest;
use api\taobao\top\TopClient;
use app\admin\common\Api;
use think\Config;
use think\Db;
use think\Validate;

class Quan extends Api
{

    private $quanModel;
    private $QuanMsg;
    private $QuanField = 'small_images,shop_title,user_type,zk_final_price,title,nick,seller_id,volume,pict_url,item_url,coupon_total_count,commission_rate,coupon_info,category,num_iid,coupon_remain_count,coupon_start_time,coupon_end_time,coupon_click_url,item_description';
    private $total;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->quanModel = new \app\admin\model\Quan();
    }

    public function index()
    {
        $cur = $this->request->param('page', 1, 'intval');
        $total = $this->quanModel->quanCount();
        $page = ceil($total / config('paginate.list_rows'));
        if ($cur > $page) {
            $cur = $page;
        } elseif ($cur < 1) {
            $cur = 1;
        }
        $info = [];
        if ($total > 0) {
            $info = $this->quanModel->getQuan('', $cur);
        }
        $this->assign('collectList', $info);
        $this->assign('pageInfo', ['page' => $page, 'cur' => $cur, 'total' => $total]);
        return $this->fetch();
    }

    public function add()
    {
        $cate = Db::name('cate')->field('id,cate_name')->order('sort DESC')->select();
        $this->assign('cateInfo', $cate);
        return $this->fetch();
    }

    public function addHandle()
    {
        $this->jump();
        $data = $this->request->param('', '', 'htmlspecialchars');
        $rules = [

            'quan_name|采集器' => 'require|length:2,10',
            'keyword|关键词' => 'require|length:2,10',
            'cate_id|分类ID' => 'require|integer',
            'adzone_id|adzone_id' => 'require|integer|gt:0',
            'total_page|总页数' => 'require|integer|gt:0|elt:15',
        ];
        $msg = [
            'adzone_id.integer' => 'adzone_id字段填写不合法，请检查长度',
            'cate_id.integer' => '分类ID填写不合法，请检查长度',
            'total_page.integer' => '页码格式不对，必须是正整数',
        ];
        $validate = new Validate($rules, $msg);
        if (!$validate->check($data)) {
            return json([
                'status' => 'error',
                'errorMsg' => $validate->getError()
            ]);
        }
        $addData = [
            'name' => $data['quan_name'],
            'cate_id' => $data['cate_id'],
            'total_page' => $data['total_page'],
            'keyword' => $data['keyword'],
            'adzone_id' => $data['adzone_id'],
            'create_time' => date('Y-m-d H:i:s', time()),

        ];
        if (!isset($data['quan_id'])) {
            $info = $this->quanModel->insertQuan($addData);
            $title = '采集器规则添加';

        } else {
            $addData['id'] = $data['quan_id'];
            $title = '采集器规则修改';
            $info = $this->quanModel->updateQuan($addData);

        }
        if ($info) {
            return json([
                'status' => 'ok',
                'errorMsg' => ''
            ]);
        } else {
            return json([
                'status' => 'ok',
                'errorMsg' => $title . '失败'
            ]);
        }

    }

    public function delete()
    {
        $this->jump();
        $id = $this->request->param('id', '', 'intval');
        $info = Db::name('quan')->where(['id' => $id])->delete();
        if ($info) {
            return json([
                'status' => 'ok',
                'errorMsg' => ''
            ]);
        } else {
            return json([
                'status' => 'error',
                'errorMsg' => '数据没被删除'
            ]);
        }
    }


    public function deleteAll()
    {
        $this->jump();
        $data = $this->request->param('', '', 'intval');
        if (!empty($data['ids'])) {
            $info = Db::name('quan')->delete($data['ids']);
            if ($info) {
                return json([
                    'status' => 'ok',
                    'errorMsg' => ''
                ]);
            } else {
                return json([
                    'status' => 'error',
                    'errorMsg' => '数据没被删除'
                ]);
            }
        }
        return json([
            'status' => 'error',
            'errorMsg' => '非法操作'
        ]);
    }


    public function show()
    {
        $type = $this->request->param('type', '0', 'intval');
        $id = $this->request->param('id', '', 'intval');
        if (intval($type) === 1 && !empty($id)) {
            $url = url('quan/collectMsg', ['id' => $id, 'type' => 1]);
        } else {
            $url = url('quan/collectMsg', ['type' => 2]);
        }
        $this->assign('ajaxUrl', $url);
        return $this->fetch();
    }

    public function collectMsg()
    {
        if (empty($this->request->param('timed', '', 'intval'))) {
            return json([
                'status' => '1',
                'msg' => '参数请求错误'
            ]);
        }
        set_time_limit(0);//无限请求超时时间
        $type = $this->request->param('type', '1', 'intval');
        $id = $this->request->param('id', '', 'intval');
        if ($type == 1 && !empty($id)) {
            $res = Db::name('quan')
                ->field('name,keyword,cate_id,adzone_id,total_page')
                ->where(['id' => $id])
                ->find();
            if ($res) {
                $this->selectCollect($res);
                if (!empty($this->collectMsg)) {
                    return json($this->collectMsg);
                }
            } else {
                return json([
                    'status' => '4',
                    'msg' => '查询不到该采集规则'
                ]);
            }
        } elseif ($type == 2) {
            $this->autoCollect();
            if (!empty($this->collectMsg)) {
                return json($this->collectMsg);

            }

        } else {
            return json([
                'status' => '1',
                'msg' => '参数请求错误'
            ]);
        }

    }




    /**
     * @param $collectData 采集规则的信息数组，必须包含adzone_id，keyword，total_page，cate_id键值对
     * @return bool
     * status 状态码 1 代表信息入库前的必要检查不通过
     *              2  相关信息检验通过，并且所有数据采集入库成功
     *              3  系统已经正常响应，但是API服务器返回错误码
     *              4  查询到的数据为空
     *              5 采集数据成功，但是入库失败，
     *              6 相关信息检验通过，循环中一个循环数据采集入库成功
     */
    private function selectCollect(array $collectData)
    {
        if (!$this->issetApi()) {
            $this->error($this->errorMsg, url('system/home', ['type' => 2, 'url' => 2]));
        }
        $adzoneId = Config::get('system.adzone_id');
        $pageSize = 100;
        $c = new TopClient();
        $c->appkey = Config::get('system.app_key');
        $c->secretKey = Config::get('system.app_secret');
        $c->format = 'json';
        $req = new TbkDgItemCouponGetRequest();
        $req->setPlatform("1");
        $req->setPageSize("{$pageSize}");
        $req->setAdzoneId("{$adzoneId}");
        $req->setQ("{$collectData['keyword']}");
        $req->setPageNo("1");
        $resp = $c->execute($req);
        $resp = json_decode(json_encode($resp, JSON_UNESCAPED_UNICODE), true);

        if (isset($resp['error_response']) || isset($resp['code'])) {
            $this->collectMsg = [
                'status' => 3,
                'msg' => '淘宝服务器响应失败'
            ];
            return false;
            //return $this->collectMsg;

        }
        if (intval($resp['total_results']) === 0 || !isset($resp['results'])) {
            $this->collectMsg = [
                'status' => 4,
                'success_num' => 0,
                'title' => $collectData['name'],
                'msg' => "{$collectData['name']}列表没有数据,请检查关键词是否正确",
            ];

        } else {
            $data = $this->handleData($resp['results']['tbk_coupon']);

            $sql = Db::name('products')->fetchSql(true)->insertAll($data, false);
            $sql .= $this->updateSql;
            $pdo = $this->pdoConnect();
            $info = $pdo->exec($sql);
            $this->collectMsg = [
                'status' => 2,
                'success_num' => $info,
                'title' => $collectData['name'],
                'msg' => "采集{$collectData['name']}关键词成功，共入库更新{$info}数据",
            ];

        }
        return true;

    }


    /**
     * @return bool
     * 自动一键采集阿里妈妈选库表的商品
     * status 状态码 1 代表信息入库前的必要检查不通过
     *              2  相关信息检验通过，并且所有数据采集入库成功
     *              3  系统已经正常响应，但是API服务器返回错误码
     *              4  查询到的数据为空
     *              5 采集数据成功，但是入库失败，
     *              6 相关信息检验通过，循环中一个循环数据采集入库成功
     */

    private function autoCollect()
    {

        if (!$this->issetApi()) {
            $this->error($this->errorMsg, url('system/home', ['type' => 2, 'url' => 2]));
        }
        $adzoneId = Config::get('system.adzone_id');
        $pageSize = 100;
        $c = new TopClient();
        $c->appkey = Config::get('system.app_key');
        $c->secretKey = Config::get('system.app_secret');
        $c->format = 'json';
        $req = new TbkDgItemCouponGetRequest();
        $req->setPlatform("1");
        $req->setPageSize("{$pageSize}");
        $req->setAdzoneId("{$adzoneId}");
        $cate = Db::name('cate')->field('id,cate_name')->select();
        if (empty($cate)) {
            $this->collectMsg = [
                'status' => 1,
                'msg' => '请先添加分类'
            ];
            //return $this->collectMsg;
            return false;
        }

        //采集的页数
        $count = 0;
        //成功采集的数据数量
        $total = 0;
        for ($i = 1;$i <=100; $i++) {
            $count++;
            $req->setPageNo("{$i}");
            $resp = $c->execute($req);
            $resp = json_decode(json_encode($resp, JSON_UNESCAPED_UNICODE), true);
            if (isset($resp['error_response']) || isset($resp['code'])) {
                $this->collectMsg = [
                    'status' => 3,
                    'msg' => '淘宝服务器响应失败，请检查appkey是否输入正确'
                ];
                return false;
                //return $this->collectMsg;

            }
            if (intval($resp['total_results']) === 0 || !isset($resp['results'])) {
                $this->collectMsg = [
                    'status' => 4,
                    'count' => $count,
                    'success_num' => 0,
                    'msg' => "采集第{$i}页没有数据",
                ];
                continue;

            } else {
                $data = $this->handleData($resp['results']['tbk_coupon']);
                $sql = Db::name('products')->fetchSql(true)->insertAll($data, false);
                //$sql = substr_replace($sql, 'INSERT IGNORE', 0, 6);
                $sql .= $this->updateSql;
                $pdo = $this->pdoConnect();
                $info = $pdo->exec($sql);

                if ($info) {
                    $this->collectMsg = [
                        'status' => 2,
                        'count' => $count,
                        'success_num' => $info,
                        'msg' => "采集第{$i}页成功，共入库{$info}数据",
                    ];
                    $total = $total + $info;

                } else {
                    $this->collectMsg = [
                        'status' => 5,
                        'count' => $count,
                        'success_num' => $info,
                        'msg' => "采集第{$i}页成功，共入库{$info}数据",
                    ];

                }

            }

        }
        $this->collectMsg = [
            'status' => '2',
            'count' => $count,
            'total'=>$total,
            'msg' => "本次采集结束，共采集{$count}页,共{$total}条数据",
        ];
        return true;


    }


    /**
     * @param $data 处理的数据
     * @param $cateId 入库的分类ID
     * @param int $activityId 入库的活动ID
     * @return mixed
     */
    public function handleData($data)
    {
        $pattern = '/减(.*)元/';
        $category = $this->getCategory();
        $cate = Db::name('cate')
            ->field('id,cate_name,taobao_category')
            ->select();
        foreach ($data as $key => $value) {
            $data[$key]['cate_id'] = 0;

            unset($data[$key]['shop_title']);
            if (!isset($value['small_images'])) {
                $data[$key]['small_images'] = '';
            } else {
                if (!isset($value['small_images']['string'])) {
                    $data[$key]['small_images'] = '';
                } else {
                    $data[$key]['small_images'] = json_encode($value['small_images']['string'], JSON_UNESCAPED_UNICODE);
                }
            }

            if (!isset($value['coupon_click_url']) || !isset($value['coupon_end_time']) || !isset($value['coupon_info']) || !isset($value['coupon_start_time']) || !isset($value['coupon_total_count']) || !isset($value['coupon_remain_count'])) {
                unset($data[$key]);
            } else {
                preg_match($pattern, $data[$key]['coupon_info'], $matches);
                if (!empty($matches)) {
                    $data[$key]['coupon_info'] = $matches[1];
                }
                foreach ($category as $k => $v) {
                    if (in_array($value['category'],$v)) {
                        foreach ($cate as $item=>$cateValue){
                            if ($k == $cateValue['taobao_category']){
                                $data[$key]['cate_id'] = $cateValue['id'];
                            }
                        }

                    }
                }
                ksort($data[$key]);
            }

        }
        return $data;
    }


}
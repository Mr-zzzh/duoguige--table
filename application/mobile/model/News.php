<?php

namespace app\mobile\model;

class News extends Common {

    public function GetAll($params) {
        $map    = array();
        $banner = db('banner')->where(array('type' => 3, 'status' => 1))
            ->field('id,url,jumpurl,newsid')->order('sort asc,createtime desc')->select();
        $id     = db('banner')->where(array('type' => 3, 'status' => 1))->column('newsid');
        if (!empty($params['keyword'])) {
            $map['title|content'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['status']  = 1;
        $map['id']      = array('not in', $id);
        $list           = $this->where($map)
            ->field('id,title,thumb,type,view_number,like_number')
            ->order('sort asc,createtime desc')->paginate($params['limit'])->toArray();
        $list['banner'] = $banner;
        show_json(1, $list);
    }

    public function GetOne($id) {
        global $member;
        $this->where('id', $id)->setInc('view_number');
        $item = $this->get($id);
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
            if (db('like')->where(array('uid' => $member['id'], 'nid' => $id, 'type' => 1))->value('id')) {
                $item['is_like'] = 1;
            } else {
                $item['is_like'] = 2;
            }
            $item['comment_number'] = db('leave_message')->where(array('nid' => $id, 'type' => 1))->count('id');
            $item['createtime']     = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

    public function Like($params) {
        global $member;
        $type = intval($params['type']);
        $nid  = intval($params['nid']);
        if (empty($type)) {
            show_json(0, '点赞类型不能为空');
        }
        if (empty($nid)) {
            show_json(0, '点赞ID不能为空');
        }
        $like = db('like')->where(array('uid' => $member['id'], 'nid' => $nid, 'type' => $type))->value('id');
        if ($type == 1) {   //新闻
            if (empty($like)) {     //点赞
                $data = array(
                    'uid'        => $member['id'],
                    'nid'        => $nid,
                    'type'       => 1,
                    'createtime' => time(),
                );
                if (db('like')->insert($data)) {
                    $this->where('id', $nid)->setInc('like_number');
                    show_json(1, '点赞成功');
                } else {
                    show_json(0, '点赞失败');
                }
            } else {    //取消
                if (db('like')->where(array('uid' => $member['id'], 'nid' => $nid, 'type' => $type))->delete()) {
                    $this->where('id', $nid)->setDec('like_number');
                    show_json(1, '取消成功');
                } else {
                    show_json(0, '取消失败');
                }
            }
        } elseif ($type == 2) {     //留言
            if (empty($like)) {     //点赞
                $data = array(
                    'uid'        => $member['id'],
                    'nid'        => $nid,
                    'type'       => 2,
                    'createtime' => time(),
                );
                if (db('like')->insert($data)) {
                    db('leave_message')->where('id', $nid)->setInc('like_number');
                    show_json(1, '点赞成功');
                } else {
                    show_json(0, '点赞失败');
                }
            } else {    //取消
                if (db('like')->where(array('uid' => $member['id'], 'nid' => $nid, 'type' => $type))->delete()) {
                    db('leave_message')->where('id', $nid)->setDec('like_number');
                    show_json(1, '取消成功');
                } else {
                    show_json(0, '取消失败');
                }
            }
        } else {
            show_json(0, '请传正确点赞类型');
        }
    }

}
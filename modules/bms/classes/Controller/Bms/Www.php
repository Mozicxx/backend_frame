<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 网站内容管理
 */
class Controller_Bms_Www extends Controller_Bms {

    public function action_index() {
        $this->redirect(URL::site("bms/www/news", TRUE));
    }

    public function action_news() {
        $this->article(1);
    }

    public function article($cid) {
        $article = ORM::factory("Article")->where("catalog_id", "=", $cid);
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $news = $article->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($news as $m) {
                    $m->catalog_id = $m->catalog->catalog;
                    unset($m->catalog);
                    $m->add_time = date("Y-m-d H:i", $m->add_time);
                    $data[] = $m->as_array();
                }
                $res = array(
                    "total" => $article->where("catalog_id", "=", $cid)->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $article->where("id", "=", $this->request->query("id"))->find();
                $res = $article->as_array();
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $r) {
                    $r->catalog_id = $cid;
                    $r->add_time = time();
                    $r->add_by = $this->manager_name;
                    $article->where("id", "=", $r->id)->find()
                            ->values((array) $r)->save();
                }
            } elseif ($method == "delete") {
                $id = $this->request->query("id");
                if ($id) {
                    $id = explode(",", $id);
                    foreach ($id as $i) {
                        $article->where("id", "=", $i)->find()->delete();
                    }
                }
            }
            echo json_encode($res);
            die;
        } else {
            if ($this->request->param()) {
                if ($cid > 0) {
                    $this->template->content = new View("bms/www/edit_" . $cid);
                } else {
                    $this->template->content = new View("bms/www/edit");
                }
            } else {
                $this->template->content = new View("bms/www/list");
            }
        }
    }

    public function action_help() {
        $this->article(2);
    }

    public function action_page() {
        $this->article(0);
    }

}

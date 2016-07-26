<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 开发者
 * 	开发者概况
  开发者录入
  开发者维护
  开发者反馈
  应用维护
 */
class Controller_Bms_Developer extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/developer/index");
    }

    public function action_account() {
        $account = ORM::factory("UserAccount");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $users = $account->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                foreach ($users as $v) {
                    $v->add_time = date("Y-m-d", $v->add_time);
                    $v->pass_time = date("Y-m-d", $v->pass_time);
                    $v->user_id = $v->user->email;
                    unset($v->user);
                    $data[] = $v->as_array();
                }
                unset($users);
                $res = array(
                    "total" => $account->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $user) {
                    unset($user->add_time);
                    $user->pass_time = time();
                    $account->where("id", "=", $user->id)->find()
                            ->values((array) $user)->save();
                }
            } elseif ($method == "get") {
                $res = $account->where("id", "=", $this->request->query("id"))->find()->as_array();
                $res["add_time"] = date("Y-m-d H:i", $res["add_time"]);
                $res["pass_time"] = date("Y-m-d H:i", $res["pass_time"]);
                $res["pass_by"] = $res["pass_by"] ? $res["pass_by"] : $this->manager_name;
            } elseif ($method == "delete") {
                $account->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/developer/accountedit");
            } else {
                $this->template->content = new View("bms/developer/account");
            }
        }
    }

    public function action_manage() {
        $developer = ORM::factory("Developer");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $users = $developer->order_by($query["sortField"], $query["sortOrder"])
                                ->order_by("id", "DESC")
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                                ->find_all()->as_array();
                $data = array();
                foreach ($users as $v) {
                    $v->add = date("Y-m-d", $v->add);
                    $v->last_login = date("Y-m-d", $v->last_login);
                    $data[] = $v->as_array();
                }
                unset($users);
                $res = array(
                    "total" => $developer->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $user) {
                    if ($user->newpassword) {
                        $user->password = Auth::instance()->hash($user->newpassword);
                    }
                    unset($user->newpassword);
                    $developer->where("id", "=", $user->id)->find()
                            ->values((array) $user)->save();
                }
            } elseif ($method == "get") {
                $res = $developer->where("id", "=", $this->request->query("id"))->find()->as_array();
            } elseif ($method == "delete") {
                $developer->where("id", "=", $this->request->query("id"))->find()->delete();
            } elseif ($method == "login") {
                Auth_Developer::instance()->force_login($this->request->query("id"));
                $this->redirect(URL::site("developer", TRUE));
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/developer/edit");
            } else {
                $this->template->content = new View("bms/developer/list");
            }
        }
    }

    public function action_feedback() {
        $feedback = ORM::factory("Feedback");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $msg = $feedback->where("topic_id", "=", "0")
                        ->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($msg as $m) {
                    $data[] = $m->as_array();
                }
                $res = array(
                    "total" => $feedback->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $feedback->where("id", "=", $this->request->query("id"))->find();
                $res = $feedback->as_array();
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $r) {
                    $reply = array(
                        "topic_id" => $r->id,
                        "subject" => "RE:" . $r->subject,
                        "add_time" => time(),
                        "desc" => $r->reply
                    );
                    $feedback->values($reply)->save();
                    ORM::factory("Feedback", $r->id)
                            ->values(array("update_time" => time(), "update_by" => $this->manager_name, "status" => "processed"))
                            ->save();
                }
            } elseif ($method == "delete") {
                $id = $this->request->query("id");
                if ($id) {
                    $id = explode(",", $id);
                    DB::delete("feedback")->where("id", "in", $id)->execute("core");
                    DB::delete("feedback")->where("topic_id", "in", $id)->execute("core");
                }
            }
            echo json_encode($res);
            die;
        } else {
            if ($this->request->param()) {
                $this->template->content = new View("bms/developer/feedbackedit");
            } else {
                $this->template->content = new View("bms/developer/feedback");
            }
        }
    }

    public function action_app() {
        $app = ORM::factory("App");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $apps = $app->where("developer_id", ">", 0)
                        ->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($apps as $v) {
                    $v->add_time = date("Y-m-d", $v->add_time);
                    $v->developer_id = $v->developer->email;
                    unset($v->developer);
                    $data[] = $v->as_array();
                }
                unset($apps);
                $res = array(
                    "total" => $app->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $a) {
                    $app->where("id", "=", $a->id)->find()
                            ->values((array) $a)->save();
                }
            } elseif ($method == "get") {
                $res = $app->where("id", "=", $this->request->query("id"))->find()->as_array();
            } elseif ($method == "delete") {
                $app->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/app/edit");
            } else {
                $this->template->content = new View("bms/app/list");
            }
        }
    }

}

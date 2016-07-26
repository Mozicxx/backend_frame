<?php

defined('SYSPATH') or die('No direct script access.');

/**
 *
 */
class Controller_Bms_Config extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/config/index");
    }

    /**
     * 角色管理
     */
    public function action_role() {
        $role = ORM::factory("AdminRole");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $roles = $role->find_all();
                $data = array();
                foreach ($roles as $r) {
                    $data[] = $r->as_array();
                }
                $res = array(
                    "total" => $role->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $r = $role->where("role_id", "=", $id = $this->request->query("id"))->find();
                $permission = Kohana::$config->load("bms")->as_array();
                $role_permission = $role->where("role_id", "=", $id)->get_permission();
                $data = array();
                foreach ($permission as $i => $p) {
                    $permission[$i]["text"] = $i;
                    $id = $p["id"];
                    unset($p["id"]);
                    foreach ($p as $k => $m) {
                        if (isset($m["uri"]) && in_array($m["uri"], $role_permission)) {
                            $p[$k]["checked"] = TRUE;
                        }
                    }

                    $data[] = array("id" => $id, "text" => $i, "functions" => array_values($p));
                }
                $res = $r->as_array();
                $res["promission"] = $data;
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $r) {
                    $p = $r->promission;
                    unset($r->promission);
                    $role->where("role_id", "=", $r->role_id)->find();
                    $role->values((array) $r);
                    $role->save();
                    $permission = array();
                    foreach ($p as $p0) {
                        foreach ($p0->functions as $f) {
                            if (isset($f->checked) && $f->checked) {
                                $permission[] = $f->uri;
                            }
                        }
                    }
                    $role->set_permission($permission);
                }
            } elseif ($method == "delete") {
                $id = $this->request->query("id");
                if ($id) {
                    $id = explode(",", $id);
                    foreach ($id as $i) {
                        $role->where("role_id", "=", $i)->find()->delete()->set_permission(null);
                    }
                }
            }
            echo json_encode($res);
            die;
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/config/roleedit");
            } else {
                $this->template->content = new View("bms/config/role");
            }
        }
    }

    public function action_manager() {
        $adminuser = ORM::factory("AdminUser");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $user = $adminuser->find_all();
                $data = array();
                $userid = array();
                foreach ($user as $u) {
                    $u->created = $u->created ? date("Y-m-d H:i", $u->created) : "";
                    $u->login_time = $u->login_time ? date("Y-m-d H:i", $u->login_time) : "";
                    $userid[] = $u->user_id;
                    $data[] = $u->as_array();
                }
                if ($userid) {
                    $userid = implode(",", $userid);
                    $sql = "SELECT b.user_id,GROUP_CONCAT(a.role_name)as roles 
                            FROM admin_role a JOIN admin_user_role b ON a.role_id=b.role_id 
                            WHERE user_id in($userid)
                            GROUP BY b.user_id";
                    $roles = DB::query(Database::SELECT, $sql)->execute("core")->as_array("user_id");
                    foreach ($data as $i => $u) {
                        $data[$i]["roles"] = $roles[$u["user_id"]]["roles"];
                    }
                }
                $res = array(
                    "total" => $adminuser->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $user = $adminuser->where("user_id", "=", $this->request->query("id"))->find();
                $roles = $user->get_roles();
                $res = $user->as_array();
                unset($res["password"]);
                $res["roles"] = implode(",", $roles);
                $res["created"] = $res["created"] ? date("Y-m-d H:i", $res["created"]) : "";
                $res["login_time"] = $res["login_time"] ? date("Y-m-d H:i", $res["login_time"]) : "";
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $user) {
                    $adminuser->where("user_id", "=", $user->user_id)->find();

                    $roles = explode(",", $user->roles);
                    unset($user->created);
                    unset($user->login_time);
                    if ($user->password) {
                        $user->password = Auth_Bms::instance()->hash($user->password);
                    } else {
                        unset($user->password);
                    }
                    if (!$adminuser->user_id) {
                        $user->created = time();
                    }
                    $adminuser->values((array) $user)->save();

                    $adminuser->set_roles($roles);
                }
            } elseif ($method == "delete") {
                $id = $this->request->query("id");
                if ($id) {
                    $id = explode(",", $id);
                    foreach ($id as $i) {
                        $adminuser->where("user_id", "=", $i)->find()->delete()->set_roles(null);
                    }
                }
            }
            echo json_encode($res);
            die;
        } else {
            if ($this->request->param()) {
                $this->template->content = new View("bms/config/manageredit");
            } else {
                $this->template->content = new View("bms/config/manager");
            }
        }
    }

}

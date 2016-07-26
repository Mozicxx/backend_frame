<?php

defined('SYSPATH') or die('No direct script access.');
return array
    ("广告管理" => array(
        "id" => "market",
        array(
            "name" => "广告客户",
            "id" => "advertiser",
            "description" => "广告客户管理",
            "uri" => "bms/ad/advertiser",
            "display" => true,),
        array(
            "name" => "接入广告管理",
            "id" => "addad",
            "description" => "接入广告管理",
            "uri" => "bms/ad/add",
            "display" => true,),
        array(
            "name" => "广告发布",
            "id" => "pubad",
            "description" => "广告发布",
            "uri" => "bms/ad/manage",
            "display" => true,),
        array(
            "name" => "查询广告",
            "id" => "adselect",
            "description" => "查询广告",
            "uri" => "bms/ad/select",
            "display" => false,),
        array(
            "name" => "日效果对账",
            "id" => "adbills",
            "description" => "日效果对账",
            "uri" => "bms/ad/bills",
            "display" => true,),
    ),
    "运营" => array(
        "id" => "op",
        array(
            "name" => "数据监控",
            "id" => "opdata",
            "description" => "数据监控",
            "uri" => "bms/monitoring/data",
            "display" => true,),
        array(
            "name" => "数据趋势",
            "id" => "optrend",
            "description" => "数据趋势",
            "uri" => "bms/monitoring/index",
            "display" => true,),
        array(
            "name" => "用户数据",
            "id" => "reportuser",
            "description" => "用户数据",
            "uri" => "bms/report/user",
            "display" => true,),
        array(
            "name" => "每日汇总",
            "id" => "reportindex",
            "description" => "每日汇总",
            "uri" => "bms/report/index",
            "display" => true,),
    ),
    "客服" => array(
        "id" => "service",
        array(
            "name" => "用户反馈",
            "id" => "feedback",
            "description" => "用户反馈",
            "uri" => "bms/developer/feedback",
            "display" => true,),
    ),
    "财务" => array(
        "id" => "finance",
        array(
            "name" => "应收汇总",
            "id" => "f_receivables",
            "description" => "应收汇总",
            "uri" => "bms/finance/receivables",
            "display" => true,),
        array(
            "name" => "应收明细",
            "id" => "f_receivable",
            "description" => "应收明细",
            "uri" => "bms/finance/receivable",
            "display" => true,),
        array(
            "name" => "回款管理",
            "id" => "f_payback",
            "description" => "回款管理",
            "uri" => "bms/finance/payback",
            "display" => true,),
        array(
            "name" => "发票管理",
            "id" => "f_bill",
            "description" => "发票管理",
            "uri" => "bms/finance/bill",
            "display" => true,),
        array(
            "name" => "提现申请",
            "id" => "f_transfer",
            "description" => "提现申请",
            "uri" => "bms/finance/transfer",
            "display" => true,),
        array(
            "name" => "应付汇总",
            "id" => "f_payable",
            "description" => "应付汇总",
            "uri" => "bms/finance/payable",
            "display" => true,),
    ),
    "管理" => array(
        "id" => "config",
        array(
            "name" => "角色管理",
            "id" => "role",
            "description" => "角色管理",
            "uri" => "bms/config/role",
            "display" => true,),
        array(
            "name" => "员工管理",
            "id" => "user",
            "description" => "媒介审核",
            "uri" => "bms/config/manager",
            "display" => true,),
    ),
    "个人中心" => array(
        "id" => "home",
        array(
            "name" => "个人资料",
            "id" => "homeuser",
            "description" => "个人资料",
            "uri" => "bms/home/my",
            "display" => true,),
        array(
            "name" => "修改密码",
            "id" => "password",
            "description" => "修改密码",
            "uri" => "bms/home/retpwd",
            "display" => true,),
        array(
            "name" => "首页面板",
            "id" => "homeindex",
            "description" => "首页面板",
            "uri" => "bms/home/index",
            "display" => false,),
        array(
            "name" => "今日数据",
            "id" => "homeindex",
            "description" => "今日数据",
            "uri" => "bms/home/dashboard",
            "display" => false,),
        array(
            "name" => "上传文件",
            "id" => "homeupload",
            "description" => "上传文件",
            "uri" => "bms/home/upload",
            "display" => false,),
        array(
            "name" => "导航菜单",
            "id" => "password",
            "description" => "导航菜单",
            "uri" => "bms/home/menu",
            "display" => false,),
    )
);

<?php

defined('SYSPATH') or die('No direct script access.');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OSS
 *
 * @author jiwei
 */
class Kohana_Storage_Connection_OSS extends Storage_Connection {

    protected $_config = array(
        "OSS_ACCESS_ID" => "9Xf6hLC1dRmyAO36",
        "OSS_ACCESS_KEY" => "umhyRi3EBucmp5g7xdH0JQWcKUWwbx",
        "bucket" => "zt-mkmoney"
    );
    protected $bucket;

    /**
     *
     * @var ALIOSS
     */
    private $oss_sdk_service = null;

    /**
     *
     * @return ALIOSS
     */
    private function _load() {
        if ($this->oss_sdk_service === NULL) {
            require_once Kohana::find_file('vendor', 'oss_sdk/sdk.class');
            $this->oss_sdk_service = new ALIOSS($this->_config["OSS_ACCESS_ID"], $this->_config["OSS_ACCESS_KEY"]);
            //设置是否打开curl调试模式
            $this->oss_sdk_service->set_debug_mode(FALSE);
            $this->bucket = $this->_config["bucket"];
        }
        return $this->oss_sdk_service;
    }

    protected function _delete($path) {
        $this->_load();
        return $this->oss_sdk_service->delete_object($this->bucket, $path);
    }

    protected function _exists($path) {
        $this->_load();
        $res = $this->oss_sdk_service->is_object_exist($this->bucket, $path);
        if ($res->status === 200) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    protected function _get($path, $handle) {
        $this->_load();
        $options = array(
            ALIOSS::OSS_FILE_DOWNLOAD => $handle,
                //ALIOSS::OSS_CONTENT_TYPE => 'txt/html',
        );
        return $this->oss_sdk_service->get_object($this->bucket, $path, $options);
    }

    protected function _listing($path, $directory) {
        $this->_load();
        $options = array(
            'delimiter' => '',
            'prefix' => $path,
            'max-keys' => 100,
        );
        $res = $this->oss_sdk_service->list_object($this->bucket, $options);

        if ($res->status === 200) {
            $body = simplexml_load_string($res->body);
            if ($body->Contents) {
                foreach ($body->Contents as $file) {
                    $object = Storage_File::factory((string) $file->Key, $this)
                            ->size((int) $file->Size)
                            ->modified(strtotime((string) $file->LastModified));
                    $directory->set($object);
                }
            }
        }
        return $directory;
    }

    protected function _set($path, $handle, $mime) {
        $stat = fstat($handle);
        $this->_load();
        $upload_file_options = array(
            ALIOSS::OSS_FILE_UPLOAD => $handle,
            'length' => $stat["size"],
            ALIOSS::OSS_HEADERS => array(
            //'Content-Disposition'=>"inline"
            //'Expires' => date("Y-m-d H:i:s", strtotime("+10year")),
            ),
        );
        $this->oss_sdk_service->upload_file_by_content($this->bucket, $path, $upload_file_options);
    }

    protected function _size($path) {
        $this->_load();
        $res = $this->oss_sdk_service->get_object_meta($this->bucket, $path);
        if ($res->status === 200) {
            return round($res->header["content-length"] / 1024, 3);
        } else {
            return null;
        }
    }

    protected function _url($path, $protocol) {
        $this->_load();
        $res = $this->oss_sdk_service->get_object_meta($this->bucket, $path);
        if ($res->status === 200) {
            return $res->header["x-oss-request-url"];
        } else {
            return null;
        }
    }

//put your code here
}

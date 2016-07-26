<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Uploader
 *
 * @author jiwei
 */
class Uploader extends UploadHandler {

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
        $storage = Storage::factory("oss");

        $file = new stdClass();
        if ("apk" == substr($name, -3, 3)) {
            $p = new ApkParser();
            $p->open($uploaded_file);
            $file->apkinfo = array(
                "version" => $p->getVersionName(),
                "versionCode" => $p->getVersionCode(),
                "size" => round($size / (1024 * 1024), 3) . "M",
                "package" => $p->getPackage()
            );
            unset($p);
        }

        $file->name = $this->get_file_name($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        $file->size = $this->fix_integer_overflow(intval($size));
        $file->type = $type;
        $_path = date("ym") . "/" . date("dHi") . "_" . $name;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $file_path = $this->get_upload_path($file->name);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                $storage->set($_path, $uploaded_file, TRUE);
            }
            $file->url = $storage->url($_path);
            $this->set_additional_file_properties($file);
        }
        return $file;
    }

}

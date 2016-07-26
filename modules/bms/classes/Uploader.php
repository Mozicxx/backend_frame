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
        $file->name = $this->get_file_name($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        $file->size = $this->fix_integer_overflow(intval($size));
        $file->type = $type;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $file_path = $this->get_upload_path($file->name);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                $storage->set($name, $uploaded_file, TRUE);
            }
            $file->url = $storage->url($name);
            $this->set_additional_file_properties($file);
        }
        return $file;
    }

}

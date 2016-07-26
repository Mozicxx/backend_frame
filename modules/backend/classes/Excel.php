<?php

/**
 * Description of Excel
 *
 * @author jiwei
 */
class Excel {

    /**
     * 获取excel的数据
     * @param type $file
     * @param type $sheet
     * @return array
     */
    public static function getdata($file, $sheet = 0) {        
        require_once(Kohana::find_file("vendor/spreadsheetreader", "php-excel-reader/excel_reader2"));
        require_once(Kohana::find_file("vendor/spreadsheetreader", "SpreadsheetReader"));
        $Spreadsheet = new SpreadsheetReader($file);
        $Spreadsheet->ChangeSheet($sheet);
        $data = array();
        foreach ($Spreadsheet as $row) {
            $data[] = $row;
        }
        unset($Spreadsheet);
        return $data;
    }

    public static function export_csv($title, $data, $filename = null) {
        if ($title) {
            $str = iconv('utf-8', 'gb2312', implode(",", $title) . "\n");
        } else {
            $str = "";
        }
        foreach ($data as $row) {
            $str .= iconv('utf-8', 'gb2312', implode(",", $row) . "\n");
        }
        if (!$filename) {
            $filename = date('Ymd') . '.csv';
        }
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $str;
    }

}

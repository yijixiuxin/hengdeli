<?php
/**
 * 导出报告
 * Created by JetBrains PhpStorm.
 * User: Xavier
 * Date: 13-7-4
 * Time: 上午10:09
 * To change this template use File | Settings | File Templates.
 */

class export extends base {
    public static $excel = null;
    public static $eSheet = null;
    public static $eWrite = null;
    public static $eSet = null;
    public $h = 2;
    public $l = 1;
    public $column = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V' ,'W', 'X', 'Y', 'Z');
    public $cField = array('服务细节', '检测指标');
    public $cstage = null;
    public $cacheDir = 'upload/';

    public $qu = array(
        '01' => '北京区',
        '02' => '成都区',
        '03' => '福建区',
        '04' => '哈尔滨区',
        '05' => '杭州区',
        '06' => '杭州新宇',
        '07' => '合肥区',
        '08' => '江西区',
        '09' => '昆明区',
        '10' => '南京区',
        '11' => '南宁区',
        '12' => '青岛区',
        '13' => '上海区',
        '14' => '沈阳区',
        '15' => '苏州区',
        '16' => '太原区',
        '17' => '天津区',
        '18' => '温州区',
        '19' => '乌鲁木齐',
        '20' => '无锡新宇',
        '21' => '武汉区',
        '22' => '郑州区',
        '23' => '重庆区',
    );

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $user_info = model_user::cuser();
        $this->cstage = base::$context['cstage'];
        require ROOT_PATH.'/core/lib/excel/PHPExcel.php';
        $this->create_excel();
        $this->set_excel();
        $this->set_sheet();
        $this->set_column_width(25);
    }

    /**
     * 导出高层报告
     */
    public function export_report() {
        $cache = isset($_GET['cache']) ? $_GET['cache'] : 1;
        $fileName = 'g_report_'.$this->cstage['stage'].'.xlsx';
        if ($cache == 1) {
            $this->ouput_file($fileName);
        }
        $tableName = '第'.$this->cstage['stage'].'期 高层阅读报告';
        $this->write_table_name($tableName);

        //高层需要调用的方法
        $g_f = array(
                array('fun' => 'g_report_1_1', 'param' => ''),
                array('fun' => 'g_report_1_2_1', 'param' => ''),
                array('fun' => 'g_report_1_2_2', 'param' => ''),
                array('fun' => 'g_report_1_3', 'param' => ''),
                array('fun' => 'g_report_1_4', 'param' => ''),
                array('fun' => 'g_report_1_5', 'param' => ''),
                array('fun' => 'g_report_1_6_1', 'param' => ''),
                array('fun' => 'g_report_1_6_2', 'param' => ''),
                array('fun' => 'g_report_1_6_3', 'param' => ''),
                array('fun' => 'g_report_1_7_1', 'param' => ''),
                array('fun' => 'g_report_1_7_2', 'param' => ''),
                array('fun' => 'g_report_1_7_3', 'param' => ''),
                array('fun' => 'g_report_1_8_1', 'param' => ''),
                array('fun' => 'g_report_1_8_2', 'param' => ''),
                array('fun' => 'g_report_1_8_3', 'param' => ''),
                array('fun' => 'g_report_2_1', 'param' => '2'),
                array('fun' => 'g_report_2_2', 'param' => '2'),
                array('fun' => 'g_report_2_3', 'param' => '2'),
                array('fun' => 'g_report_2_1', 'param' => '3'),
                array('fun' => 'g_report_2_2', 'param' => '3'),
                array('fun' => 'g_report_2_3', 'param' => '3'),
                array('fun' => 'g_report_2_1', 'param' => '4'),
                array('fun' => 'g_report_2_2', 'param' => '4'),
                array('fun' => 'g_report_2_3', 'param' => '4'),
                array('fun' => 'g_report_2_1', 'param' => '5'),
                array('fun' => 'g_report_2_2', 'param' => '5'),
                array('fun' => 'g_report_2_3', 'param' => '5'),
                array('fun' => 'g_report_2_1', 'param' => '6'),
                array('fun' => 'g_report_2_2', 'param' => '6'),
                array('fun' => 'g_report_2_3', 'param' => '6'),
                array('fun' => 'g_report_2_1', 'param' => '7'),
                array('fun' => 'g_report_2_2', 'param' => '7'),
                array('fun' => 'g_report_2_3', 'param' => '7'),
                array('fun' => 'g_report_2_1', 'param' => '8'),
                array('fun' => 'g_report_2_2', 'param' => '8'),
                array('fun' => 'g_report_2_3', 'param' => '8'),

        );

        //获取数据并写入excel
        foreach ($g_f as $k => $f) {
            //var_dump($f);exit();
            $data = model_export::$f['fun']($f['param']);
            if (empty($data)) continue;
            if (isset($data['name'])) {
                $this->write_excel($data);
            } else {
                foreach ($data as $d) {
                    $this->write_excel($d);
                }
            }
            unset($data);
           //if ($k == 5) break;
        }
        $this->ouput_excel($fileName);
    }

    public function export_junior() {
        $acode = isset($_GET['acode']) ? $_GET['acode'] : '01';
        model_export::set_acode($acode);
        $cache = isset($_GET['cache']) ? $_GET['cache'] : 1;
        $fileName = 'j_report_'.$this->cstage['stage'].'_'.$acode.'.xlsx';
        if ($cache == 1) {
            $this->ouput_file($fileName);
        }
        $tableName = '第'.$this->cstage['stage'].'期 '.$this->qu[$acode].' 区域阅读报告';
        $this->write_table_name($tableName);

        //区域需要调用的方法
        $j_f = array(
            array('fun' => 'j_report_1_1', 'param' => ''),
            array('fun' => 'j_report_1_2', 'param' => ''),
            array('fun' => 'j_report_1_3', 'param' => ''),
            array('fun' => 'j_report_1_4', 'param' => ''),
            array('fun' => 'j_report_1_5', 'param' => ''),
            array('fun' => 'j_report_1_6', 'param' => ''),
            array('fun' => 'j_report_1_7', 'param' => ''),
            array('fun' => 'j_report_1_8', 'param' => ''),
            array('fun' => 'j_report_2_1', 'param' => '2'),
            array('fun' => 'j_report_2_2', 'param' => '2'),
            array('fun' => 'j_report_2_3', 'param' => '2'),
            array('fun' => 'j_report_2_1', 'param' => '3'),
            array('fun' => 'j_report_2_2', 'param' => '3'),
            array('fun' => 'j_report_2_3', 'param' => '3'),
            array('fun' => 'j_report_2_1', 'param' => '4'),
            array('fun' => 'j_report_2_2', 'param' => '4'),
            array('fun' => 'j_report_2_3', 'param' => '4'),
            array('fun' => 'j_report_2_1', 'param' => '5'),
            array('fun' => 'j_report_2_2', 'param' => '5'),
            array('fun' => 'j_report_2_3', 'param' => '5'),
            array('fun' => 'j_report_2_1', 'param' => '6'),
            array('fun' => 'j_report_2_2', 'param' => '6'),
            array('fun' => 'j_report_2_3', 'param' => '6'),
            array('fun' => 'j_report_2_1', 'param' => '7'),
            array('fun' => 'j_report_2_2', 'param' => '7'),
            array('fun' => 'j_report_2_3', 'param' => '7'),
            array('fun' => 'j_report_2_1', 'param' => '8'),
            array('fun' => 'j_report_2_2', 'param' => '8'),
            array('fun' => 'j_report_2_3', 'param' => '8'),
        );
        //获取数据并写入excel
        foreach ($j_f as $k => $f) {
            //var_dump($f);exit();
            $data = model_export::$f['fun']($f['param']);
            if (empty($data)) continue;
            if (isset($data['name'])) {
                $this->write_excel($data);
            } else {
                foreach ($data as $d) {
                    $this->write_excel($d);
                }
            }
            unset($data);
            //if ($k == 5) break;
        }
        $this->ouput_excel($fileName);
    }



    public function write_excel($info = array()) {
        if (empty($info)) return false;
        if (!empty($info['tTitle'])) {
            self::$eSheet->setCellValue($this->column[$this->l].$this->h, $info['tTitle'], PHPExcel_Cell_DataType::TYPE_STRING);
            self::$eSheet->mergeCells($this->column[$this->l].$this->h.':'.$this->column[$this->l+4].$this->h);
            $this->set_excel_font($this->column[$this->l].$this->h.':'.$this->column[$this->l+4].$this->h, 16, true);
            $this->h += 1;
        }
        if (!empty($info['name'])) {
            self::$eSheet->setCellValue($this->column[$this->l].$this->h, $info['name'], PHPExcel_Cell_DataType::TYPE_STRING);
            self::$eSheet->mergeCells($this->column[$this->l].$this->h.':'.$this->column[$this->l+4].$this->h);
            $this->set_excel_font($this->column[$this->l].$this->h.':'.$this->column[$this->l+4].$this->h);
            $this->h += 1;
        }
        if (!empty($info['title'])) {
            $beginL = $this->column[$this->l].$this->h;
            foreach ($info['title'] as $title) {
                if (is_string($title)) {
                    $hShu = 1;
                    if (in_array($title, $this->cField)) {
                        self::$eSheet->setCellValue($this->column[$this->l].$this->h, $title, PHPExcel_Cell_DataType::TYPE_STRING);
                        $index = $this->column[$this->l].$this->h.':'.$this->column[$this->l+4].$this->h;
                        self::$eSheet->mergeCells($index);
                        $this->l += 5;
                        $cField = true;
                    } else {
                        self::$eSheet->setCellValue($this->column[$this->l].$this->h, $title, PHPExcel_Cell_DataType::TYPE_STRING);
                        $this->l += 1;
                    }
                } else {
                    $hShu = 2;
                    if (isset($title['l']) && !isset($title['c']) && $title['l'] > 1) {
                        //有竖向合并
                        $index = $this->column[$this->l].$this->h.':'.$this->column[$this->l].($this->h+($title['l']-1));
                        self::$eSheet->setCellValue($this->column[$this->l].$this->h, $title[0], PHPExcel_Cell_DataType::TYPE_STRING);
                        self::$eSheet->mergeCells($index);
                        //$this->l += 1;
                        //$this->h += $t['l'] -1;
                    } else if (isset($title['l']) && isset($title['c']) && isset($title['cs'])) {
                        //先把所有的合并了，一会儿在拆分
                        $index = $this->column[$this->l].$this->h.':'.$this->column[$this->l+($title['c']-1)].$this->h;
                        self::$eSheet->mergeCells($index);
                        //开始写入数据
                        self::$eSheet->setCellValue($this->column[$this->l].$this->h, $title[0], PHPExcel_Cell_DataType::TYPE_STRING);
                        $this->h += 1;
                        foreach ($title['cs'] as $k =>$tc) {
                            self::$eSheet->setCellValue($this->column[$this->l+$k].$this->h ,$tc, PHPExcel_Cell_DataType::TYPE_STRING);
                            $this->set_excel_fill($this->column[$this->l+$k].$this->h);
                        }
                        $this->h -= 1;
                        //$this->h += $t['l'] - 1;
                    }
                    $this->l += isset($title['c']) ? $title['c'] : 1;
                }
            }
            $endL = $this->column[$this->l-1].$this->h;
            $this->set_excel_fill($beginL.':'.$endL);
            $this->h += $hShu;
            $this->l = 1;
        }

        if (!empty($info['data'])) {
            foreach ($info['data'] as $data) {
                if (empty($data)) continue;
                $i = 1;
                foreach($data as $d) {
                    $d = str_replace('<br />', '', $d);
                    if ($i == 1 && $cField === true) {
                        self::$eSheet->setCellValue($this->column[$this->l].$this->h, $d, PHPExcel_Cell_DataType::TYPE_STRING);
                        $index = $this->column[$this->l].$this->h.':'.$this->column[$this->l+4].$this->h;
                        self::$eSheet->mergeCells($index);
                        $this->set_excel_border($index);
                        $this->l += 5;
                    } else {
                        self::$eSheet->setCellValue($this->column[$this->l].$this->h, $d, PHPExcel_Cell_DataType::TYPE_STRING);
                        $this->set_excel_border($this->column[$this->l].$this->h);
                        $this->l += 1;
                    }
                    $i++;
                }
                $this->h += 1;
                $this->l = 1;
            }
            $this->l = 1;
        }
        $this->h += 2;
    }

    public function create_excel() {
        self::$excel = new PHPExcel();
        self::$eWrite = new PHPExcel_Writer_Excel2007(self::$excel);
        self::$eWrite->setOffice2003Compatibility(true);
    }

    public function write_table_name($tName) {
        self::$eSheet->setCellValue($this->column[$this-l].$this->h, $tName, PHPExcel_Cell_DataType::TYPE_STRING);
        $this->set_excel_font($this->column[$this-l].$this->h, 24, true);
        self::$eSheet->mergeCells($this->column[$this->l].$this->h.':'.$this->column[$this->l+5].$this->h);
        $this->h += 2;
    }

    public function set_excel_font($index, $size = 14, $bold = false) {
        $eStyle = self::$eSheet->getStyle($index);
        $eFont = $eStyle->getFont();
        $eFont->setSize($size);
        $eFont->setBold($bold);
    }

    public function set_excel_fill($index) {
        $eStyle = self::$eSheet->getStyle($index);
        $eFill = $eStyle->getFill();
        $eFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $eFill->getStartColor()->setARGB('FF808080');
    }

    public function set_excel_border($index) {
        $eStyle = self::$eSheet->getStyle($index);
        $eBorder = $eStyle->getBorders();
        $eBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $eBorder->getTop()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
        $eBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $eBorder->getRight()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
        $eBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $eBorder->getLeft()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
        $eBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $eBorder->getBottom()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
    }

    public function set_excel() {
        self::$eSet = self::$excel->getProperties();
        self::$eSet->setCreator('盈联');                //设置文档作者
        self::$eSet->setLastModifiedBy('盈联');      //设置最后修改人信息
        self::$eSet->setTitle('report');      //设置文档标题
        self::$eSet->setSubject('report');      //设置文档副标题
        self::$eSet->setDescription('report');      //设置文档描述
        self::$eSet->setKeywords('report');      //设置文档关键字
        self::$eSet->setCategory('report');      //设置文档分类
    }

    public function set_sheet() {
        self::$excel->setActiveSheetIndex(0);
        self::$eSheet = self::$excel->getActiveSheet();
        self::$eSheet->setTitle('report');
    }

    public function set_column_width($w = 30) {
        foreach($this->column as $k => $c) {
            if ($k == 0) continue;
            self::$eSheet->getColumnDimension($c)->setWidth($w);
        }
    }

    public function ouput_excel($fileName = 'report.xlsx') {
        $dirFile = $this->cacheDir.$fileName;
        //var_dump($dirFile);
        self::$eWrite->save($dirFile);
        $this->ouput_file($fileName);
    }

    public function ouput_file($fileName = '') {
        if (empty($fileName)) return false;
        $dirFile = $this->cacheDir.$fileName;
        if (!file_exists($dirFile)) {
            return false;
        }
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Disposition:inline;filename="'.$fileName.'"');
        header('Content-Transfer-Encoding: binary');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        readfile($dirFile);
        exit();
    }
}
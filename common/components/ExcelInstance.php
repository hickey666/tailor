<?php
/**
 * Created by PhpStorm.
 * User: Randal
 * Date: 2017/08/18
 * Time: 10:08
 */

namespace common\components;


use PHPExcel,
    PHPExcel_Cell_DataType,
    PHPExcel_Style_Alignment,
    PHPExcel_Style_Border,
    PHPExcel_Style_Fill,
    Yii;

class ExcelInstance
{
    /* @var ExcelInstance 入口对象自身 */
    private static $instance = null;
    /* @var PHPExcel PHPExcel对象实体 */
    private $excel = null;
    /* @var int 数据起始行 */
    private $dataStartLine = null;
    /* @var string Excel文件缓存位置 */
    private $excelDir;
    //表格头字段
    private $fields;

    /* @var string 导出文件类型 Excel5，后缀为'.xls' */
    const EXCEL_5 = 'Excel5';
    /* @var string 导出文件类型 Excel2007，后缀为'.xlsx' */
    const EXCEL_2007 = 'Excel2007';

    /**
     * 私有化构造函数
     *
     * @return void
     */
    private function __construct()
    {
        $this->excel    = new PHPExcel();
        $this->excelDir = 'php://output';
    }

    /**
     * 重写克隆方法
     * 如果已存在对象直接返回，否则获取 self::getInstance() 方法
     *
     * @return ExcelInstance
     */
    public function __clone()
    {
        if (is_null(self::$instance)) {
            self::getInstance();
        }
        return self::$instance;
    }

    /**
     * 入口接口
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ExcelInstance();
        }
        return self::$instance;
    }

    /**
     * 设置活动表格及表格头
     *
     * @param int    $index     设置活动表格编号
     * @param string $sheetName 活动表格名称
     * @param array  $fields    活动表格列标题
     * @param string $title     活动表格头及相关设置
     * @param string $headColor 活动表格列标题背景颜色
     *
     * @return ExcelInstance
     * @throws \PHPExcel_Exception
     */
    public function setActiveSheetIndex($index, $sheetName, $fields, $title = '', $headColor = 'CCCCCCCC')
    {
        $this->fields = $fields;
        if (!$this->excel->sheetCodeNameExists($index)) {
            $this->excel->createSheet($index);
        }
        $this->excel->setActiveSheetIndex($index)->setTitle($sheetName)->getColumnDimension('A')->setAutoSize(true);
        $this->dataStartLine = 2;

        $endCol     = 'A';
        $colCount   = count($this->fields);
        $fieldNames = array_values($this->fields);
        $startLine  = empty($title) ? '1' : '2';
        // 报表头的输出 先做表头确定宽度 再做标题格合并
        for ($i = 'A', $j = 0; $j < $colCount; $i++, $j++) {
            $cell = $i . $startLine;
            $this->excel->getActiveSheet()
                ->setCellValue($cell, $fieldNames[ $j ])
                ->getColumnDimension($i)
                ->setAutoSize(true);

            // 居中
            $this->excel->getActiveSheet()
                ->getStyle($cell)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            // 边框
            $this->excel->getActiveSheet()
                ->getStyle($cell)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // 颜色
            $this->excel->getActiveSheet()
                ->getStyle($cell)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($headColor);

            if ($j == $colCount - 1) {
                $endCol = $i;
            }
        }

        if ($title) {
            $this->dataStartLine = 3;
            // 设置标题位置
            $endCell = $endCol . '1';
            // 合并标题格
            $this->excel->getActiveSheet()
                ->mergeCells('A1:' . $endCell);
            // 设置标题
            $this->excel->getActiveSheet()
                ->setCellValue('A1', $title);
            // 设置标题大小
            $this->excel->getActiveSheet()
                ->getStyle('A1')
                ->getFont()
                ->setSize(24);
            // 设置标题居中
            $this->excel->getActiveSheet()
                ->getStyle('B1')
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        return $this;
    }

    /**
     * 插入数据
     *
     * @param array $data    数据集
     * @param array $fields  字段
     * @param array $extType 数据集要在表格里显示的数据类型，默认TYPE_STRING
     *                       TYPE_STRING2   TYPE_STRING     TYPE_FORMULA
     *                       TYPE_NUMERIC   TYPE_BOOL       TYPE_NULL
     *                       TYPE_INLINE    TYPE_ERROR
     *
     * @return ExcelInstance
     * @throws \PHPExcel_Exception
     */
    public function addData($data, $extType = [])
    {
        $n     = 0;
        $count = count($this->fields);
        $keys  = array_keys($this->fields);
        //明细的输出
        foreach ($data as $row) {
            for ($i = 'A', $j = 0; $j < $count; $i++, $j++) {
                $cell = $i . ($n + $this->dataStartLine);
                $this->excel->getActiveSheet()->setCellValueExplicit(
                    $cell,
                    $row[ $keys[ $j ] ]??0,
                    isset($extType[ $keys[ $j ] ]) ? $extType[ $keys[ $j ] ] : PHPExcel_Cell_DataType::TYPE_STRING
                );
            }
            $n++;
        }
        return $this;
    }

    /**
     * excelDir setter function.
     *
     * @param string $path 保存的路径，可以是 php://output
     *
     * @return ExcelInstance 接口本身
     */
    public function setExcelDir($path='php//output')
    {
        $this->excelDir = $path;

        return $this;
    }

    /**
     * 将Excel对象写入文件
     *
     * @param string $fileName 文件名称
     * @param string $version  文件版本
     *
     * @return resource 生成的Excel文件路径
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportFile($fileName, $version = self::EXCEL_5)
    {
        // $filePath = $this->excelDir . $fileName . ($version === self::EXCEL_2007 ? '.xlsx' : '.xls');

        // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // $objWriter->save($filePath);

        $fileName .= date("Y年m月j日") . ($version === self::EXCEL_2007 ? '.xlsx' : '.xls');
        $objWriter = \PHPExcel_IOFactory::createWriter(
            $this->excel,
            $version === self::EXCEL_2007 ? self::EXCEL_2007 : self::EXCEL_5
        );
        if ($this->excelDir === 'php://output') {
            $this->sendExcelHeaders($fileName, 'application/vnd.ms-excel');
        }
        return $objWriter->save($this->excelDir);
    }

    /**
     * 主动发送下载文件头信息
     *
     * @param string $fileName 文件名
     * @param string $mimeType
     *
     * @return void
     */
    private function sendExcelHeaders($fileName, $mimeType)
    {
        Yii::$app->getResponse()->setDownloadHeaders($fileName, $mimeType);

        if (!headers_sent()) {
            $headers = Yii::$app->getResponse()->getHeaders()->toArray();
            foreach ($headers as $name => $values) {
                $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
                // set replace for first occurrence of header but false afterwards to allow multiple
                $replace = true;
                foreach ($values as $value) {
                    header("$name: $value", $replace);
                    $replace = false;
                }
            }
        }
    }
}
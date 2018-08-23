<?php
namespace common\components;

use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_IOFactory;
use Symfony\Component\Finder\Iterator\DepthRangeFilterIterator;

class Excel
{
    //样式中数据位置常量
    const CENTER = 'center';
    const LEFT = 'left';
    const RIGHT = 'right';
    const TOP = 'top';
    const BOTTOM = 'bottom';

    /* @var ExcelInstance 入口对象自身 */
    private static $instance = null;
    // 文件后缀
    private $_suffix = ['xls' , 'xlsx' , 'html' ,'htm','csv'];
    //PHPExcel对象
    public $_objPHPExcel;

    public function __construct() {
        $this->_objPHPExcel = new PHPExcel ();
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
            self::$instance = new Excel();
        }
        return self::$instance;
    }


    /**
     * excel文件导出功能封装，依赖于Excel扩展包。详情请参考vendor/phpoffice/phpexcel/Classes/PHPExcel.php
     * 
     * @param array $data 需要导出的数据，格式是一个二维数组，类似于：
     * [[0 => 'zhangsan',1 => 2,2 => 2,3 => '50%',4 => '50%'],[0 => '总计',1 => 2,2 => 2,3 => '50%',4 => '50%']]  
     * 另外，此参数也提供了headerList参数，它在$headerList参数为空的情况下可以覆盖$headerList类似于：
     * [[0 => 'zhangsan',1 => 2,2 => 2,3 => '50%',4 => '50%'],[0 => '总计',1 => 2,2 => 2,3 => '50%',4 => '50%'],'headerList' =>[0 => '交易人员',
     * 1 => '稽查结果统计（真/待确认）',2 => '稽查结果统计（假）',3 => '占比（真/待确认）',4 => '占比（假）',]] 
     * 注意，在不需要设置header（表单头）样式的情况下，完全可以将所有数据存入data而不需要设置headerList，
     * data中不要有headerList为key的数据，$headerList也保持为空即可。
     * @param array $headerList header表单样式头数据，如果不需要单独设置表单头样式，可以默认为空。样式参考上面示例。
     * @param int $sheetIndex 活动页下标
     * @param string $title 表单title，也即是sheet的标题。
     * 可通过setFileName()方法设置默认名称或者不传/为空都会自动调用setFileName()获取默认filename;
     * @param array $options 提供额外参数，对样式等进行修改的可能。
     * 目前只实现了对header的样式的调整，header样式调整参数示例：
     * ['header_style' => ['hstyle' => 'center', 'vstyle' => 'center']]
     * @return object $this
     */
    public function loadData($data, $headerList = [], $sheetIndex = 0, $title = '', $options = []) {
        $this->_objPHPExcel = $this->_objPHPExcel? : (new PHPExcel());
        //设置当前活动的sheet
        $this->_objPHPExcel->setActiveSheetIndex($sheetIndex);

        //设置sheet名字
        if ($title) {
            $this->_objPHPExcel->getActiveSheet()->setTitle($title)->getColumnDimension('A')->setAutoSize(true);
        }
        //设置默认行高
        $this->_objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

        $headerList = $this->getHeaderList($data, $headerList);

        $startRow = 1;
        if ($headerList) {
            if (isset($options['header_style'])) {
                $this->setHeader($headerList, $this->_objPHPExcel, $options['header_style']);
            } else {
                $this->setHeader($headerList, $this->_objPHPExcel);
            }
            $startRow = 2;
        }

        $this->setCellValue($data, $this->_objPHPExcel, $startRow);

        return $this;

    }

    /**
     * 设置每个cell的值
     * 
     * @param array $data 纯净的能直接设置到cell中的二维数组。类似于：
     * [[0 => 'zhangsan',1 => 2,2 => 2,3 => '50%',4 => '50%'],[0 => '总计',1 => 2,2 => 2,3 => '50%',4 => '50%',]]
     * @param $objPHPExcel PHPExcel $this->_objPHPExcel 需要操作的PHPExcel对象
     * @param int $startRow 提供可以调整数据从哪一行开始循环写入的开始行数值。默认从第一行（1）开始。
     * @param int $startColumn 提供可以调整数据从哪一列开始循环写入的开始列数值。默认从第一列（0）开始。
     * @return $this
     */
    public function setCellValue($data, PHPExcel $objPHPExcel = null, $startRow = 1, $startColumn = 0) {
        $this->_objPHPExcel = $objPHPExcel? : $this->_objPHPExcel;
        foreach ($data as $rowKey => $row) {
            $rowIndex = $startRow ? $rowKey + $startRow : $rowKey;
            foreach ($row as $columnKey => $column) {
                $columnIndex = $startColumn ? $columnKey + $startColumn : $columnKey;
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnIndex, $rowIndex, $column);
                $columnIndex++;
            }
            $rowIndex++;
        }
        return $this;
    }

    /**
     * 设置表格头字段
     * @param array $data  表格头字段（必须为索引数组）
     * 如果是二位表，则数组的第一个元素应为空，如['', '一月', '二月', '三月']，或者指定键从1开始
     * @return $this
     */
    public function setRowTitle($data)
    {
       foreach ($data as $key => $val){
           $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($key, 1, $val);
       }
       return $this;
    }

    /**
     * 设置竖向的字段信息
     * @param array $data 表格头字段（必须为索引数组）
     * 如果是二位表，则数组的第一个元素应为空，如['', '一月', '二月', '三月']，或者指定键从1开始
     * @return $this
     */
    public function setColumnTitle($data)
    {
        foreach ($data as $key => $val){
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $key+1, $val);
        }
        return $this;
    }

    /**
     * 设置sheet
     * @param $index
     * @param $sheetName
     * @return $this
     */
    public function setActiveSheetIndex($index, $sheetName)
    {
        if (!$this->_objPHPExcel->sheetCodeNameExists($index)) {
            $this->_objPHPExcel->createSheet($index);
        }
        $this->_objPHPExcel->setActiveSheetIndex($index)->setTitle($sheetName)->getColumnDimension('A')->setAutoSize(true);
        return $this;
    }

    /**
     * 加载二维表的数据
     * @param $data
     * @return $this
     */
    public function loadTableData($data)
    {

        $this->setCellValue($data, $this->_objPHPExcel, 2, 1);

        return $this;
    }

    /**
     * 对data中的headerList和$headerList数据进行调整。
     * $headerList为空的时候data中的headerList有值则赋值给$headerList.
     * 最终删除data中的headerList数据。保证data中数据的纯净性。 
     * 
     * @param array $data 数据示例参照export函数
     * @param array $headerList 数据示例参照export函数
     * @return type
     */
    private function getHeaderList(&$data, $headerList = []) {
        if (isset($data['headerList']) && !empty($data['headerList'])) {
            if (empty($headerList)) {
                $headerList = $data['headerList'];
            }
            unset($data['headerList']);
        }
        return $headerList;
    }

    /**
     * 设置header数据
     * 
     * 
     * @todo 现只提供最多26个列的样式支持。现只对横排的列进行了支持，尚未支持竖排或者既有竖排又有横排的。
     * @param array $headerList 数据示例参照export函数
     * @param PHPExcel $objPHPExcel
     * @param type $options
     */
    public function setHeader($headerList, PHPExcel $objPHPExcel, $options = []) {
        $this->_objPHPExcel = $objPHPExcel? : $this->_objPHPExcel;
        $letterArr = range('A', 'Z');
        foreach ($headerList as $headerIndex => $header) {
            $objStyle = $this->_objPHPExcel
                    ->getActiveSheet()
                    ->setCellValueByColumnAndRow($headerIndex, 1, $header)
                    ->getStyle($letterArr[$headerIndex] . '1')
                    ->getAlignment();
            if (isset($options['hstyle']) && $options['hstyle']) {
                $this->setHorizontal($objStyle, $options['hstyle']);
            }
            if (isset($options['vstyle']) && $options['vstyle']) {
                $this->setVertical($objStyle, $options['vstyle']);
            }
        }
    }

    /**
     * 设置单元格（cell）的横排样式，有左右中三种值
     * 
     * @param PHPExcel_Style_Alignment $objStyle
     * @param string $style center、left、right
     * @return PHPExcel_Style_Alignment
     */
    public function setHorizontal(PHPExcel_Style_Alignment $objStyle, $style = 'center') {
        switch ($style) {
            case self::LEFT:
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                break;
            case self::CENTER:
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                break;
            case self::RIGHT:
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                break;
            default :
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                break;
        }
        return $objStyle;
    }

    /**
     * 设置单元格（cell）的竖排样式，有上中下三种值
     * 
     * @param PHPExcel_Style_Alignment $objStyle
     * @param string $style center、top、bottom
     * @return PHPExcel_Style_Alignment
     */
    public function setVertical(PHPExcel_Style_Alignment $objStyle, $style = 'center') {
        switch ($style) {
            case self::BOTTOM:
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
                break;
            case self::CENTER:
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                break;
            case self::TOP:
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_TOP);
                break;
            default :
                $objStyle->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                break;
        }
        return $objStyle;
    }

    /**
     * Enter 浏览器输出
     *
     * @param string $filename
     * @param string $type
     */

    public function output ($type="xls",$filename = "") {

        if ( ! $filename ) {
            $filename = date("YmdHis") . rand(10000, 99999) . ".xls";
        }

        if ( in_array ( $type, $this->_suffix  ) ) {
            $suffix = $type;

        }
        else {
            $suffix = "xls";
        }
        switch ($type) {
            case "xls" :
                header ( 'Content-Type: application/vnd.ms-excel' );
                header ( 'Content-Disposition: attachment;filename="' . $filename . '.' . $suffix . '"' );
                header ( 'Cache-Control: max-age=0' );
                $objWriter = PHPExcel_IOFactory::createWriter ( $this->_objPHPExcel, 'Excel5' );
                break;
            case "xlsx" :
                header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
                header ( 'Content-Disposition: attachment;filename="' . $filename . '.' . $suffix . '"' );
                header ( 'Cache-Control: max-age=0' );
                $objWriter = PHPExcel_IOFactory::createWriter ( $this->_objPHPExcel, 'Excel2007' );
                break;
            case "html" :
                header ( "Content-Type:HTML text data" );
                header ( 'Content-Disposition: attachment;filename="' . $filename . '.' . $suffix . '"' );
                header ( 'Cache-Control: max-age=0' );
                $objWriter = PHPExcel_IOFactory::createWriter ( $this->_objPHPExcel, 'HTML' );
                break;
            case "csv" :
                //header ( "Content-type:text/csv" );
                Header('Content-Type: application/msexcel;charset=gbk');
                header ( 'Content-Disposition:attachment;filename="' . $filename . '.' . $suffix . '"' );
                //header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0' );
                //header ( 'Expires:0' );
                //header ( 'Pragma:public' );
                $objWriter = PHPExcel_IOFactory::createWriter ( $this->_objPHPExcel, 'CSV' );
                break;
            default :
                header ( "Content-Type:HTML text data" );
                header ( 'Content-Disposition: attachment;filename="' . $filename . '.' . $suffix . '"' );
                header ( 'Cache-Control: max-age=0' );
                $objWriter = PHPExcel_IOFactory::createWriter ( $this->_objPHPExcel, 'HTML' );
                break;
        }

        $objWriter->save ( 'php://output' );
    }

}

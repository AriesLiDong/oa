<?php
namespace app\report\controller;
use \app\base\controller\Base as ControllerBase;
use think\Session;
vendor("PHPExcel.PHPExcel");

class Excel extends ControllerBase
{
    public function liantong_report(){
//        $file = iconv("utf-8", "gb2312", $file);   //转码
        $file = ROOT_PATH.'public/upload/1_liantong2.xlsx';
        if(empty($file) OR !file_exists($file)) {
            die('file not exists!');
        }

        $extension = strtolower( pathinfo($file, PATHINFO_EXTENSION) );

        if ($extension =='xlsx') {
            $objReader = new \PHPExcel_Reader_Excel2007();
            $objExcel = $objReader ->load($file);

        } else if ($extension =='xls') {
            $objReader = new \PHPExcel_Reader_Excel5();
            $objExcel = $objReader ->load($file);
        } else if ($extension=='csv') {
            $PHPReader = new \PHPExcel_Reader_CSV();

            //默认输入字符集
            $PHPReader->setInputEncoding('GBK');

            //默认的分隔符
            $PHPReader->setDelimiter(',');

            //载入文件
            $objExcel = $PHPReader->load($file);
        }

        $sheet = $objExcel ->getSheet(0);
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        $columnH = $sheet->getHighestColumn();   //取得最大的列号
        $columnCnt = array_search($columnH, $cellName);
        $rowCnt = $sheet->getHighestRow();   //获取总行数

        $data = array();
        for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容
            for($_column=0; $_column<=$columnCnt; $_column++){
                $cellId = $cellName[$_column].$_row;
                $cellValue = $sheet->getCell($cellId)->getValue();
                //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值
                if($cellValue instanceof \PHPExcel_RichText){   //富文本转换字符串
                    $cellValue = $cellValue->__toString();
                }

                $data[$_row][$cellName[$_column]] = $cellValue;
            }
        }
        print_r($data);exit;
        return $data;
    }
}

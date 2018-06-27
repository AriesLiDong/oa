<?php
namespace app\report\controller;
use \app\base\controller\Base as ControllerBase;
use think\Session;
use app\base\model\PhoneUser as PhoneUserModel;
vendor("PHPExcel.PHPExcel");

class Excel extends ControllerBase
{
    protected function im_report($filename,$sheets){
//        $file = iconv("utf-8", "gb2312", $file);   //转码
        $file = ROOT_PATH.'public/upload/'.$filename;
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

        $data = array();
        $sheet_cnt = explode(',',$sheets);

        foreach ($sheet_cnt as $k => $v) {
            $sheet = $objExcel ->getSheet($k);
            $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

            $columnH = $sheet->getHighestColumn();   //取得最大的列号
            $columnCnt = array_search($columnH, $cellName);
            $rowCnt = $sheet->getHighestRow();   //获取总行数

            for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容
                for($_column=0; $_column<=$columnCnt; $_column++){
                    $cellId = $cellName[$_column].$_row;
                    $cellValue = $sheet->getCell($cellId)->getValue();
                    //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值
                    if($cellValue instanceof \PHPExcel_RichText){   //富文本转换字符串
                        $cellValue = $cellValue->__toString();
                    }

                    $data[$k][$_row][$cellName[$_column]] = $cellValue;
                }
            }
        }

        return $data;
    }

    protected function out_report($data){
        error_reporting(E_ALL);
        date_default_timezone_set('Asia/chongqing');
        $objPHPExcel = new \PHPExcel();
        /*设置excel的属性*/
        $objPHPExcel->getProperties()->setCreator("shenlidong")//创建人
        ->setLastModifiedBy("shenlidong")//最后修改人
        ->setKeywords("order list")//关键字
        ->setCategory("result file");//种类
        //第一行数据
        $objPHPExcel->setActiveSheetIndex(0);
        $active = $objPHPExcel->getActiveSheet();

        //设置列宽度
        $active->getColumnDimension('A')->setWidth(20);
        $active->getColumnDimension('E')->setWidth(20);

        $index=0;
        $total = 0;
        $objPHPExcel->setActiveSheetIndex(0);
        foreach ($data as $k => $v){
            $index++;
            if(empty($tempname)){
                $tempname = $v['department'];
                $active->setCellValue('A'.$index,$v['department']);//部门
                //渲染标题
                $active = $this->set_title('A'.$index,$active);
                $index++;
            }else{
                if($tempname!=$v['department']){
                    $tempname = $v['department'];
                    $active->setCellValue('G'.($index-1),$total);
                    $active->setCellValue('A'.$index,$v['department']);//部门
                    $active = $this->set_title('A'.$index,$active);
                    $total = 0;
                    $index++;
                }
            }

            $active->setCellValue('A'.$index,$v['number']);//电话号码
            if(!isset($v['money'])){
                $active->setCellValue('E'.$index,0 );//电话费
            }else{
                $active->setCellValue('E'.$index,$v['money'] );//电话费
                $total+=$v['money'];
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ASD');
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(ROOT_PATH.'public\download\2.xls');
    }

    protected  function set_title($ck,$obj){
        //水平居中
        $obj->getStyle($ck)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //字体加粗
        $obj->getStyle($ck)->getFont()->setBold(true);

        //设置背景色
        $obj->getStyle($ck)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $obj->getStyle($ck)->getFill()->getStartColor()->setARGB('FFCAE8EA');
        return $obj;
    }
    public function import_phoneuser(){
        $filename = 'baoxiaojieguo.xlsx';
        $sheet = '0';
        $jump = [1,31,49,55,63,74,256,291,299,336,357,382,388,403,458,464,467,474];
        $data = $this->im_report($filename,$sheet);
        $number = array();
        foreach($data[0] as $key=>$value){
            if(in_array($key,$jump))
                continue;
            $num = trim($value['A']);
            $number[] = ['number'=>$num,'service'=>'联通'];
        }
        $phoneuser_model = new PhoneUserModel();
        $res = $phoneuser_model->add_m($number);
        return $res;
    }

    public function create_liantongReport(){
        $excel1 = Session::get('liantong_report')[0];
        $excel2 = Session::get('liantong_report')[1];

        //获取excel数据
        $excel1_data = $this->im_report($excel1,'0,1,2,3,4,5,6,7');
        $excel2_data = $this->im_report($excel2,'0');

        //获取电话用户
        $phoneuser_model = new PhoneUserModel();
        $data = $phoneuser_model->getAll();
        //整理数据
        $temp = array();
        foreach ($data as $k=>$v){
            $temp[$v['number']] = $v;
        }
        $data = $temp;
        //制作账单-普通电话账单
        foreach ($excel1_data as $k => $v){
            if($k==0){
                continue;
            }else if($k==1){
                $data = $this->liantong_sheet2($v,$data);
            }else if($k==2){
                $data = $this->liantong_sheet3($v,$data);
            }else if($k==3){
                $data = $this->liantong_sheet4($v,$data);
            }else if($k==4){
                $data = $this->liantong_sheet5($v,$data);
            }else if($k==5){
                $data = $this->liantong_sheet6($v,$data);
            }else if($k==6){
                $data = $this->liantong_sheet7($v,$data);
            }else if($k==7){
                $data = $this->liantong_sheet8($v,$data);
            }
        }

        //制作账单-IP电话账单
        foreach ($excel2_data[0] as $k => $v){
            $key = trim($v['B']).'IP电话';
            if(!empty($data[$key])){
                $data[$key]['money'] = $v['C'];
            }
        }

        $this->out_report($data);

    }

    protected function liantong_sheet2($sheet,$data){
        foreach ($sheet as $k=>$v){
            $preg = preg_match('#0532#',$v['B'],$mc);
            if(!$preg)
                continue;

            $key = trim($v['B']);
            $data[$key]['money'] = $sheet[$k+1]['L'];
        }
        return $data;
    }

    protected  function liantong_sheet3($sheet,$data){
        $cnt = count($sheet);
        foreach ($sheet as $k=>$v){
            $preg = preg_match('#0532#', $v['B'],$mc);
            if(!$preg)
                continue;
            $key = trim($v['B']);

            for ($i=($k+1);$i<=$cnt;$i++){
                $preg = preg_match('#小计#',$sheet[$i]['E'],$mc);
                if($preg){
                    $data[$key]['money'] = $sheet[$i]['L'];
                    break;
                }
            }
        }
        return $data;
    }

    protected function liantong_sheet4($sheet,$data){
        $cnt = count($sheet);
        foreach ($sheet as $k=>$v){
            $preg = preg_match('#185#',$v['B'],$mc);
            if(!$preg)
                continue;
            $key = trim($v['B']);
            for ($i=($k+1);$i<=$cnt;$i++){
                $preg = preg_match('#小计#',$sheet[$i]['E'],$mc);
                if($preg){
                    $data[$key]['money'] = $sheet[$i]['L'];
                    break;
                }
            }
        }
        return $data;
    }

    protected  function liantong_sheet5($sheet,$data){
        $cnt = count($sheet);
        foreach ($sheet as $k=>$v){
            $preg = preg_match('#145#',$v['B'],$mc);
            if(!$preg)
                continue;
            $key = trim($v['B']);
            for ($i=($k+1);$i<=$cnt;$i++){
                $preg = preg_match('#小计#',$sheet[$i]['E'],$mc);
                if($preg){
                    $data[$key]['money'] = $sheet[$i]['L'];
                    break;
                }
            }
        }
        return $data;
    }

    protected  function liantong_sheet6($sheet,$data){
        $cnt = count($sheet);
        foreach ($sheet as $k=>$v){
            $preg = preg_match('#145|0532#',$v['B'],$mc);
            if(!$preg)
                continue;
            $key = trim($v['B']);
            for ($i=($k+1);$i<=$cnt;$i++){
                $preg = preg_match('#小计#',$sheet[$i]['D'],$mc);
                if($preg){
                    $data[$key]['money'] = $sheet[$i]['L'];
                    break;
                }
            }
        }
        return $data;
    }

    protected  function liantong_sheet7($sheet,$data){
        foreach ($sheet as $k=>$v){
            if($k<4)
                continue;
            $key = trim($v['A']);
            if(!empty($data[$key])){
                $data[$key]['money'] = ($v['C']+$v['D']+$v['E']+$v['F']);
            }
        }
        return $data;
    }

    protected  function liantong_sheet8($sheet,$data){
        foreach ($sheet as $k=> $v) {
            if($k<4)
                continue;
            $key = trim($v['A']);
            if(!empty($data[$key])){
                $data[$key]['money'] = ($v['C']+$v['D']+$v['E']+$v['F']);
            }
        }
        return $data;
    }
}

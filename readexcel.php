<?php
ob_end_clean();//清除缓冲区,避免乱码
header("Content-Type: application/vnd.ms-excel; charset=utf-8");  
header("Content-Disposition:attachment;filename=".$_GET['filename']);
header("Pragma:no-cache");
header("Expires:0");
/*读取excel文件，并进行相应处理*/

$fileName = '../upload/'.$_GET['filename'];

if (!file_exists($fileName)) {
  exit("文件".$fileName."不存在");
}

$startTime = time(); //返回当前时间的Unix 时间戳

require_once './Classes/PHPExcel/IOFactory.php';

$inputFileType = PHPExcel_IOFactory::identify($fileName);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($fileName);

$objPHPExcel->setActiveSheetIndex(3);
$rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();
$columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();

$dataArr = array();
$result = array();
for ($row = 17; $row <= $rowCount; $row++){
  for ($column = 'B'; $column <= $columnCount; $column++) {
    $v = $objPHPExcel->getActiveSheet()->getCell($column.$row)->getValue();
    if($column === 'E' || $column === 'F') {
      $v = $v ? gmdate("Y-m-d H:i:s", PHPExcel_Shared_Date::ExcelToPHP($v)) : null;
      $v = $v ? date('m月d日', strtotime($v)) : null;
    }
    if($column === 'K' || $column === 'L') {
      $v = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(ord($column)-65,$row)->getCalculatedValue();
    }
    $dataArr[] = $v;
  }
  $result[$row-17] = $dataArr;
  $dataArr = null;
}
echo json_encode($result);
?>
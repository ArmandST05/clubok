<?php
    ob_start();
    require '../../vendor/autoload.php';
    include('../../model/ModelPoliza.php');
    $model_poliza = new ModelPoliza();
    $polizas = $model_poliza->get_polizas_activas();

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    //use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\IOFactory;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Póliza');
    $sheet->setCellValue('B1', 'Cobertura');
    $sheet->setCellValue('C1', 'Cliente');
    $sheet->setCellValue('D1', 'Tel.');
    $sheet->setCellValue('E1', 'Tel. Alternativo');
    $sheet->setCellValue('F1', 'Edad');
    $sheet->setCellValue('G1', 'Dirección');
    $sheet->setCellValue('H1', 'Marca');
    $sheet->setCellValue('I1', 'Tipo');
    $sheet->setCellValue('J1', 'Color');
    $sheet->setCellValue('K1', 'Año');
    $sheet->setCellValue('L1', 'Placa');
    $sheet->setCellValue('M1', 'No. Serie');
    $sheet->setCellValue('N1', 'Fecha Inicio');
    $sheet->setCellValue('O1', 'Fecha Fin');
    $sheet->setCellValue('P1', 'Total');
    $sheet->setCellValue('Q1', 'Saldo');
    $sheet->setCellValue('R1', 'Estatus');
    $sheet->setCellValue('S1', 'Último pago');

    $index_poliza = 2;
    foreach ($polizas as $poliza){
        $saldo = ((floatval($poliza["total_abonos"]) > 0) ? ($poliza["total"] - $poliza["total_abonos"]) : 0);
        $fecha_actual = date("Y-m-d");

        //Calcular Edad
        if ($poliza['cliente_fecha_nacimiento'] && strtotime($poliza['cliente_fecha_nacimiento']) > 0) {
            $diff = abs(strtotime($fecha_actual) - strtotime($poliza['cliente_fecha_nacimiento']));
            $anios = floor($diff / (365 * 60 * 60 * 24));
            $edad = $anios . " Años";
        } else {
            $poliza['cliente_fecha_nacimiento'] = "No asignada";
            $edad = "No asignada";
        }

        $total_poliza = floatval($poliza["total"]) - floatval($poliza["costo_expedicion"]);

        //Calcular meses transcurridos desde el inicio de la póliza
        $fecha_inicio = new DateTime($poliza["fecha_inicio"]);
        $fecha_fin = new DateTime(date("Y-m-d"));
        $diferencia = $fecha_inicio->diff($fecha_fin);
        $meses = ($diferencia->y * 12) + $diferencia->m;
        //Calcular cuánto debe de abonar cada mes
        $pago_mensual = floatval($total_poliza / $poliza["plazo"]);
        //Se considera que el primer abono registrado es el costo de expedición de la póliza
        $cantidad_pagada_real = floatval($poliza["total_abonos"] - $poliza["costo_expedicion"]);

        if ($cantidad_pagada_real < ($pago_mensual * ($meses - 3))) {
            $estatus_color = "#BD2312";
            $estatus_nombre = "INACTIVO";
        } else if ($cantidad_pagada_real < ($pago_mensual * ($meses - 2))) {
            $estatus_color = "#EC5BDF";
            $estatus_nombre = "MOROSO";
        } else {
            $estatus_color = "#9BEE38";
            $estatus_nombre = "ACTIVO";
        }

        $direccion = $poliza["cliente_direccion_calle"] . " #" . $poliza["cliente_direccion_numero"] . ", " . $poliza["cliente_direccion_colonia"] . ", " . $poliza["cliente_direccion_ciudad"] . ", " . $poliza["cliente_direccion_estado"] . ", CP:" . $poliza["cliente_direccion_codigo_postal"];
        
        //$sheet->setCellValue('A'.$index_poliza, $poliza["idpoliza"]);
        $spreadsheet->getActiveSheet()->getCell('A'.$index_poliza)->setValueExplicit($poliza["idpoliza"],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
        $sheet->setCellValue('B'.$index_poliza, $poliza["cobertura_nombre"]);
        $sheet->setCellValue('C'.$index_poliza, $poliza["cliente_nombre"]);
        $sheet->setCellValue('D'.$index_poliza, $poliza["cliente_telefono"]);
        $sheet->setCellValue('E'.$index_poliza, $poliza["cliente_telefono_alternativo"]);
        $sheet->setCellValue('F'.$index_poliza, $edad);
        $sheet->setCellValue('G'.$index_poliza, $direccion);
        $sheet->setCellValue('H'.$index_poliza, $poliza["vehiculo_marca"]);
        $sheet->setCellValue('I'.$index_poliza, $poliza["vehiculo_tipo"]);
        $sheet->setCellValue('J'.$index_poliza, $poliza["vehiculo_color"]);
        $sheet->setCellValue('K'.$index_poliza, $poliza["vehiculo_anio"]);
        $sheet->setCellValue('L'.$index_poliza, $poliza["vehiculo_placa"]);
        $sheet->setCellValue('M'.$index_poliza, $poliza["vehiculo_numero_serie"]);
        $sheet->setCellValue('N'.$index_poliza, $poliza["fecha_inicio_formato"]);
        $sheet->setCellValue('O'.$index_poliza, $poliza["fecha_fin_formato"]);
        $sheet->setCellValue('P'.$index_poliza, "$".number_format($poliza["total"],2));
        $sheet->setCellValue('Q'.$index_poliza, "$".number_format($saldo,2));
        $sheet->setCellValue('R'.$index_poliza, $estatus_nombre);
        $spreadsheet->getActiveSheet()->getStyle('R'.$index_poliza)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($estatus_color);
        $sheet->setCellValue('S'.$index_poliza, $poliza["fecha_ultimo_abono"]);

        $index_poliza ++;
    }

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('CLIENTES');
    /*
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte Excel"');
    header('Cache-Control: max-age=0');*/

    $file_name = "CLIENTES-".date("d-m-y");

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    $writer = IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save('php://output');

    
?>

<?php
include('../../model/ModelCobertura.php');
$model_cobertura = new ModelCobertura();
$cobertura_id = $_GET["cobertura_id"];
$poliza_id = $_GET["poliza_id"];

$servicios = $model_cobertura->get_lista_servicios_cobertura_poliza($cobertura_id,$poliza_id);
$cantidad_asegurada_tipos = $model_cobertura->get_lista_cantidad_asegurada_tipos();

echo "<tr>
    <th></th>
    <th>Cantidad</th>
    <th>Descripción</th>
    <th>Sumas Aseguradas ($)</th>
    <th>Prima Neta ($)</th>
    <th>Deducibles (%)</th>
    </tr>";
    foreach ($servicios as $servicio) {

      if($servicio['idpolizadetalle']){
        //Se selecciona de forma predeterminada si se incluyó como parte del tipo de cobertura/póliza 
        $seleccionado = 'checked';
        $editable = '';
        $requerido = 'required';
        $class="";
      }else{
        //Se bloquean los campos si no está incluido de forma predeterminada
        $seleccionado = '';
        $editable = 'disabled';
        $requerido = '';
        $class="bg-danger";  
      }

      $cantidad = ($servicio['cantidad']) ? $servicio['cantidad'] : 1;
      $editar_cantidad = (($servicio['cobertura_servicio_editar_cantidad'] && $servicio['editar_cantidad']) ? '' : 'readonly');
      $cantidad_asegurada_tipo_id = (($servicio['cantidad_asegurada_tipo_id']) ? $servicio['cantidad_asegurada_tipo_id']: '1');

      //Es un array con todas las cantidades aseguradas predeterminadas.
      //Si tiene varias cantidades predeterminadas se coloca un select, si tiene sólo una se deja el input con la cantidad predeterminada.
      $cantidad_asegurada_array = (($servicio['cantidad_asegurada']) ? (explode(",",$servicio['cantidad_asegurada'])): [0.00]); 
      $cantidad_deducible = (($servicio['cantidad_deducible']) ? $servicio['cantidad_deducible']: '0.00');

      echo "<tr id='tr".$servicio['idservicio']."' class='".$class."'>
              <td><input type='checkbox' class='form-control input-sm' name='servicios[".$servicio['idservicio']."][seleccionado]"."' id='checkbox".$servicio['idservicio']."' value='1' " . $seleccionado . " onclick='seleccionar_servicio(".$servicio['idservicio'].")'><label>Incluir</td>
              <td><input type='number' class='form-control input-sm' name='servicios[".$servicio['idservicio']."][cantidad]"."' id='cantidad".$servicio['idservicio']."' min='1' value='" . $cantidad . "' " . $editar_cantidad . " " . $requerido . "></td>
              <td>" . $servicio["nombre"] . "</td>
              <td>
                <div class='form-group'>
                  <select class='form-control input-sm tr-input' name='servicios[".$servicio['idservicio']."][cantidad_asegurada_tipo_id]"."' id='cantidadaseguradatipo".$servicio['idservicio']."' onchange='seleccionar_cantidad_asegurada_tipo(".$servicio['idservicio'].")' " . $requerido . " " . $editable . ">";
                    foreach ($cantidad_asegurada_tipos as $tipo){
                      echo "<option value='".$tipo["idcantidadaseguradatipo"]."' ".(($cantidad_asegurada_tipo_id == $tipo['idcantidadaseguradatipo'])? 'selected': '').">".$tipo["nombre"]."</option>";
                    }
            echo "</select>
                  </div>";
                    if($cantidad_asegurada_tipo_id == 3 && count($cantidad_asegurada_array) > 1){
                      //No mostrar select si no hay varias opciones predeterminadas.
              echo "<div class='form-group'>
                      <select class='form-control input-sm tr-input' name='personalizar_cantidad[".$servicio['idservicio']."][cantidad_asegurada_personalizar]"."' id='cantidadaseguradapersonalizar".$servicio['idservicio']."' onchange='seleccionar_cantidad_asegurada_personalizar(".$servicio['idservicio'].")' " . $requerido . " " . $editable . ">";
                        foreach ($cantidad_asegurada_array as $cantidad){
                          echo "<option value='".$cantidad."'>$".number_format($cantidad,2)."</option>";
                        }
                echo "  <option value='otro'>OTRO</option>
                    </select>
                  </div>";
                    }
                echo "<div class='input-group' style='".(($cantidad_asegurada_tipo_id == 3 && count($cantidad_asegurada_array) == 1) ? '':'display: none;')."'>
                    <span class='input-group-addon'>$</span>
                    <input type='number' step='.01' value='".$cantidad_asegurada_array[0]."' name='servicios[".$servicio['idservicio']."][cantidad_asegurada]"."' id='cantidadasegurada".$servicio['idservicio']."' class='form-control input-sm tr-input' " . $requerido . " " . $editable . ">
                  </div>
                </td>
                <td>
                  <div class='input-group'>
                    <span class='input-group-addon'>$</span>
                    <input type='number' step='.01' value='0.00' name='servicios[".$servicio['idservicio']."][prima_neta]"."' id='primaneta".$servicio['idservicio']."' class='form-control input-sm tr-input prima-neta' " . $requerido . " " . $editable . ">
                  </div>
                </td>
                <td>
                  <div class='input-group'>
                    <input type='number' step='.01' value='".$cantidad_deducible."' name='servicios[".$servicio['idservicio']."][cantidad_deducible]"."' id='deducible".$servicio['idservicio']."' class='form-control input-sm tr-input' " . $requerido . " " . $editable . ">
                    <span class='input-group-addon'>%</span>
                  </div>
                </td>
              <tr>";
    }
?>

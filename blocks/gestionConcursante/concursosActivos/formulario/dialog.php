<div class="modal fade popup" id="myModal" role="dialog">
    <div class="modal-dialog popup_margin margen-interna corner">

      <!-- Modal content-->
      <div class="modal-content ">
        <div class="modal-header" style="margin:0px; border-bottom: none; ">
          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <p style="margin: 5px 5px 5px 5px; text-align: center;">AUTORIZACIÓN</p>
        </div>
        <div style="margin: 5px 5px 5px 20px;">
            <div class="alert alert-info">

              <?php
          		$cadena_sql = $this->miSql->getCadenaSql("consultaMensaje");
          		$resultadoMensaje = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

              // ---------------- CONTROL: Checkbox -----------
              $esteCampo = 'autorizacion';
              $atributos ['id'] = $esteCampo;
              $atributos ['nombre'] = $esteCampo;
              $atributos ["etiquetaObligatorio"] = false;
              $atributos ['columnas'] = 2;
              $atributos ['tab'] = $tab ++;
              $atributos ['anchoEtiqueta'] = 2;
              $atributos ['etiqueta'] = $resultadoMensaje[0]['texto'];
              $atributos ['seleccionado'] = false;
              //$atributos ['evento'] = ' ';
              $atributos ['estilo'] = 'justificado';
              $atributos ['eventoFuncion'] = ' ';
              $atributos ['validar'] = 'required';

              //$atributos ['valor'] = '';
              $atributos = array_merge ( $atributos, $atributosGlobales );
              echo $this->miFormulario->campoCuadroSeleccion ( $atributos );
              unset ( $atributos );
              ?>
            </div>

        </div>

        <div class="modal-footer">
          <?php

            // -----------------CONTROL: Botón ----------------------------------------------------------------
            $esteCampo = 'botonInscribir';
            $atributos ["id"] = $esteCampo;
            $atributos ["tabIndex"] = $tab;
            $atributos ["tipo"] = '';
            // submit: no se coloca si se desea un tipo button genérico
            $atributos ['submit'] = true;
            $atributos ["estiloMarco"] = '';
            $atributos ["estiloBoton"] = 'jqueryui';
            // verificar: true para verificar el formulario antes de pasarlo al servidor.
            $atributos ["verificar"] = '';
            $atributos ['deshabilitado'] = false;
            $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
            $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
            $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
            $tab ++;
            // Aplica atributos globales al control
            $atributos = array_merge ( $atributos, $atributosGlobales );
            echo $this->miFormulario->campoBoton ( $atributos );
            // -----------------FIN CONTROL: Botón -----------------------------------------------------------



          ?>
        </div>
      </div>

    </div>
  </div>

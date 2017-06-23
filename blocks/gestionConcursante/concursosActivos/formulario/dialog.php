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
              <p style="text-align: justify;">
              Acorde con el reglamento de Concursos Docentes, ARTÍCULO 20º - PARÁGRAFO 2, autoriza a la Universidad para publicar la información relacionada con su Hoja de Vida y que para tal efecto se cargará lo respectivo, en el sistema de información con las restricciones señaladas en la Ley
            </div>
        </div>

        <?php
          //check de aceptación
          
        ?>


        <div class="modal-footer">
          <?php
            // -----------------CONTROL: Botón ----------------------------------------------------------------
            $esteCampo = 'botonInscribir';
            $atributos ["id"] = $esteCampo;
            $atributos ["tabIndex"] = $tab;
            $atributos ["tipo"] = 'boton';
            // submit: no se coloca si se desea un tipo button genérico
            $atributos ['submit'] = true;
            $atributos ["estiloMarco"] = '';
            $atributos ["estiloBoton"] = 'jqueryui';
            // verificar: true para verificar el formulario antes de pasarlo al servidor.
            $atributos ["verificar"] = '';
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

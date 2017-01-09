$( document ).ready(function() {
	
	var campoFecha = [];
	var campoFechaInput = [];
	
	var IFechaA = 0;
	var IFechaB= 0;
	var contFecha = 0;
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaExpDocFunMod')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaExpDocFunMod')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaNacimiento')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaNacimiento')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaFormacionBasica')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaFormacionBasica')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaFormacionMedia')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaFormacionMedia')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_0')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_0')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_1')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_1')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_2')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_2')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_3')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_3')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_4')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_4')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_5')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_5')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_6')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_6')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_7')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_7')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_8')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_8')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_9')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_9')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_10')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_10')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_11')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_11')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_12')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_12')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_13')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_13')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_14')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_14')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_15')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_15')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_16')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_16')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_17')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_17')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_18')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_18')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaInformal_19')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaInformal_19')?>";
	
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_0')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_0')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_0')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_0')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_0')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_0')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_1')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_1')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_1')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_1')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_1')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_1')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_2')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_2')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_2')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_2')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_2')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_2')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_3')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_3')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_3')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_3')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_3')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_3')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_4')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_4')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_4')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_4')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_4')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_4')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_5')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_5')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_5')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_5')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_5')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_5')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_6')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_6')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_6')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_6')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_6')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_6')?>";
	
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_7')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaConvalidaSuperior_7')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_7')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTituloSuperior_7')?>";
	campoFecha[IFechaA++] = "#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_7')?>";
	campoFechaInput[IFechaB++] = "input#<?php echo $this->campoSeguro('funcionarioFechaTarjetaSuperior_7')?>";
	
	$(campoFecha).each(function(){
		$(this.valueOf()).datepicker({
			dateFormat: 'yy-mm-dd',
			maxDate: 0,
			yearRange: '-80:+0',
			changeYear: true,
			changeMonth: true,
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
			'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
			dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
			onSelect: function(dateText, inst) {
				var lockDate = new Date($(this.valueOf()).datepicker('getDate'));
			}, onClose: function() { 
				}
		})
	});
});
	//Fija la visibilidad de los enlaces anterior/siguiente en funcion del numero slide mostrado
	function setLinksSlide(id_slide)
	{
		if ($('#SLIDE_POST_ACTUAL-'+id_slide).val() == $('#SLIDE_POST_TOTALES-'+id_slide).val()) $('#link-siguiente-'+id_slide).css('visibility', 'hidden');
		else $('#link-siguiente-'+id_slide).css('visibility', 'visible');
		if ($('#SLIDE_POST_ACTUAL-'+id_slide).val() == 1) $('#link-anterior-'+id_slide).css('visibility', 'hidden');
		else $('#link-anterior-'+id_slide).css('visibility', 'visible');

		$('#' + id_slide + ' .link-directo-slide').removeClass('on');
		$('#link-directo-slide-' + id_slide + '-'+ $('#SLIDE_POST_ACTUAL-'+id_slide).val()).addClass('on');
	}
	
    function slideAnterior(id_slide, direccion, despl_en_px, salto_a_slide)
	{
		if ($('#SLIDE_POST_ACTUAL-'+id_slide).val() == 1) return;
		
		var actual = $('#SLIDE_POST_ACTUAL-'+id_slide).val();
		var anterior;
		if (typeof(salto_a_slide) == 'undefined')
			anterior = parseInt($('#SLIDE_POST_ACTUAL-'+id_slide).val())-1;
		else
			anterior = salto_a_slide;
			
		$('#SLIDE_POST_ACTUAL-'+id_slide).val(anterior);
		
		setLinksSlide(id_slide);
		
		if (direccion == 'horizontal')
		{
			$('#post-'+id_slide + '-' + actual).animate({left: despl_en_px + "px"});
			$('#post-'+id_slide + '-' + anterior).animate({left: "0"});
		}
		else
		{
			$('#post-'+id_slide + '-' + actual).animate({top: despl_en_px + "px"});
			$('#post-'+id_slide + '-' + anterior).animate({top: "0"});
		}
	}

    function slideSiguiente(id_slide, direccion, despl_en_px, salto_a_slide)
	{
		if ($('#SLIDE_POST_ACTUAL-'+id_slide).val() == $('#SLIDE_POST_TOTALES-'+id_slide).val())
			return;

		var actual = $('#SLIDE_POST_ACTUAL-'+id_slide).val();
		var siguiente;
		
		if (typeof(salto_a_slide) == 'undefined')
			siguiente = parseInt($('#SLIDE_POST_ACTUAL-'+id_slide).val())+1;
		else
			siguiente = salto_a_slide;
		
		$('#SLIDE_POST_ACTUAL-'+id_slide).val(siguiente);
		
		setLinksSlide(id_slide);

		if (direccion == 'horizontal')
		{
			$('#post-'+id_slide + '-' + actual).animate({left: "-" + despl_en_px + "px"});
			$('#post-'+id_slide + '-' + siguiente).animate({left: "0"});
		}
		else
		{
			$('#post-'+id_slide + '-' + actual).animate({top: "-" + despl_en_px + "px"});
			$('#post-'+id_slide + '-' + siguiente).animate({top: "0"});
		}
	}
	
	function saltoASlide(id_slide, direccion, despl_en_px, salto_a_slide)
	{
		if ($('#SLIDE_POST_ACTUAL-'+id_slide).val() > salto_a_slide)
		{
			slideAnterior(id_slide, direccion, despl_en_px, salto_a_slide);
		}
		else
		{
			slideSiguiente(id_slide, direccion, despl_en_px, salto_a_slide);
		}
	}
	
	function autoCambioSlide(id_slide, direccion, despl_en_px, delay)
	{
		//Cambia al siguiente siempre que no este en el ultimo
		if ($('#SLIDE_POST_ACTUAL-'+id_slide).val() == $('#SLIDE_POST_TOTALES-'+id_slide).val())
			saltoASlide(id_slide, direccion, despl_en_px, 1);
		else
			slideSiguiente(id_slide, direccion, despl_en_px);
			
		setTimeout("autoCambioSlide('" + id_slide + "','" + direccion + "'," +  despl_en_px + "," +  delay + ")", delay);
	}

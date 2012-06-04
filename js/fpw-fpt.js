/* AJAX handlers */

jQuery( document ).ready( function( $ ) {

	$("#contextual-help-link").html(fpw_fpt.help_link_text);

	// AJAX - Update Options button
	if ( $( '#fpt-update' ).length ) {
		$( '#fpt-update' ).click( function() {
			message_div = $( '#fpt-message' );
			barr = $( 'input:checkbox:checked.fpt-option-group' ).map( function () {
  						return this.value; }).get();
  			vcwidth = $( '#content-width').attr( 'value' );
  			vcheight = $( '#content-height' ).attr( 'value' );
  			vcpos = $( '#content-position' ).find( ":selected" ).text();
  			vcradius = $( '#content-border-radius' ).attr( 'value' );
  			vcbwidth = $( '#content-border-width' ).attr( 'value' );
  			vcbocol = $( '#content-border-color' ).attr( 'value' );
  			vcbacol = $( '#content-background-color' ).attr( 'value' );
  			vcpt = $( '#content-padding-top' ).attr( 'value' );
  			vcpl = $( '#content-padding-left' ).attr( 'value' );
  			vcpb = $( '#content-padding-bottom' ).attr( 'value' );
  			vcpr = $( '#content-padding-right' ).attr( 'value' );
  			vcmt = $( '#content-margin-top' ).attr( 'value' );
  			vcml = $( '#content-margin-left' ).attr( 'value' );
  			vcmb = $( '#content-margin-bottom' ).attr( 'value' );
  			vcmr = $( '#content-margin-right' ).attr( 'value' );
  			vewidth = $( '#excerpt-width').attr( 'value' );
  			veheight = $( '#excerpt-height' ).attr( 'value' );
  			vepos = $( '#excerpt-position' ).find( ":selected" ).text();
  			veradius = $( '#excerpt-border-radius' ).attr( 'value' );
  			vebwidth = $( '#excerpt-border-width' ).attr( 'value' );
  			vebocol = $( '#excerpt-border-color' ).attr( 'value' );
  			vebacol = $( '#excerpt-background-color' ).attr( 'value' );
  			vept = $( '#excerpt-padding-top' ).attr( 'value' );
  			vepl = $( '#excerpt-padding-left' ).attr( 'value' );
  			vepb = $( '#excerpt-padding-bottom' ).attr( 'value' );
  			vepr = $( '#excerpt-padding-right' ).attr( 'value' );
  			vemt = $( '#excerpt-margin-top' ).attr( 'value' );
  			veml = $( '#excerpt-margin-left' ).attr( 'value' );
  			vemb = $( '#excerpt-margin-bottom' ).attr( 'value' );
  			vemr = $( '#excerpt-margin-right' ).attr( 'value' );
			message_div.html( '<p><strong>Please wait...</strong></p>' ).load( fpw_fpt.ajaxurl, {
				boxes:						barr,
				content_width:				vcwidth,
				content_height:				vcheight,
				content_position:			vcpos,
				content_border_radius:		vcradius,
				content_border_width:		vcbwidth,
				content_border_color:		vcbocol,
				content_background_color:	vcbacol,
				content_padding_top:		vcpt,
				content_padding_left:		vcpl,
				content_padding_bottom:		vcpb,
				content_padding_right:		vcpr,
				content_margin_top:			vcmt,
				content_margin_left:		vcml,
				content_margin_bottom:		vcmb,
				content_margin_right:		vcmr,
				excerpt_width:				vewidth,
				excerpt_height:				veheight,
				excerpt_position:			vepos,
				excerpt_border_radius:		veradius,
				excerpt_border_width:		vebwidth,
				excerpt_border_color:		vebocol,
				excerpt_background_color:	vebacol,
				excerpt_padding_top:		vept,
				excerpt_padding_left:		vepl,
				excerpt_padding_bottom:		vepb,
				excerpt_padding_right:		vepr,
				excerpt_margin_top:			vemt,
				excerpt_margin_left:		veml,
				excerpt_margin_bottom:		vemb,
				excerpt_margin_right:		vemr,
				action:						'fpw_pt_update'
			}).delay(750);
  			$('#fpt-message').fadeIn(2500).delay(4000).fadeOut(1500);
			return false;
		});
	}

	// AJAX - Get Language File button
	if ( $( '#fpt-language' ).length ) {
		$( '#fpt-language' ).click( function() {
			message_div = $( '#fpt-message' );
			message_div.html( '<p><strong>Please wait...</strong></p>' ).load( fpw_fpt.ajaxurl, {
				action:		'fpw_pt_language'
			}).delay(750);
  			$('#fpt-message').fadeIn(1500).delay(4000).fadeOut(1500);
			return false;
		});
	}

	//	farbtastic magic for color inputs
    $('#colorpicker-content-border-color').hide();
    $('#colorpicker-content-border-color').farbtastic('#content-border-color');

    $('#content-border-color').click(function() {
        $('#colorpicker-content-border-color').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker-content-border-color').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });

    $('#colorpicker-content-background-color').hide();
    $('#colorpicker-content-background-color').farbtastic('#content-background-color');

    $('#content-background-color').click(function() {
        $('#colorpicker-content-background-color').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker-content-background-color').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });

    $('#colorpicker-excerpt-border-color').hide();
    $('#colorpicker-excerpt-border-color').farbtastic('#excerpt-border-color');

    $('#excerpt-border-color').click(function() {
        $('#colorpicker-excerpt-border-color').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker-excerpt-border-color').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });

    $('#colorpicker-excerpt-background-color').hide();
    $('#colorpicker-excerpt-background-color').farbtastic('#excerpt-background-color');

    $('#excerpt-background-color').click(function() {
        $('#colorpicker-excerpt-background-color').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker-excerpt-background-color').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });

});


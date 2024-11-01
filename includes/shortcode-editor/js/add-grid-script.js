/* global tinyMCE */
( function( $ ) {
	if ( !wp.media )
		return;

	function i18n( str ) {
		if ( wgextraEditor[ str ] )
			return wgextraEditor[ str ];
		else
			return str;
	}

	var $doc = $( document ),
		$win = $( window );

	var $modal;

	function GUI( $element, reInitialize ) {
		if ( $element.hasClass( 'ui-initialized' ) && !reInitialize )
			return;

		// Enable tabs
		$element.find( '[rel="tabs"]' ).not( $element.find( '.ui-tabs-panel [rel="tabs"]' ) ).each( function(){
			var $tab = $( this ),
				options = $tab.data( 'options' );

			if ( options )
				options = eval( "({" + options + "})" );
			else
				options = {};

			var disabled = options.disabled || false,
				active = typeof options.active === 'number' && options.active || 0;

			$tab.tabs( {
				disabled: disabled,
				active: active,
				classes: {
					"ui-tabs": "wgextra-ui-tabs",
					"ui-tabs-panel": "wgextra-ui-tabs-panel"
				},
				activate: function( event, ui ) {
					GUI( ui.newPanel );

					// reload codemirrors
					ui.newPanel.find('textarea.codemirror-enabled').not('[rel="visual_editor"]').each(function(){
						$(this).data('editor').refresh();
					});

					$modal.dialog( "option", "position", {
						my: "center",
						at: "center",
						of: window
					} );
				},
				create: function( event, ui ) {
					if ( ui.panel.is( ':visible' ) )
						GUI( ui.panel );
				}
			} );
		} );

		// Enable selectboxes
		var selectboxEachFn = function() {
			var $this = $( this ),
				name = this.name,
				width = $this.outerWidth();

			$this.selectmenu( {
				classes: {
					"ui-selectmenu-button": "wgextra-ui-selectmenu-button",
					"ui-selectmenu-menu": "wgextra-ui-selectmenu-menu"
				},
				width: width || false,
				change: function( event ) {
					$( event.target ).trigger( 'change' );
				}
			} );
		};
		var $select = $element.find( "select" ).not( '[multiple], .in-controlgroup' ).not( $element.find( '.ui-tabs-panel select' ) );
		$select.filter( ':hidden' ).one( 'visible', selectboxEachFn );
		$select.filter( ':visible' ).each( selectboxEachFn );


		// Enable multiple selectboxes
		var multipleSelectBoxEachFn = function() {
			var $this = $( this );

			$this.multiSelect({
				selectableOptgroup: true,
				selectableHeader: "<input type='text' class='search-input full-width' autocomplete='off' placeholder='" + i18n('Type to Search...') + "'>",
				selectionHeader: "<input type='text' class='search-input full-width' autocomplete='off' placeholder='" + i18n('Type to Search...') + "'>",
				afterInit: function(ms) {
					var that = this,
						$selectableSearch = that.$selectableUl.prev(),
						$selectionSearch = that.$selectionUl.prev(),
						selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
						selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

					that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
						.on('keydown', function(e) {
							if (e.which === 40) {
								that.$selectableUl.focus();
								return false;
							}
						});

					that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
						.on('keydown', function(e) {
							if (e.which == 40) {
								that.$selectionUl.focus();
								return false;
							}
						});
				},
				afterSelect: function() {
					this.qs1.cache();
					this.qs2.cache();
				},
				afterDeselect: function() {
					this.qs1.cache();
					this.qs2.cache();
				}
			});

			$this.on('update', function() {
				$this.multiSelect('refresh');
			});
		};
		$element.find("select[multiple]").not($element.find('.ui-tabs-panel select[multiple]')).each(multipleSelectBoxEachFn);

		// Enable spinner
		var spinnerEachFn = function() {
			var $spinner = $( this ),
				options = $spinner.data( 'options' );

			if ( options )
				options = eval( "({" + options + "})" );
			else
				options = {};

			var max = parseFloat( options.max ) || null,
				min = parseFloat( options.min ) || 0,
				step = parseFloat( options.step ) || 1;

			$spinner.spinner( {
				min: min,
				max: max,
				step: step,
				stop: function( event, ui ) {
					$spinner.trigger( 'change' );
				}
			} );
		};
		$element.find( 'input[rel="number"]' ).not( $element.find( '.ui-tabs-panel input[rel="number"]' ) ).each( spinnerEachFn );
		// Enable radios
		$element.find( 'input[type="radio"], input[type="checkbox"]' ).not('.wgee-onoffswitch-checkbox').not($element.find('.ui-tabs-panel input[type="radio"], .ui-tabs-panel input[type="checkbox"]')).checkboxradio({
			icon: false
		});
		// Control group
		var controlgroupEachFn = function(){
			$( this ).controlgroup();
		};
		var $controlgroup = $element.find( '.wgee-switch-field, .controlgroup' ).not($element.find('.ui-tabs-panel .switch-field, .ui-tabs-panel .controlgroup'));
		$controlgroup.filter( ':hidden' ).one( 'visible', controlgroupEachFn );
		$controlgroup.filter( ':visible' ).each( controlgroupEachFn );

		// Enable range slider
		var rangeEachFn = function(){
			var $input = $(this),
				$slider = $('<div></div>'),
				options = $input.data('options'),
				value = eval($input.val());

			if (options)
				options = eval("({" + options + "})");
			else
				options = {};

			var range = options.range || false,
				max = parseFloat(options.max) || 100,
				min = parseFloat(options.min) || 0,
				step = parseFloat(options.step) || 1,
				pips = options.pips || false,
				type = options.type || "",
				forceTip = options.forceTip || false;

			$slider.insertAfter( $input );
			$input.hide();

			if ( options.class )
				$slider.addClass( options.class );

			if ( forceTip )
				$slider.addClass( 'ui-slider-force-tip' );

			if ( type === 'circle' )
				$slider.addClass( 'ui-circle-slider' );
			else if ( type === 'scale' )
				$slider.addClass( 'ui-scale-slider' );

			var sliderOptions = {
				range: range,
				min: min,
				max: max,
				step: step,
				slide: function( event, ui ) {
					if ( ui.values )
						$input.val( JSON.stringify( ui.values ) );
					else
						$input.val( ui.value );
				},
				stop: function( event, ui ) {
					$input.trigger('change');
				}
			};

			sliderOptions[$.isArray( value ) ? 'values' : 'value'] = value;

			$slider.slider(sliderOptions);

			$input.on( 'update', function(event){
				$slider.slider( "value", $input.val() );
			} );

			if (pips)
				$slider.slider("pips", pips).slider("float");
		};
		$element.find( 'input[rel="range"]' ).not($element.find('.ui-tabs-panel input[rel="range"]')).each(rangeEachFn);

		// Enable textarea
		var textareaEachFn = function() {
			var element = this,
				$this = $( element ),
				$parent = $this.parents('td'),
				className = element.className;

			var options = {
					selectionPointer: true,
					styleActiveLine: true,
					lineNumbers: true,
					matchBrackets: true,
					matchTags: {bothTags: true},
					indentUnit: 4,
					indentWithTabs: true,
					autoCloseBrackets: true,
					autoCloseTags: true,
					keyMap: 'sublime',
					extraKeys: {
						"Ctrl-Space": "autocomplete",
						"Ctrl-K": function (cm, event) {
							cm.state.colorpicker.popup_color_picker();
						}
					},
					viewportMargin: Infinity,
					theme: "mdn-like"
				};

			if (className.indexOf('css') !== -1) {
				options.mode = "text/x-less";
				options.colorpicker = {
					mode: 'edit'
				};
			}
			else if (className.indexOf('javascript') !== -1) {
				options.mode = "text/javascript";
			}
			else if (className.indexOf('twig') !== -1) {
				//options.mode = { name: "twig", htmlMode: true, alignCDATA: true };
				CodeMirror.defineMode("htmltwig", function(config, parserConfig) {
					return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), CodeMirror.getMode(config, "twig"));
				});
				options.mode = { name: "htmltwig", htmlMode: true, alignCDATA: true };
			}
			else if (className.indexOf('html') !== -1) {
				options.mode = { name: "htmlmixed", alignCDATA: true };
			}

			var editor = CodeMirror.fromTextArea(element, options);

			//editor.setSize(width);
			editor.display.wrapper.style.maxWidth = $parent.width() + "px";

			$this.data('editor', editor);

			editor.on("change", function(instance, changeObj) {
				$this.val(editor.getValue()).trigger('input');
			});

			$this.on('update', function(){
				editor.setValue($this.val());
			});

			$win.on('resize', _.debounce( function(){
				editor.display.wrapper.style.maxWidth = $parent.width() + "px";
			}, 250 ) );

			$this.addClass('codemirror-enabled');
		};
		var $textarea = $element.find( 'textarea' ).not($element.find('.ui-tabs-panel textarea'));
		$textarea.filter(':hidden').one('visible', textareaEachFn);
		$textarea.filter(':visible').each(textareaEachFn);

		// Enable colorpicker
		$element.find( '[rel="colorpicker"]' ).not($element.find('.ui-tabs-panel [rel="colorpicker"]')).minicolors({
			control: 'hue',
			format: 'rgb',
			inline: false,
			opacity: true,
			theme: 'bootstrap',
			changeDelay: 300,
			change: function(value, opacity) {
				$(this).trigger('change');
			}
		});

		// Tooltip
		$element.tooltip({
			classes: {
				"ui-tooltip": "wgextra-ui-tooltip"
			},
			position: {
				my: "center bottom-20",
				at: "center top",
				using: function( position, feedback ) {
					$( this ).css( position );
					$( "<div>" )
					.addClass( "wgextra-ui-arrow" )
					.addClass( feedback.vertical )
					.addClass( feedback.horizontal )
					.appendTo( this );
				}
			},
			content: function () {
				return $(this).prop('title');
			}
		});

		$element.addClass( 'ui-initialized' );
	}

	function dataHasError( data ) {
		if ( typeof data.template_id === 'undefined' ) {
			return i18n( 'Please choose a template!' );
		} else if ( data.limit && !is_numeric( data.limit ) ) {
			return sprintf( i18n( "Please enter a valid number in the '%s' field." ), i18n( "Item Number" ) );
		}

		return false;
	}

	function normalizeData( data ) {
		data['advanced-options'] = data['advanced-options'] ? 'yes' : 'no';
		data['use-lowres-image'] = data['use-lowres-image'] ? 'yes' : 'no';

		if ( data.template === 'columns' ) {
			delete data['row-height'];
			delete data['max-row-height'];
		}

		if ( data.link !== 'custom' )
			delete data['link-url'];

		if ( data['loading-type'] === 'none' ) {
			delete data['loading-animation'];
			delete data['use-lowres-image'];
		} else if ( data['loading-type'] === 'indicator' ) {
			delete data['use-lowres-image'];
		}

		/* Tilt */
		data['tilt'] = data['tilt'] ? 'yes' : 'no';
		data['tilt-reset'] = data['tilt-reset'] ? 'yes' : 'no';
		if ( data.tilt === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(tilt)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		/* Lightboxes */
		data['magnific-vertical-fit'] = data['magnific-vertical-fit'] ? 'yes' : 'no';
		data['magnific-preload'] = data['magnific-preload'] ? 'yes' : 'no';
		data['magnific-deeplink'] = data['magnific-deeplink'] ? 'yes' : 'no';
		data['photoswipe-loop'] = data['photoswipe-loop'] ? 'yes' : 'no';
		data['photoswipe-deeplink'] = data['photoswipe-deeplink'] ? 'yes' : 'no';
		data['photoswipe-share-buttons'] = data['photoswipe-share-buttons'] ? 'yes' : 'no';
		data['photoswipe-download'] = data['photoswipe-download'] ? 'yes' : 'no';
		data['fancybox-loop'] = data['fancybox-loop'] ? 'yes' : 'no';
		data['fancybox-deeplink'] = data['fancybox-deeplink'] ? 'yes' : 'no';
		data['fancybox-download'] = data['fancybox-download'] ? 'yes' : 'no';
		data['fancybox-preload'] = data['fancybox-preload'] ? 'yes' : 'no';
		data['ilightbox-loop'] = data['ilightbox-loop'] ? 'yes' : 'no';
		data['ilightbox-carousel-mode'] = data['ilightbox-carousel-mode'] ? 'yes' : 'no';
		data['ilightbox-deeplink'] = data['ilightbox-deeplink'] ? 'yes' : 'no';
		data['ilightbox-share-buttons'] = data['ilightbox-share-buttons'] ? 'yes' : 'no';
		data['ilightbox-thumbnails'] = data['ilightbox-thumbnails'] ? 'yes' : 'no';
		if ( data.lightbox === 'none' ) {
			$.each( data, function ( key, value ) {
				if ( /^(magnific|photoswipe|fancybox|ilightbox)-/.test( key ) ) {
					delete data[key];
				}
			} );
			delete data['custom-lightbox'];
		} else if ( data.lightbox === 'magnific' ) {
			$.each( data, function ( key, value ) {
				if ( /^(photoswipe|fancybox|ilightbox)-/.test( key ) ) {
					delete data[key];
				}
			} );
			delete data['custom-lightbox'];
		} else if ( data.lightbox === 'photoswipe' ) {
			$.each( data, function ( key, value ) {
				if ( /^(magnific|fancybox|ilightbox)-/.test( key ) ) {
					delete data[key];
				}
			} );
			delete data['custom-lightbox'];
		} else if ( data.lightbox === 'fancybox' ) {
			$.each( data, function ( key, value ) {
				if ( /^(magnific|photoswipe|ilightbox)-/.test( key ) ) {
					delete data[key];
				}
			} );
			delete data['custom-lightbox'];
		} else if ( data.lightbox === 'ilightbox' ) {
			$.each( data, function ( key, value ) {
				if ( /^(magnific|photoswipe|fancybox)-/.test( key ) ) {
					delete data[key];
				}
			} );
			delete data['custom-lightbox'];
		} else if ( data.lightbox === 'custom' ) {
			$.each( data, function ( key, value ) {
				if ( /^(magnific|photoswipe|fancybox|ilightbox)-/.test( key ) ) {
					delete data[key];
				}
			} );
			data['custom-lightbox'] = encodeURIComponent( data['custom-lightbox'] );
		}

		/* Slider */
		data['slider-auto-scale'] = data['slider-auto-scale'] ? 'yes' : 'no';
		data['slider-start-random'] = data['slider-start-random'] ? 'yes' : 'no';
		data['slider-free-scroll'] = data['slider-free-scroll'] ? 'yes' : 'no';
		data['slider-loop'] = data['slider-loop'] ? 'yes' : 'no';
		data['slider-scrollbar'] = data['slider-scrollbar'] ? 'yes' : 'no';
		data['slider-arrows'] = data['slider-arrows'] ? 'yes' : 'no';
		data['slider-bullets'] = data['slider-bullets'] ? 'yes' : 'no';
		data['slider-thumbnails'] = data['slider-thumbnails'] ? 'yes' : 'no';
		data['slider-repeat-cycling'] = data['slider-repeat-cycling'] ? 'yes' : 'no';
		data['slider-pause-on-hover'] = data['slider-pause-on-hover'] ? 'yes' : 'no';
		data['slider-start-paused'] = data['slider-start-paused'] ? 'yes' : 'no';
		data['slider-mouse-dragging'] = data['slider-mouse-dragging'] ? 'yes' : 'no';
		data['slider-touch-dragging'] = data['slider-touch-dragging'] ? 'yes' : 'no';
		data['slider-release-swing'] = data['slider-release-swing'] ? 'yes' : 'no';
		data['slider-elastic-bounds'] = data['slider-elastic-bounds'] ? 'yes' : 'no';
		data['slider-one-page-drag'] = data['slider-one-page-drag'] ? 'yes' : 'no';
		data['slider-scrollbar-drag-handle'] = data['slider-scrollbar-drag-handle'] ? 'yes' : 'no';
		data['slider-scrollbar-dynamic-handle'] = data['slider-scrollbar-dynamic-handle'] ? 'yes' : 'no';
		data['slider-scrollbar-clickable'] = data['slider-scrollbar-clickable'] ? 'yes' : 'no';
		data['slider-kenburns-reverse'] = data['slider-kenburns-reverse'] ? 'yes' : 'no';
		data['slider-time-loader-toggle'] = data['slider-time-loader-toggle'] ? 'yes' : 'no';

		if ( data['slider-thumbnails'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(slider-thumbnails)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}
		if ( data['slider-arrows'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(slider-arrows)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}
		if ( data['slider-scrollbar'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(slider-scrollbar)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}
		if ( data['slider-bullets'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(slider-bullets)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}
		if ( !data['slider-kenburns-mode'] ) {
			$.each( data, function ( key, value ) {
				if ( /^(slider-kenburns)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}
		if ( !data['slider-cycle-by'] ) {
			delete data['slider-pause-time'];
			delete data['slider-repeat-cycling'];
			delete data['slider-pause-on-hover'];
			delete data['slider-start-paused'];
		}

		/* Sources */
		if ( (data['ordering-order-by'] || '').indexOf('meta_value') === -1 && (data['ordering-order-by-fallback'] || '').indexOf('meta_value') === -1 ) {
			delete data['ordering-meta-key'];
		}

		/* Display */
		data['detect-focus-point'] = data['detect-focus-point'] ? 'yes' : 'no';
		data['fixed-ratio'] = data['fixed-ratio-width'] + ':' + data['fixed-ratio-height'];
		delete data['fixed-ratio-width'];
		delete data['fixed-ratio-height'];

		data['max-row-height'] = data['max-row-height'] + data['max-row-height-unit'];
		delete data['max-row-height-unit'];

		if ( data['grouped-items-mode'] !== 'slider' ) {
			delete data['grouped-items-template'];
		}

		/* Style */
		data['placeholder'] = data['placeholder'] ? 'yes' : 'no';
		data['placeholder-overlay'] = data['placeholder-overlay'] ? 'yes' : 'no';
		data['placeholder-readable-caption'] = data['placeholder-readable-caption'] ? 'yes' : 'no';
		data['placeholder-background'] = data['placeholder-background'] ? 'yes' : 'no';

		if ( data['placeholder'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(placeholder)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		data['border'] = data['border'] ? 'yes' : 'no';
		if ( data['border'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(border)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		data['shadow'] = data['shadow'] ? 'yes' : 'no';
		data['shadow-inset'] = data['shadow-inset'] ? 'yes' : 'no';
		if ( data['shadow'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(shadow)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		data['icon'] = data['icon'] ? 'yes' : 'no';
		if ( data['icon'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(icon)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		data['caption'] = data['caption'] ? 'yes' : 'no';
		data['caption-inset'] = data['caption-inset'] ? 'yes' : 'no';
		if ( data['caption'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(caption)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		data['overlay'] = data['overlay'] ? 'yes' : 'no';
		if ( data['overlay'] === 'no' ) {
			$.each( data, function ( key, value ) {
				if ( /^(overlay)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		if ( data['thumbnail-effect'] === 'none' ) {
			$.each( data, function ( key, value ) {
				if ( /^(thumbnail-effect)-/.test( key ) ) {
					delete data[key];
				}
			} );
		}

		return wp.hooks.applyFilters( 'wgee-normalize-data', data );
	}

	function normalizeInputs( data ) {
		var fixedRatio = data['fixed-ratio'].split( ':' );
		data['fixed-ratio-width'] = fixedRatio[0];
		data['fixed-ratio-height'] = fixedRatio[1];
		delete data['fixed-ratio'];

		var maxRowHeight = data['max-row-height'],
			maxRowHeightInt = parseInt( maxRowHeight ),
			maxRowHeightUnit = maxRowHeight.replace( maxRowHeightInt, '' );
		data['max-row-height'] = maxRowHeightInt;
		data['max-row-height-unit'] = maxRowHeightUnit;

		return wp.hooks.applyFilters( 'wgee-normalize-inputs', data );
	}

	$doc.ready( function( editor, values, onsubmit_callback ) {
		$modal = $( '#wgextra-editor-dialog' );

		var $editor_inputs = $modal.find( 'input, textarea, select' ),
			editor_defaults = $.extend( {}, wgextraEditor.default_template_options, $editor_inputs.serializeObject() ),
			$error_container = $( '<div class="ui-dialog-error hidden"></div>' ),
			$form = $modal.find( 'form' ),
			$scroll_container = $modal.find( '.scroll-container' ),
			$primaryTabs = $modal.find( '[rel="tabs"].advanced-options' ),
			$fields = $modal.find( '.field' ),
			$displayFields = $(document.getElementById( 'wgee-display-tab' )).find( '.field' ),
			$sourceFields = $(document.getElementById( 'wgee-source-tab' )).find( '.field' ),
			$taxonomy_terms_select = $(document.getElementById( 'wgee-source-tab' )).find('select#wgee-taxonomies');

		$.WGExtraAGP = function( editor, values, submit_callback ) {
			var template = wgextraEditor.templates[values.template_id] || {};
			var default_values = normalizeInputs( $.extend( {}, editor_defaults, template, values ) );

			$modal.dialog( {
				resizable: true,
				height: "auto",
				width: 960,
				//minHeight: 600,
				minWidth: 960,
				modal: true,
				classes: {
					"ui-dialog": "ui-dialog-wgextra-editor ui-corner-all"
				},
				buttons: [ {
						text: i18n( "Insert" ),
						class: 'primary',
						click: function() {
							var data = normalizeData( cleanObject( $form.serializeObject() ) );

							if ( error = dataHasError( data ) ) {
								$modal.parent().effect( "shake" );
								$error_container.removeClass( 'hidden' ).html( error );
								return;
							}

							var template = wgextraEditor.templates[data.template_id];

							data = _.omit( data, function(v,k) { return template[k] == v; } );

							if ( typeof submit_callback === 'function' ) {
								submit_callback.call( $form[ 0 ], data );
							}

							$modal.dialog( "close" );
						},
					},
					{
						text: i18n( "Reset" ),
						click: function() {
							$editor_inputs.setValues( default_values );
						}
					},
					{
						text: i18n( "Cancel" ),
						click: function() {
							$modal.dialog( "close" );
						}
					}
				],
				open: function( event, ui ) {
					GUI( $modal );

					$scroll_container.scrollTop(0);
					$primaryTabs.tabs( "option", "active", 0 );
					$fields.filter('.advanced-options')[default_values['advanced-options'] ? 'removeClass' : 'addClass']( 'hidden' );
					$modal.dialog("option", "position", { my: "center", at: "center", of: window });

					$editor_inputs.setValues( default_values );

					$error_container.addClass( 'hidden' );

					$win.on( 'resize.WGExtraAGP', _.debounce( function(){
						$modal.dialog("option", "position", { my: "center", at: "center", of: window });
					} , 100 ) );
				},
				close: function( event, ui ) {
					$win.off('.WGExtraAGP');
				},
				create: function( event, ui ) {
					$modal.parent().find( '.ui-dialog-buttonpane' ).append( $error_container );
				}
			} );
		};

		$doc.on( 'click', '.wgextra-add-grid', function() {
			var id = $( this ).parents( '.wp-editor-wrap' ).find( 'textarea.wp-editor-area' ).attr( 'id' ),
				editor = tinyMCE.get( id );

			wp.mce.wgextra.popupwindow( editor );
		} );

		var lastData = {};

		$modal.on( 'change', 'input, textarea, select', _.debounce( function( event, isTrigger ) {
			var name = this.name,
				data = $form.serializeObject();

			if ( _.isEqual( lastData, data ) ) {
				return;
			}

			console.log( data );

			if ( name === 'template_id' && data.template_id !== lastData.template_id && !isTrigger ) {
				var values;

				if ( wgextraEditor.templates[ data.template_id ] ) {
					var template = wgextraEditor.templates[ data.template_id ],
						values = normalizeInputs( $.extend( {}, template ) );
				} else {
					values = $.extend( {}, editor_defaults );
					console.log('none', values);
				}

				$editor_inputs.setValues( values );

				// Set taxonomies
				if ( values.taxonomies && values.taxonomies.length )
					setTimeout(function(){
						$editor_inputs.filter('[name="taxonomies"]').setValues( { taxonomies: values.taxonomies } );
					}, 100);
			} else if ( name === 'advanced-options' ) {
				$fields.filter('.advanced-options')[ this.checked ? 'removeClass' : 'addClass' ]( 'hidden' );
			} else {
				if ( data.template !== lastData.template ) {
					if ( data.template === 'slider' )
						$primaryTabs.tabs( "enable", 1 );
					else {
						$primaryTabs.tabs( "disable", 1 );
						if ( $primaryTabs.tabs( "option", "active" ) == 1 )
							$primaryTabs.tabs( "option", "active", 0 );
					}

					// Handle gallery fields
					$displayFields.addClass('hidden').filter( getVisibleFields( wgextraEditor.templates_types[ data.template ].fields ) ).removeClass('hidden');
				}

				// Handle sources fields
				if ( data.source !== lastData.source ) {
					$sourceFields.addClass('hidden').filter( getVisibleFields( wgextraEditor.sources_types[ data.source ].fields ) ).removeClass('hidden');
				}

				// Handle sources fields
				if ( data['post-types'] !== lastData['post-types'] ) {
					set_taxonomies_selectbox( $taxonomy_terms_select, data );
				}

				/* General Fields */
				$fields.filter('[rel="use-lowres-image"]')[ data['loading-type'] === 'lazyload' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="loading-animation"]')[ data['loading-type'] !== 'none' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="link-url"]')[ data['link'] === 'custom' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="tilt-options"]')[ data['tilt'] ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="thumbnail_ratio_manual"]')[ data['thumbnail-ratio'] === 'manual' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="grouped_items_template"]')[ data['grouped-items-mode'] === 'slider' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="use_placeholder"]')[ data['placeholder'] ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="has_border"]')[ data['border'] ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="has_shadow"]')[ data['shadow'] ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="has_icon"]')[ data['icon'] ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="has_caption"]')[ data['caption'] ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="has_overlay"]')[ data['overlay'] ? 'removeClass' : 'addClass' ]( 'hidden' );

				$fields.filter('[rel="scrollbar-track-solid"]')[ data['slider-scrollbar-track'] === 'solid' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="scrollbar-track-gradient"]')[ data['slider-scrollbar-track'] === 'gradient' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="scrollbar-handle-solid"]')[ data['slider-scrollbar-handle'] === 'solid' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="scrollbar-handle-gradient"]')[ data['slider-scrollbar-handle'] === 'gradient' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="time-loader-color-solid"]')[ data['slider-time-loader-color-type'] === 'solid' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="time-loader-color-gradient"]')[ data['slider-time-loader-color-type'] === 'gradient' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="caption-background-solid"]')[ data['caption-background'] === 'solid' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="caption-background-gradient"]')[ data['caption-background'] === 'gradient' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="overlay-background-solid"]')[ data['overlay-background'] === 'solid' ? 'removeClass' : 'addClass' ]( 'hidden' );
				$fields.filter('[rel="overlay-background-gradient"]')[ data['overlay-background'] === 'gradient' ? 'removeClass' : 'addClass' ]( 'hidden' );

				/* Lightboxes Fields */
				if ( data.lightbox !== lastData.lightbox ) {
					$fields.filter('[rel="magnific-lightbox"]')[ data.lightbox === 'magnific' ? 'removeClass' : 'addClass' ]( 'hidden' );
					$fields.filter('[rel="photoswipe-lightbox"]')[ data.lightbox === 'photoswipe' ? 'removeClass' : 'addClass' ]( 'hidden' );
					$fields.filter('[rel="fancybox-lightbox"]')[ data.lightbox === 'fancybox' ? 'removeClass' : 'addClass' ]( 'hidden' );
					$fields.filter('[rel="ilightbox-lightbox"]')[ data.lightbox === 'ilightbox' ? 'removeClass' : 'addClass' ]( 'hidden' );
					$fields.filter('[rel="custom-lightbox"]')[ data.lightbox === 'custom' ? 'removeClass' : 'addClass' ]( 'hidden' );
				}

				// Handle sources ordering order by field
				$fields.filter('[rel="ordering-meta-key"]')[(data['ordering-order-by'] || '').indexOf('meta_value') !== -1 || (data['ordering-order-by-fallback'] || '').indexOf('meta_value') !== -1 ? 'removeClass' : 'addClass']('hidden');
			}

			lastData = data;

			$modal.dialog( "option", "position", {
				my: "center",
				at: "center",
				of: window
			} );
		}, 50 ) );

		function set_taxonomies_selectbox($taxonomy_terms_select, data) {
			var wgextra_post_types = $.isArray(data['post-types']) ? data['post-types'] : [data['post-types']],
				html = '';

			$.each(wgextra_post_types, function(pi, post_type) {
				if(wgextraEditor.post_types_taxonomies[post_type]) {
					$.each(wgextraEditor.post_types_taxonomies[post_type], function(tax_key, taxonomy) {
						if (taxonomy.terms.length === 0)
							return true;

						html += "<optgroup label='" + taxonomy.label + "'>";
							$.each(taxonomy.terms, function(i, term) {
								var optionValue = tax_key + ":" + term.slug;
								html += "<option value='" + optionValue + "'>" + term.name + " (" + term.count + " " + i18n('post(s)') + ")</option>";
							});
						html += "</optgroup>";
					});
				}
			});

			$taxonomy_terms_select.html(html);
			$taxonomy_terms_select.multiSelect('refresh');
		}
	} );

	function getVisibleFields( fields ) {
		var type_fields = fields,
			split_fields = type_fields.split(',');

		split_fields = split_fields.map(function(x) {
			return '[rel="' + $.trim(x) + '"]';
		});

		return split_fields.join(', ');
	}

	function is_numeric( value ) {
		return ( typeof( value ) === 'number' || typeof( value ) === 'string' ) && value !== '' && !isNaN( value );
	}

	function isEmpty( val ) {
		if ( val === undefined )
			return true;

		if ( typeof val == 'function' || typeof val == 'number' || typeof val == 'boolean' || Object.prototype.toString.call( val ) === '[object Date]' )
			return false;

		// null or 0 length array
		if ( val == null || val.length === 0 )
			return true;

		// empty object
		if ( typeof val == "object" && Object.keys( val ).length === 0 )
			return true;

		return false;
	}

	function cleanObject( obj ) {
		Object.keys( obj ).forEach( function( key ) {
			if ( Object.prototype.toString.call( obj[ key ] ) === '[object Object]' && !isEmpty( obj[ key ] ) ) cleanObject( obj[ key ] );
			if ( isEmpty( obj[ key ] ) ) delete obj[ key ];
		} );

		return obj;
	};

	function sprintf() {
		var regex = /%%|%(\d+\$)?([-+'#0 ]*)(\*\d+\$|\*|\d+)?(?:\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
		var a = arguments;
		var i = 0;
		var format = a[ i++ ];
		var _pad = function( str, len, chr, leftJustify ) {
			if ( !chr ) {
				chr = ' ';
			}
			var padding = ( str.length >= len ) ? '' : new Array( 1 + len - str.length >>> 0 ).join( chr );
			return leftJustify ? str + padding : padding + str;
		}
		var justify = function( value, prefix, leftJustify, minWidth, zeroPad, customPadChar ) {
			var diff = minWidth - value.length
			if ( diff > 0 ) {
				if ( leftJustify || !zeroPad ) {
					value = _pad( value, minWidth, customPadChar, leftJustify )
				} else {
					value = [
						value.slice( 0, prefix.length ),
						_pad( '', diff, '0', true ),
						value.slice( prefix.length )
					].join( '' )
				}
			}
			return value
		}
		var _formatBaseX = function( value, base, prefix, leftJustify, minWidth, precision, zeroPad ) {
			// Note: casts negative numbers to positive ones
			var number = value >>> 0
			prefix = ( prefix && number && {
				'2': '0b',
				'8': '0',
				'16': '0x'
			}[ base ] ) || ''
			value = prefix + _pad( number.toString( base ), precision || 0, '0', false )
			return justify( value, prefix, leftJustify, minWidth, zeroPad )
		}
		// _formatString()
		var _formatString = function( value, leftJustify, minWidth, precision, zeroPad, customPadChar ) {
			if ( precision !== null && precision !== undefined ) {
				value = value.slice( 0, precision )
			}
			return justify( value, '', leftJustify, minWidth, zeroPad, customPadChar )
		}
		// doFormat()
		var doFormat = function( substring, valueIndex, flags, minWidth, precision, type ) {
			var number, prefix, method, textTransform, value
			if ( substring === '%%' ) {
				return '%'
			}
			// parse flags
			var leftJustify = false
			var positivePrefix = ''
			var zeroPad = false
			var prefixBaseX = false
			var customPadChar = ' '
			var flagsl = flags.length
			var j
			for ( j = 0; j < flagsl; j++ ) {
				switch ( flags.charAt( j ) ) {
					case ' ':
						positivePrefix = ' '
						break
					case '+':
						positivePrefix = '+'
						break
					case '-':
						leftJustify = true
						break
					case "'":
						customPadChar = flags.charAt( j + 1 )
						break
					case '0':
						zeroPad = true
						customPadChar = '0'
						break
					case '#':
						prefixBaseX = true
						break
				}
			}
			// parameters may be null, undefined, empty-string or real valued
			// we want to ignore null, undefined and empty-string values
			if ( !minWidth ) {
				minWidth = 0
			} else if ( minWidth === '*' ) {
				minWidth = +a[ i++ ]
			} else if ( minWidth.charAt( 0 ) === '*' ) {
				minWidth = +a[ minWidth.slice( 1, -1 ) ]
			} else {
				minWidth = +minWidth
			}
			// Note: undocumented perl feature:
			if ( minWidth < 0 ) {
				minWidth = -minWidth
				leftJustify = true
			}
			if ( !isFinite( minWidth ) ) {
				throw new Error( 'sprintf: (minimum-)width must be finite' )
			}
			if ( !precision ) {
				precision = 'fFeE'.indexOf( type ) > -1 ? 6 : ( type === 'd' ) ? 0 : undefined
			} else if ( precision === '*' ) {
				precision = +a[ i++ ]
			} else if ( precision.charAt( 0 ) === '*' ) {
				precision = +a[ precision.slice( 1, -1 ) ]
			} else {
				precision = +precision
			}
			// grab value using valueIndex if required?
			value = valueIndex ? a[ valueIndex.slice( 0, -1 ) ] : a[ i++ ]
			switch ( type ) {
				case 's':
					return _formatString( value + '', leftJustify, minWidth, precision, zeroPad, customPadChar )
				case 'c':
					return _formatString( String.fromCharCode( +value ), leftJustify, minWidth, precision, zeroPad )
				case 'b':
					return _formatBaseX( value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad )
				case 'o':
					return _formatBaseX( value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad )
				case 'x':
					return _formatBaseX( value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad )
				case 'X':
					return _formatBaseX( value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad )
						.toUpperCase()
				case 'u':
					return _formatBaseX( value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad )
				case 'i':
				case 'd':
					number = +value || 0
					// Plain Math.round doesn't just truncate
					number = Math.round( number - number % 1 )
					prefix = number < 0 ? '-' : positivePrefix
					value = prefix + _pad( String( Math.abs( number ) ), precision, '0', false )
					return justify( value, prefix, leftJustify, minWidth, zeroPad )
				case 'e':
				case 'E':
				case 'f': // @todo: Should handle locales (as per setlocale)
				case 'F':
				case 'g':
				case 'G':
					number = +value
					prefix = number < 0 ? '-' : positivePrefix
					method = [ 'toExponential', 'toFixed', 'toPrecision' ][ 'efg'.indexOf( type.toLowerCase() ) ]
					textTransform = [ 'toString', 'toUpperCase' ][ 'eEfFgG'.indexOf( type ) % 2 ]
					value = prefix + Math.abs( number )[ method ]( precision )
					return justify( value, prefix, leftJustify, minWidth, zeroPad )[ textTransform ]()
				default:
					return substring
			}
		}
		return format.replace( regex, doFormat )
	}

	$.fn.setValues = function( values ) {
		var $fields = this;

		if ( !$.isPlainObject( values ) )
			return;

		$.each( values, function( name, value ) {
			var $element = $fields.filter( '[name="' + name + '"]' );

			if ( $element[ 0 ] ) {
				var type = ( $element[ 0 ].type || $element[ 0 ].tagName ).toLowerCase().replace( 'select-one', 'select' );

				if ( type === 'radio' ) {
					$element.filter( '[value="' + value + '"]' ).prop( 'checked', true ).trigger( 'change', [ true ] );
					if ( $element.hasClass( 'ui-checkboxradio' ) )
						$element.checkboxradio( "refresh" );
				} else if ( type === 'checkbox' ) {
					$element.prop( 'checked', (value === 'yes' || value === 'on') ).trigger( 'change', [ true ] );
				} else if ( type === 'select' ) {
					if ( value ) {
						$element.val( value ).trigger( 'change', [ true ] );
					} else {
						$element[0].selectedIndex = 0;
					}

					try {
						$element.selectmenu( 'refresh' );
					} catch ( e ) {}
				} else if ( type === 'select-multiple' ) {
					if ( typeof value === 'string' ) {
						value = value.split( ',' ).map(function(option) {
							return $.trim( option );
						});
					}

					$element.val( value ).trigger( 'change', [ true ] );

				} else if ( type === 'textarea' ) {
					$element.val( decodeURIComponent( value ) ).trigger( 'change', [ true ] );
				} else {
					if ( $element.val() !== value ) {
						$element.val( value ).trigger( 'change', [ true ] );
						if ( $element.hasClass( 'minicolors-input' ) ) {
							$element.minicolors( 'value', {
								color: value
							} );
						}
					}
				}
				$element.trigger( 'update' );
			}
		} );

		return this;
	};
}( jQuery ) );
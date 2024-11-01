(function($) {
	jQuery.expr[':'].regex = function(elem, index, match) {
		var matchParams = match[3].split(','),
			validLabels = /^(data|css):/,
			attr = {
				method: matchParams[0].match(validLabels) ? 
							matchParams[0].split(':')[0] : 'attr',
				property: matchParams.shift().replace(validLabels,'')
			},
			regexFlags = 'ig',
			regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
		return regex.test(jQuery(elem)[attr.method](attr.property));
	}

	jQuery(document).ready(function($){
		var $doc = $(document),
			$win = $(window);

		$doc.on('click', '.rebuild_thumbnails a', function(){
			var $this = $(this),
				$parent = $this.parents('tr'),
				$filename = $parent.find('.title p.filename'),
				id = $this[0].getAttribute('image-id'),
				nonce = $this[0].getAttribute('nonce');

			$parent.addClass('loading');

			$.ajax({
				method: "POST",
				url: ajaxurl,
				data: {
					action: "wgextra",
					id: id,
					wgextra_task: "process_image_resize",
					wgextra_nonce: nonce
				}
			})
			.done(function(data) {
				$parent.removeClass('loading');
				if (data.message)
					$filename.text(data.message);
			})
			.fail(function(data) {
				$parent.removeClass('loading');
			});

			return false;
		});

		$doc
		.on("click", ".toggle-add-attachment-term", function(e){
			e.preventDefault();
			jQuery(this).closest(".attachment-term-section").find(".add-new-term").toggle();
		})
		.on("click", ".attachment-term-section .media-category-tabs li", function(e){
			e.preventDefault();

			var $this = $(this),
				tab = $this.data("tab");
			$this.parent("ul").find("li").addClass("hide-if-no-js").removeClass("tabs");
			$this.removeClass("hide-if-no-js").addClass("tabs");
			$this.closest(".attachment-term-section").find(".attachment-terms").toggleClass("attachment-terms-popular");
		})
		.on("DOMNodeInserted", ".media-modal .attachment-details, .media-modal table.compat-attachment-fields, .media-modal form.compat-item", _.debounce(function(e){
			if ($(e.target).is('.media-modal .attachment-details, .media-modal table.compat-attachment-fields, .media-modal form.compat-item')) {
				$doc.trigger('wgextra:wpmedia-dom-refresh');
			}
		}, 200) )
		.on("DOMNodeInserted", ".media-modal .media-frame-content .image-editor", _.debounce(function(e){
			if (this === e.target) {
				$doc.trigger('wgextra:wpmedia-edit-image');
			}
		}, 500) )
		.on("wgextra:wpmedia-open-modal", _.debounce(function(){
			initUI.call(document);
		}, 10) )
		.on("wgextra:wpmedia-dom-refresh", function(){
			initUI.call(document);
		} );

		var initUI = function() {
			$('.compat-field-wgextra_dominant_color input:not(.wp-color-picker)').wpColorPicker();

			var $sliders_selectboxes = $('.compat-field-wgextra_columns_size select:visible, .compat-field-wgextra_rows_size select:visible'),
				$picker_selectboxes = $('.compat-field-wgextra_icon select:visible');

			$sliders_selectboxes.each(function() {
				var $select = $(this),
					$slider = $('<div><div class="ui-slider-handle"></div></div>'),
					$handle = $slider.find('.ui-slider-handle'),
					value = $select.val();

				$select.hide();

				$select.parent().addClass('slider-enabled');

				$slider.insertAfter($select);
				$slider.slider({
					value: value && value.replace('-12', '') || 0,
					min: 0,
					max: 12,
					step: 1,
					create: function() {
						value = $( this ).slider( "value" );
						$handle.text( value == 0 ? 'Auto' : value + '/12' );
					},
					slide: function( event, ui ) {
						$handle.text( ui.value == 0 ? 'Auto' : ui.value + '/12' );
						if ( ui.value == 0 )
							$select[0].selectedIndex = 0;
						else
							$select.val( ui.value + '-12' );
					},
					change: function( event, ui ) {
						$select.trigger('change');
					}
				});
			});

			$picker_selectboxes.each(function() {
				var $select = $(this);
				var multiselect = $select.attr('multiple');
				$select.hide();

				var $buttonsHTML = $('<div class="wgextraOptionPicker"></div>');
				var selectIndex = 0;
				var addOptGroup = function(optGroup) {
					if (optGroup.attr('label')) {
						$buttonsHTML.append('<strong>' + optGroup.attr('label') + '</strong>');
					}
					var ulHtml = $('<ul class="select-buttons">');
					optGroup.children('option').each(function() {
						var img_src = $(this).data('img-src');
						var color = $(this).data('color');

						var liHtml = $('<li></li>');
						if ($(this).attr('disabled') || $select.attr('disabled')) {
							liHtml.addClass('disabled');
							liHtml.append('<span>' + $(this).html() + '</span>');
						} else {

							if (color) {
								liHtml.append('<a href="#" style="background-color:' + color + '" data-select-index="' + selectIndex + '">&nbsp;</a>');
							} else if (img_src) {
								liHtml.append('<a href="#" data-select-index="' + selectIndex + '"><img class="image_picker" src="' + img_src + '"></a>');
							} else {
								liHtml.append('<a href="#" data-select-index="' + selectIndex + '">' + $(this).html() + '</a>');
							}
						}

						// Mark current selection as "picked"
						if ($(this).attr('selected')) {
							liHtml.children('a, span').addClass('picked');
						}
						ulHtml.append(liHtml);
						selectIndex++;
					});
					$buttonsHTML.append(ulHtml);
				}

				var optGroups = $select.children('optgroup');
				if (optGroups.length == 0) {
					addOptGroup($select);
				} else {
					optGroups.each(function() {
						addOptGroup($(this));
					});
				}

				$select.after($buttonsHTML);

				var $options_links = $buttonsHTML.find('a, span');
				var $activeItem = $options_links.filter('.picked'),
					height = $buttonsHTML.outerHeight(),
					offsetTop = $activeItem[0].offsetTop;

				$buttonsHTML.scrollTop(offsetTop + ($activeItem.outerHeight() / 2) - (height / 2));

				$buttonsHTML.on('click', 'a', function(e) {
					e.preventDefault();
					var $this = $(this),
						index = $this.attr('data-select-index');

					if (multiselect) {
						var clickedOption = $($select.find('option')[index]);
						if (clickedOption.attr('selected')) {
							$this.removeClass('picked');
							clickedOption.removeAttr('selected');
						} else {
							$this.addClass('picked');
							clickedOption.attr('selected', 'selected');
						}
					} else {
						$select[0].selectedIndex = index;
						if (!$this.hasClass('picked')) {
							$options_links.removeClass('picked');
							$this.addClass('picked');
						}
					}

					$select.trigger('change');
				});
			});

			$doc.trigger('wgextra:wpmedia-init-ui');
		};

		if ( location.href.indexOf('&action=edit') !== -1 && $( 'body' ).hasClass( 'post-type-attachment' ) ) {
			$doc.trigger('wgextra:wpmedia-open-modal');
		} else if ( wp.media && wp.media.view.Modal && wp.media.view.Modal.prototype ) {
			var frame = wp.media.view.Modal.prototype;

			frame.on('open', function(){
				$doc.trigger('wgextra:wpmedia-open-modal');
			});
			frame.on('close', function(){
				$doc.trigger('wgextra:wpmedia-close-modal');
			});
		}
	});
}(jQuery));
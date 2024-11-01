/* global tinyMCE */
( function( $ ) {
	if ( !wp.media )
		return;

	var media = wp.media,
		shortcode_string = 'wgextra';

	wp.mce = wp.mce || {};
	wp.mce.wgextra = {
		shortcode_data: {},
		bookmark: {},
		viewIndex: -1,
		template: _.template( $(document.getElementById('tmpl-editor-wgextra')).text() ),
		getContent: function() {
			var options = this.shortcode.attrs.named;

			options.uniqID = (new Date().getTime()).toString(16);

			if ( typeof options.template_id !== 'undefined' ) {
				options = $.extend( {}, wgextraEditor.templates[options.template_id], options );
				options.template = wgextraEditor[options.template];
			}

			return this.template( options );
		},
		View: { // before WP 4.2:
			template: _.template( $(document.getElementById('tmpl-editor-wgextra')).text() ),
			initialize: function( options ) {
				this.shortcode = options.shortcode;
				wp.mce.wgextra.shortcode_data = this.shortcode;
			},
			getHtml: function() {
				var options = this.shortcode.attrs.named;

				options.uniqID = (new Date().getTime()).toString(16);

				if ( typeof options.template_id !== 'undefined' ) {
					options = $.extend( {}, wgextraEditor.templates[options.template_id], options );
				}

				return this.template( options );
			}
		},
		edit: function( data, node ) {
			var shortcode_data = wp.shortcode.next( shortcode_string, data );
			var values = shortcode_data.shortcode.attrs.named;
			wp.mce.wgextra.popupwindow( tinyMCE.activeEditor, values );
		},
		// this is called from our tinymce plugin, also can call from our "edit" function above
		// wp.mce.wgextra.popupwindow(tinyMCE.activeEditor, "bird");
		popupwindow: function( editor, values, onsubmit_callback ) {
			wp.mce.wgextra.bookmark = editor.selection.getBookmark(1);
			var $views = $(editor.dom.select('.wpview'));
			wp.mce.wgextra.viewIndex = $views.index( editor.selection.getNode() );

			values = values || {};
			if ( typeof onsubmit_callback !== 'function' ) {
				onsubmit_callback = function( data ) {
					var attrs = $.extend( values, data );
					// Insert content when the window form is submitted (this also replaces during edit, handy!)
					var args = {
						tag: shortcode_string,
						type: 'single',
						attrs: attrs
					};

					//editor.selection.setRng( wp.mce.wgextra.range );
					editor.selection.moveToBookmark( wp.mce.wgextra.bookmark );
					if ( typeof send_to_editor === 'function' )
						send_to_editor( wp.shortcode.string( args ) );
					else if ( typeof wp.media.editor.insert === 'function' )
						wp.media.editor.insert( wp.shortcode.string( args ) );
					else
						editor.insertContent( wp.shortcode.string( args ) );

					if (wp.mce.wgextra.viewIndex > -1) {
						console.log(editor.dom.select('.wpview')[wp.mce.wgextra.viewIndex]);
						setTimeout( function() {
							editor.selection.select(editor.dom.select('.wpview')[wp.mce.wgextra.viewIndex]);
							editor.nodeChanged();
							editor.focus();
						} );
					}
				};
			}

			$.WGExtraAGP( editor, values, onsubmit_callback );
		}
	};
	wp.mce.views.register( shortcode_string, wp.mce.wgextra );
}( jQuery ) );
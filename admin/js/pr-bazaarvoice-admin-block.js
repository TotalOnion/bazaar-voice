( function( blocks, editor, i18n, element, blockEditor ) {
	var el = element.createElement;
	var __ = i18n.__;
	var RichText = editor.RichText;
    var CheckboxControl = editor.CheckboxControl;
	var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'pr-bazaarvoice/bazaarvoice', {
		title: 'Bazaarvoice',
        icon: 'dashicons dashicons-text',
		category: 'layout',

        attributes: {
			bazaarvoice: {
				type: 'array',
                source: 'children',
                selector: 'p',
			},
		},

        example: {
			attributes: {
				bazaarvoice: __( '123' ),
			},
		},

		edit: function(props) {
            var content = props.attributes.bazaarvoice;
            function onChangeContent( newContent ) {
				props.setAttributes( { bazaarvoice: newContent } );
			}

			return el(
				'div',
                { className: 'bazaarvoice-block' },
                    el('p', {}, 'Bazaarvoice Id'),
                    el( RichText, useBlockProps({
                        tagName: 'p',
                        onChange: onChangeContent,
                        placeholder: 'Enter your bazaarvoice id here',
                        value: content,
                    })),
                    el('p', {}, 'Display Type')/*, 
                    el(CheckboxControl, useBlockProps({
                        id: 'test',
                        className: 'check_items',
                        key: 'ratingsummary',
                        label: 'Rating Summary',
                        name: 'checkbox[]',
                        checked: 0,
                        heading: 'Test',
                        onChange: 1,
                    })),*/   
                );
        },
		save: function(props) {
            return el( RichText.Content, useBlockProps.save( {
				tagName: 'p',
				value: props.attributes.bazaarvoice,
			}));
		},
	} );
} )( window.wp.blocks, window.wp.editor, window.wp.i18n, window.wp.element, window.wp.blockEditor );

/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
wp.blocks.registerBlockType('pr-bazaarvoice/bazaarvoice', {
	title: 'Bazaarvoice',
	icon: 'dashicons dashicons-text',
	category: 'text',

	attributes: {
		title: { type: 'string' },
		bazaarvoice: { type: 'string' },
		ratingsummary: { type: 'boolean', default: false },
		reviews: { type: 'boolean', default: false },
		review_highlights: { type: 'boolean', default: false },
 		inline_rating: { type: 'boolean', default: false },
	},

	/*
	* The edit function describes the structure of your block in the context of the editor.
	* This represents what the editor will render when the block is used.
	* https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
	*/

	/* This configures how the content and color fields will work, and sets up the necessary elements */
	edit: function(props) {

		var content = props.attributes.bazaarvoice;

		function onChangeCheckbox(e) {
			let attr_name = e.target.name,
			attr_key = attr_name.substring((attr_name.lastIndexOf('-')+1), attr_name.length);
			props.setAttributes({[attr_key]: e.target.checked});
		}

		function onChangeContent( e ) {
			props.setAttributes( { bazaarvoice: e.target.value } );
		}

		function onChangeText( e ) {
			props.setAttributes( { title: e.target.value } );
		}

		return React.createElement(
			'div',
			{ className: 'bazaarvoice-block' },
			'',
			React.createElement(
				'div',
				{ className: 'components-placeholder__label' },
				'Bazaar Voice',
				),
			React.createElement(
				'div',
				{ className: 'components-placeholder__instructions' },
				'Enter a title and bazaar voice id',
			),
			React.createElement(
			'input',
			{
				type: "text",
				value: props.attributes.title,
				onChange: onChangeText,
				name:'Title',
				placeholder: 'Title',
				class: 'bazaarvoice-input'
			}),
			React.createElement('p', {}),
			React.createElement(
			'input',
			{
				type: "text",
				value: props.attributes.bazaarvoice,
				onChange: onChangeContent,
				name:'bazaarvoice',
				placeholder: 'Bazaarvoice Id',
				class: 'bazaarvoice-input'
			}),
			React.createElement('p', {}, 'Display Type'),
			React.createElement(
				'input',{
					type: 'checkbox',
					defaultChecked: props.attributes.ratingsummary,
					label: 'Rating Summary',
					name: 'ratingsummary',
					id: 'ratingsummary',
					onChange: onChangeCheckbox,
				}
			),
			React.createElement('label', {}, 'Ratings summary'),
			React.createElement('p'),
			React.createElement(
				'input',{
					type: 'checkbox',
					defaultChecked: props.attributes.reviews,
					label: 'Reviews',
					name: 'reviews',
					id: 'reviews',
					onChange: onChangeCheckbox,
				}
			),
			React.createElement('label', {}, 'Reviews'),
			React.createElement('p'),
			React.createElement(
				'input',{
					type: 'checkbox',
					defaultChecked: props.attributes.review_highlights,
					label: 'Review highlights',
					name: 'review_highlights',
					id: 'review_highlights',
					onChange: onChangeCheckbox,
				}
			),
			React.createElement('label', {}, 'Review highlights'),
			React.createElement('p'),
			React.createElement(
				'input',{
					type: 'checkbox',
					defaultChecked: props.attributes.inline_rating,
					label: 'Inline rating',
					name: 'inline_rating',
					id: 'inline_rating',
					onChange: onChangeCheckbox,
				}
			),
			React.createElement('label', {}, 'Inline rating'),
		);
	},

	/*
	* The save function defines the way in which the different
	* attributes should be combined into the final markup,
	* which is then serialized into post_content.
	* https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
	*/

	save: function(props) {
		let adminOutput = [];

		// Rating summary
		if (props.attributes.ratingsummary == 1) {
			adminOutput.push(wp.element.createElement(
				'div',
				{'data-bv-show': 'rating_summary',
				'data-bv-product-id': props.attributes.bazaarvoice},
				'Rating Summary'
			))
		}

		// Review highlights
		if (props.attributes.review_highlights == 1) {
			adminOutput.push(wp.element.createElement(
				'div',
				{'data-bv-show': 'review_highlights',
				'data-bv-product-id': props.attributes.bazaarvoice},
				'Review Highlights'
			))
		}

		// Reviews
		if (props.attributes.reviews == 1) {
			adminOutput.push(wp.element.createElement(
				'div',
				{'data-bv-show': 'reviews',
				'data-bv-product-id': props.attributes.bazaarvoice},
				'Reviews'
			))
		}

		// Inline ratings
		if (props.attributes.inline_rating == 1) {
			adminOutput.push(wp.element.createElement(
				'div',
				{'data-bv-show': 'questions',
				'data-bv-product-id': props.attributes.bazaarvoice},
				'Questions'
			))
		}

		return wp.element.createElement(
			'section',
			{ className: '', id: props.attributes.bazaarvoice },
			wp.element.createElement(
				'div',
				{},
				wp.element.createElement(
					'h1',
					{},
					props.attributes.title
				),
				adminOutput
			)
		);
	}
});

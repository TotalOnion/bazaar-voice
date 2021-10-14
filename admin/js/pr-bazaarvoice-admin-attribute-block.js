
/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
wp.blocks.registerBlockType('bazaarvoice/bazaarvoice-block', {
    title: 'Bazaarvoice',
    description: 'Add bazaarvoice widget to page'
    icon: 'comments',
    category: 'layout',
    attributes: {
        bazaarvoice_id:  {type: 'string'},
    },

    /*
    * The edit function describes the structure of your block in the context of the editor.
    * This represents what the editor will render when the block is used.
    * https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
    */
    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateContent(e) {
            props.setAttributes({bazaarvoice_id: e.target.value})
        }

        return createElement(
            "h3",
            props.attributes.bazaarvoice_id
        );
    },

    /*
    * The save function defines the way in which the different
    * attributes should be combined into the final markup,
    * which is then serialized into post_content.
    * https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
    */
    save: function(props) {

        return wp.element.createElement(
            "h3",
            props.attributes.bazaarvoice_id
        );
    }
});
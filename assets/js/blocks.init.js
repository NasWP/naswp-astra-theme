wp.domReady(function() {

    /* ------------------------------ GENERAL ------------------------------ */

    // removes comments panel
    wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'discussion-panel' );

    // removes Inline Image option from Rich text editor
   // wp.richText.unregisterFormatType( 'core/image' );

    /* ------------------------------ PARAGRAPH ------------------------------ */  

    // adds custom styles
    wp.blocks.registerBlockStyle( 'core/paragraph',
		[
			{
				name: 'default',
				label: 'Výchozí',
				isDefault: true,
			},
			{
				name: 'block_text-highlight',
				label: 'Zvýrazněný',
			}
        ]
    );

    /* ------------------------------ QUOTE ------------------------------ */

    // removes default styles
    wp.blocks.unregisterBlockStyle( 'core/quote', 
        ['default', 'large']
    );

    /* ------------------------------ TABLE ------------------------------ */  
    
    // removes default styles
    wp.blocks.unregisterBlockStyle( 'core/table', 
        ['regular', 'stripes']
    );
    
    /* ------------------------------ IMAGE ------------------------------ */ 

    // removes default styles
    wp.blocks.unregisterBlockStyle( 'core/image', 
        ['default', 'rounded']
    );
    
    /* ------------------------------ SEPARATOR ------------------------------ */ 

    // removes default styles
    wp.blocks.unregisterBlockStyle( 'core/separator', 
        ['default', 'wide', 'dots']
    );

    /* ------------------------------ EMBEDS ------------------------------ */

    // allows only selected embed types
    const allowedEmbedVariants = [
        'twitter',
         'vimeo',
         'youtube'
    ];

    wp.blocks.getBlockVariations( 'core/embed' ).forEach( variant => {
        if ( !allowedEmbedVariants.includes( variant.name ) ) {
            wp.blocks.unregisterBlockVariation( 'core/embed', variant.name );
        }
    } );

});
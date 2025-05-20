import { registerBlockType } from '@wordpress/blocks';
import StylisedLinkMetaSync from './metadata-sync';

import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType(metadata.name, {
    /**
     * @see ./edit.js
     */
    edit: Edit,

    /**
     * @see ./save.js
     */
    save,
});

wp.domReady(() => {
    const el = wp.element.createElement(StylisedLinkMetaSync);
    wp.element.render(el, document.createElement('div', { id: 'dmg-stylised-link-meta-sync' }))
});
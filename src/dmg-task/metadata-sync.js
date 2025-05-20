import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

const StylisedLinkMetaSync = () => {
    // Get the number of blocks of type 'dmg-task/stylised-link' in the current post
    // and the current post ID
    const { blocksCount, postId } = useSelect((select) => {
        const blocks = select('core/block-editor').getBlocks();
        const count = blocks.filter((block) => block.name === 'dmg-task/stylised-link').length;
        const id = select('core/editor').getCurrentPostId();
        return { blocksCount: count, postId: id };
    });

    const { editPost } = useDispatch('core/editor');

    // Update the post meta when the number of blocks changes
    useEffect(() => {
        if (!postId) return;
        if (blocksCount > 0) {
            // If there are any blocks, set the meta field to true
            editPost({ meta: { _dmg_has_stylised_link: true } });
        } else {
            // If there are no blocks, set the meta field to null
            // to remove the meta field
            editPost({ meta: { _dmg_has_stylised_link: null } });
        }
    }, [blocksCount, postId]);

    return null;
};

export default StylisedLinkMetaSync;
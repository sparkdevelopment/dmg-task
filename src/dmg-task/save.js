import { __ } from '@wordpress/i18n';

export default function save({ attributes }) {
    return (
        <p className='dmg-read-more'>
            <a className='dmg-read-more__link' href={attributes.targetPostLink}>
                {attributes.targetPostTitle ? __('Read More: ', 'dmg-task') + attributes.targetPostTitle : __('Select a post', 'dmg-task')}
            </a>
        </p>
    );
}

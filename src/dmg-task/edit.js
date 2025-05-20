import { __ } from '@wordpress/i18n';
import { useState } from 'react';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';

import './editor.scss';

function getSearchParams(searchString = '', page = 1) {
    // Default search parameters
    var searchParams = {
        per_page: 5,
        page: page,
        post_status: 'publish'
    };

    // If searchString is a number, treat it as a post ID
    // If searchString is a string, treat it as a search term
    // If searchString is empty, return all posts
    if (searchString) {
        if (!isNaN(searchString)) {
            searchParams.include = searchString;
            searchParams.orderby = 'date';
            searchParams.order = 'desc';
        } else {
            searchParams.search = searchString;
            searchParams.search_columns = ['post_title'];
            searchParams.orderby = 'title';
            searchParams.order = 'asc';
        }
    }

    return searchParams;
}

export default function Edit({ attributes, setAttributes }) {
    const [searchString, setSearchString] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const searchParams = getSearchParams(searchString, currentPage);

    const blockProps = useBlockProps({
        className: 'dmg-read-more',
        'data-id': attributes.targetPostId,
        'data-title': attributes.targetPostTitle,
        'data-link': attributes.targetPostLink,
    });

    const posts = useEntityRecords('postType', 'post', searchParams);
    if (posts && posts.error) {
        console.error('Error fetching posts:', posts.error);
    }

    const records = posts && posts.records ? posts.records : [];

    const postList = (
        records.length > 0
            ? records.map((post) => (
                <li key={post.id}
                    className={`dmg-post-list__item ${post.id === attributes.targetPostId ? 'dmg-post-list__item--selected' : ''}`}
                    data-id={post.id}
                    onClick={() => {
                        const selectedPost = {
                            id: post.id,
                            title: post.title.rendered,
                            link: post.link,
                        };

                        setAttributes({ targetPostId: selectedPost.id, targetPostTitle: selectedPost.title, targetPostLink: selectedPost.link });
                    }}
                >
                    <span>{post.title.rendered}</span>
                    <span className="dmg-select-attribute">Post ID: {post.id}</span>
                </li>
            ))
            : __('No posts found', 'dmg-task')
    );

    const totalPages = posts.totalPages || 1;

    const paginationButton = ({ page, disabled, label }) => (
        <button
            className={`dmg-pagination__button ${currentPage === page ? 'dmg-pagination__button--active' : ''} ${disabled ? 'dmg-pagination__button--disabled' : ''}`}
            onClick={() => setCurrentPage(page)}
            disabled={disabled}
        >
            {label || page}
        </button>
    );

    const paginationControls = (
        <div className="dmg-pagination">
            {/* Previous page */}
            {currentPage > 1 && paginationButton({ page: currentPage - 1, label: __('<<', 'dmg-task') })}

            {/* First page */}
            {paginationButton({ page: 1, disabled: currentPage === 1 })}

            {/* Ellipsis */}
            {currentPage > 3 && totalPages > 4 && <span className="dmg-pagination__ellipsis">…</span>}

            {/* Middle pages */}
            {currentPage > 2 && currentPage < totalPages && paginationButton({ page: currentPage - 1 })}
            {currentPage === 1 && totalPages > 2 && paginationButton({ page: currentPage + 1 })}

            {/* Current page */}
            {currentPage !== 1 && currentPage !== totalPages && paginationButton({ page: currentPage, disabled: true })}

            {/* More middle pages */}
            {currentPage === totalPages && currentPage > 2 && paginationButton({ page: currentPage - 1 })}
            {currentPage < totalPages - 1 && currentPage > 1 && paginationButton({ page: currentPage + 1 })}

            {/* Ellipsis */}
            {currentPage < totalPages - 2 && totalPages > 4 && <span className="dmg-pagination__ellipsis">…</span>}

            {/* Last page */}
            {totalPages > 1 && paginationButton({ page: totalPages, disabled: currentPage === totalPages })}

            {/* Next page */}
            {currentPage < totalPages && paginationButton({ page: currentPage + 1, label: __('>>', 'dmg-task') })}
        </div>
    );

    const inspectorControls = (
        <InspectorControls>
            <PanelBody title={__('Link Settings', 'dmg-task')}>
                <p>{__('Select a recent post or search for a post by title or ID:', 'dmg-task')}</p>
                <TextControl
                    label={__('Search posts', 'dmg-task')}
                    __next40pxDefaultSize="true"
                    __nextHasNoMarginBottom="true"
                    value={searchString}
                    onChange={(value) => {
                        setSearchString(value);
                        setCurrentPage(1);
                    }}
                    placeholder={__('Search posts...', 'dmg-task')}
                />
                <div className="dmg-post-list__container">
                    {!posts.hasResolved && <p>{__('Loading posts...', 'dmg-task')}</p>}
                    {posts && posts.hasResolved && <ul className="dmg-post-list">{postList}</ul>}
                </div>
                {paginationControls}
            </PanelBody>
        </InspectorControls >
    );

    return (
        <>
            {inspectorControls}
            <p {...blockProps}>
                <a className='dmg-read-more__link' href={attributes.targetPostLink}>
                    {attributes.targetPostTitle ? __('Read More: ', 'dmg-task') + attributes.targetPostTitle : __('Select a post', 'dmg-task')}
                </a>
            </p>
        </>
    );
}

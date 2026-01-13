(function (blocks, element, blockEditor, components, serverSideRender, data) {
    const { registerBlockType } = blocks;
    const { createElement, Fragment, useState, useEffect } = element;
    const { InspectorControls, useBlockProps } = blockEditor;
    const {
        PanelBody,
        SelectControl,
        RangeControl,
        ToggleControl,
        Placeholder,
        Spinner
    } = components;
    const ServerSideRender = serverSideRender;
    const { useSelect } = data;

    registerBlockType('ihowz/news-headlines', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                category,
                postCount,
                columns,
                rows,
                showFeaturedImage,
                showAuthor,
                showDate,
                showExcerpt,
                containerWidth,
                backgroundOpacity
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-news-headlines-editor'
            });

            // Fetch categories
            const categories = useSelect(function (select) {
                return select('core').getEntityRecords('taxonomy', 'category', {
                    per_page: -1,
                    orderby: 'name',
                    order: 'asc'
                });
            }, []);

            // Build category options for select
            const categoryOptions = [{ label: 'All Categories', value: '' }];
            if (categories) {
                categories.forEach(function (cat) {
                    categoryOptions.push({
                        label: cat.name,
                        value: cat.slug
                    });
                });
            }

            // Background opacity options
            const backgroundOptions = [
                { label: '0% (Transparent)', value: 0 },
                { label: '25%', value: 25 },
                { label: '50%', value: 50 },
                { label: '75%', value: 75 },
                { label: '100%', value: 100 }
            ];

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    // Content Settings Panel
                    createElement(
                        PanelBody,
                        { title: 'Content Settings', initialOpen: true },
                        createElement(SelectControl, {
                            label: 'Category',
                            value: category,
                            options: categoryOptions,
                            onChange: function (value) {
                                setAttributes({ category: value });
                            },
                            help: categories ? 'Select a category to filter posts' : 'Loading categories...'
                        }),
                        createElement(RangeControl, {
                            label: 'Number of Posts',
                            value: postCount,
                            onChange: function (value) {
                                setAttributes({ postCount: value });
                            },
                            min: 1,
                            max: 24
                        })
                    ),
                    // Layout Settings Panel
                    createElement(
                        PanelBody,
                        { title: 'Layout Settings', initialOpen: true },
                        createElement(RangeControl, {
                            label: 'Columns',
                            value: columns,
                            onChange: function (value) {
                                setAttributes({ columns: value });
                            },
                            min: 1,
                            max: 6
                        }),
                        createElement(RangeControl, {
                            label: 'Rows',
                            value: rows,
                            onChange: function (value) {
                                setAttributes({ rows: value });
                            },
                            min: 1,
                            max: 6,
                            help: 'Maximum rows to display (post count may limit this)'
                        })
                    ),
                    // Display Options Panel
                    createElement(
                        PanelBody,
                        { title: 'Display Options', initialOpen: true },
                        createElement(ToggleControl, {
                            label: 'Show Featured Image',
                            checked: showFeaturedImage,
                            onChange: function (value) {
                                setAttributes({ showFeaturedImage: value });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: 'Show Author',
                            checked: showAuthor,
                            onChange: function (value) {
                                setAttributes({ showAuthor: value });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: 'Show Date',
                            checked: showDate,
                            onChange: function (value) {
                                setAttributes({ showDate: value });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: 'Show Excerpt',
                            checked: showExcerpt,
                            onChange: function (value) {
                                setAttributes({ showExcerpt: value });
                            }
                        })
                    ),
                    // Container Settings Panel
                    createElement(
                        PanelBody,
                        { title: 'Container Settings', initialOpen: false },
                        createElement(SelectControl, {
                            label: 'Container Width',
                            value: containerWidth,
                            options: [
                                { label: 'Content Width', value: 'content' },
                                { label: 'Full Page Width', value: 'full' }
                            ],
                            onChange: function (value) {
                                setAttributes({ containerWidth: value });
                            }
                        }),
                        createElement(SelectControl, {
                            label: 'Background Color (Primary Green)',
                            value: backgroundOpacity,
                            options: backgroundOptions,
                            onChange: function (value) {
                                setAttributes({ backgroundOpacity: parseInt(value, 10) });
                            },
                            help: containerWidth === 'content' && backgroundOpacity > 0
                                ? 'Rounded corners will be applied'
                                : ''
                        })
                    )
                ),
                createElement(
                    'div',
                    blockProps,
                    createElement(ServerSideRender, {
                        block: 'ihowz/news-headlines',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'megaphone',
                                    label: 'News Headlines'
                                },
                                createElement(
                                    'p',
                                    null,
                                    'No posts found. Select a category with posts or check your settings.'
                                )
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'megaphone',
                                    label: 'News Headlines'
                                },
                                createElement(Spinner)
                            );
                        }
                    })
                )
            );
        },

        save: function () {
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.serverSideRender,
    window.wp.data
);

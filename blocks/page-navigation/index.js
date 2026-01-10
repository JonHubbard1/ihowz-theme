(function (blocks, element, blockEditor, components, serverSideRender, data) {
    const { registerBlockType } = blocks;
    const { createElement, Fragment } = element;
    const { InspectorControls, useBlockProps } = blockEditor;
    const { PanelBody, SelectControl, ToggleControl, TextControl, Placeholder, Spinner } = components;
    const ServerSideRender = serverSideRender;
    const { useSelect } = data;

    registerBlockType('ihowz/page-navigation', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { depth, showOnlyChildren, parentPage, excludeIds, title, showTitle } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-page-navigation-editor'
            });

            // Fetch pages for the parent page dropdown
            const pages = useSelect(function (select) {
                return select('core').getEntityRecords('postType', 'page', {
                    per_page: -1,
                    orderby: 'title',
                    order: 'asc',
                    status: 'publish'
                });
            }, []);

            // Build page options for dropdown
            const pageOptions = [{ label: 'All Pages (no parent)', value: 0 }];
            if (pages) {
                pages.forEach(function (page) {
                    pageOptions.push({
                        label: page.title.rendered,
                        value: page.id
                    });
                });
            }

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    createElement(
                        PanelBody,
                        { title: 'Navigation Settings', initialOpen: true },
                        createElement(SelectControl, {
                            label: 'Depth (levels to show)',
                            value: depth,
                            options: [
                                { label: '1 level', value: 1 },
                                { label: '2 levels', value: 2 },
                                { label: '3 levels', value: 3 },
                                { label: 'All levels', value: 0 }
                            ],
                            onChange: function (value) {
                                setAttributes({ depth: parseInt(value) });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: 'Show only children of current page',
                            help: showOnlyChildren
                                ? 'Shows child pages of the current page being viewed'
                                : 'Shows pages based on parent selection below',
                            checked: showOnlyChildren,
                            onChange: function (value) {
                                setAttributes({ showOnlyChildren: value });
                            }
                        }),
                        !showOnlyChildren && createElement(SelectControl, {
                            label: 'Parent Page',
                            help: 'Show only children of this page',
                            value: parentPage,
                            options: pageOptions,
                            onChange: function (value) {
                                setAttributes({ parentPage: parseInt(value) });
                            }
                        }),
                        createElement(TextControl, {
                            label: 'Exclude Page IDs',
                            help: 'Comma-separated list of page IDs to exclude',
                            value: excludeIds,
                            onChange: function (value) {
                                setAttributes({ excludeIds: value });
                            }
                        })
                    ),
                    createElement(
                        PanelBody,
                        { title: 'Title Settings', initialOpen: false },
                        createElement(ToggleControl, {
                            label: 'Show title',
                            checked: showTitle,
                            onChange: function (value) {
                                setAttributes({ showTitle: value });
                            }
                        }),
                        showTitle && createElement(TextControl, {
                            label: 'Navigation Title',
                            value: title,
                            onChange: function (value) {
                                setAttributes({ title: value });
                            }
                        })
                    )
                ),
                createElement(
                    'div',
                    blockProps,
                    createElement(ServerSideRender, {
                        block: 'ihowz/page-navigation',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'list-view',
                                    label: 'Page Navigation'
                                },
                                'No pages found matching your criteria.'
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'list-view',
                                    label: 'Page Navigation'
                                },
                                createElement(Spinner)
                            );
                        }
                    })
                )
            );
        },

        save: function () {
            // Server-side rendered
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

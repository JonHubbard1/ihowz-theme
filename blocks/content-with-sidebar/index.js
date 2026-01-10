/**
 * Content with Sidebar Block
 *
 * @package iHowz Theme
 */

(function (blocks, element, blockEditor, components, i18n) {
    const { registerBlockType } = blocks;
    const { createElement: el, Fragment } = element;
    const { InspectorControls, InnerBlocks, useBlockProps } = blockEditor;
    const { PanelBody, SelectControl, RangeControl, ToggleControl } = components;
    const { __ } = i18n;

    // Available widget areas (will be populated from PHP)
    const widgetAreas = [
        { label: 'Page Sidebar', value: 'page-sidebar' },
        { label: 'Sidebar', value: 'sidebar-1' },
        { label: 'Article - Sidebar Top', value: 'article-sidebar-top' },
        { label: 'Footer 1', value: 'footer-1' },
        { label: 'Footer 2', value: 'footer-2' },
        { label: 'Footer 3', value: 'footer-3' },
    ];

    // Template for inner blocks when using blocks mode
    const TEMPLATE_BLOCKS = [
        ['core/group', { className: 'content-area', layout: { type: 'constrained' } }, [
            ['core/paragraph', { placeholder: 'Add your content here...' }]
        ]],
        ['core/group', { className: 'sidebar-area', layout: { type: 'constrained' } }, [
            ['core/paragraph', { placeholder: 'Add sidebar content here...' }]
        ]]
    ];

    // Template for when using widgets (content only)
    const TEMPLATE_WIDGETS = [
        ['core/group', { className: 'content-area', layout: { type: 'constrained' } }, [
            ['core/paragraph', { placeholder: 'Add your content here...' }]
        ]]
    ];

    registerBlockType('ihowz/content-with-sidebar', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                sidebarSource,
                widgetArea,
                contentWidth,
                gapSize,
                sidebarPosition,
                verticalAlignment
            } = attributes;

            const sidebarWidth = 100 - contentWidth;

            // Build grid style
            const gridColumns = sidebarPosition === 'right'
                ? `${contentWidth}fr ${sidebarWidth}fr`
                : `${sidebarWidth}fr ${contentWidth}fr`;

            let alignItems = 'flex-start';
            if (verticalAlignment === 'center') alignItems = 'center';
            if (verticalAlignment === 'stretch') alignItems = 'stretch';

            const blockProps = useBlockProps({
                className: `ihowz-content-with-sidebar sidebar-${sidebarPosition} source-${sidebarSource}`,
                style: {
                    display: 'grid',
                    gridTemplateColumns: sidebarSource === 'blocks' ? gridColumns : '1fr',
                    gap: `${gapSize}px`,
                    alignItems: alignItems
                }
            });

            // Choose template based on sidebar source
            const template = sidebarSource === 'blocks' ? TEMPLATE_BLOCKS : TEMPLATE_WIDGETS;

            return el(
                Fragment,
                null,
                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: __('Sidebar Settings', 'ihowz-theme'), initialOpen: true },
                        el(SelectControl, {
                            label: __('Sidebar Source', 'ihowz-theme'),
                            value: sidebarSource,
                            options: [
                                { label: __('Blocks (edit per page)', 'ihowz-theme'), value: 'blocks' },
                                { label: __('Widget Area (global)', 'ihowz-theme'), value: 'widgets' }
                            ],
                            onChange: (value) => setAttributes({ sidebarSource: value }),
                            help: sidebarSource === 'blocks'
                                ? __('Sidebar content is editable directly in the block.', 'ihowz-theme')
                                : __('Sidebar uses a widget area managed in Appearance > Widgets.', 'ihowz-theme')
                        }),
                        sidebarSource === 'widgets' && el(SelectControl, {
                            label: __('Widget Area', 'ihowz-theme'),
                            value: widgetArea,
                            options: widgetAreas,
                            onChange: (value) => setAttributes({ widgetArea: value })
                        }),
                        el(SelectControl, {
                            label: __('Sidebar Position', 'ihowz-theme'),
                            value: sidebarPosition,
                            options: [
                                { label: __('Right', 'ihowz-theme'), value: 'right' },
                                { label: __('Left', 'ihowz-theme'), value: 'left' }
                            ],
                            onChange: (value) => setAttributes({ sidebarPosition: value })
                        })
                    ),
                    el(
                        PanelBody,
                        { title: __('Layout Settings', 'ihowz-theme'), initialOpen: false },
                        el(RangeControl, {
                            label: __('Content Width (%)', 'ihowz-theme'),
                            value: contentWidth,
                            onChange: (value) => setAttributes({ contentWidth: value }),
                            min: 50,
                            max: 80,
                            step: 5
                        }),
                        el('p', { className: 'components-base-control__help' },
                            __('Sidebar width: ', 'ihowz-theme') + sidebarWidth + '%'
                        ),
                        el(RangeControl, {
                            label: __('Gap Size (px)', 'ihowz-theme'),
                            value: gapSize,
                            onChange: (value) => setAttributes({ gapSize: value }),
                            min: 20,
                            max: 80,
                            step: 10
                        }),
                        el(SelectControl, {
                            label: __('Vertical Alignment', 'ihowz-theme'),
                            value: verticalAlignment,
                            options: [
                                { label: __('Top', 'ihowz-theme'), value: 'top' },
                                { label: __('Center', 'ihowz-theme'), value: 'center' },
                                { label: __('Stretch', 'ihowz-theme'), value: 'stretch' }
                            ],
                            onChange: (value) => setAttributes({ verticalAlignment: value })
                        })
                    )
                ),
                // Block Preview
                el(
                    'div',
                    blockProps,
                    sidebarSource === 'blocks'
                        ? el(InnerBlocks, {
                            template: template,
                            templateLock: false
                        })
                        : el(
                            Fragment,
                            null,
                            sidebarPosition === 'left' && el(
                                'aside',
                                { className: 'sidebar-area widget-sidebar-preview' },
                                el('div', { className: 'widget-placeholder' },
                                    el('span', { className: 'dashicons dashicons-admin-generic' }),
                                    el('p', null, __('Widget Area: ', 'ihowz-theme') + widgetArea),
                                    el('small', null, __('Widgets will display on frontend', 'ihowz-theme'))
                                )
                            ),
                            el(
                                'div',
                                {
                                    className: 'content-area',
                                    style: { flex: contentWidth }
                                },
                                el(InnerBlocks, {
                                    template: TEMPLATE_WIDGETS,
                                    templateLock: false
                                })
                            ),
                            sidebarPosition === 'right' && el(
                                'aside',
                                { className: 'sidebar-area widget-sidebar-preview' },
                                el('div', { className: 'widget-placeholder' },
                                    el('span', { className: 'dashicons dashicons-admin-generic' }),
                                    el('p', null, __('Widget Area: ', 'ihowz-theme') + widgetArea),
                                    el('small', null, __('Widgets will display on frontend', 'ihowz-theme'))
                                )
                            )
                        )
                )
            );
        },

        save: function () {
            // Dynamic block with InnerBlocks
            return el(InnerBlocks.Content);
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n
);

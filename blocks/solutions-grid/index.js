(function (blocks, element, blockEditor, components, serverSideRender) {
    const { registerBlockType } = blocks;
    const { createElement, Fragment } = element;
    const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = blockEditor;
    const {
        PanelBody,
        TextControl,
        ToggleControl,
        RangeControl,
        Button,
        Placeholder,
        Spinner,
        Card,
        CardBody,
        CardHeader,
        __experimentalNumberControl: NumberControl
    } = components;
    const ServerSideRender = serverSideRender;

    registerBlockType('ihowz/solutions-grid', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                heading,
                showTopButton,
                topButtonText,
                topButtonUrl,
                rows,
                gap,
                cardBorderRadius,
                cardMinHeight
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-solutions-grid-editor'
            });

            // Helper: Update a specific row
            function updateRow(rowIndex, newRowData) {
                var newRows = rows.slice();
                newRows[rowIndex] = Object.assign({}, newRows[rowIndex], newRowData);
                setAttributes({ rows: newRows });
            }

            // Helper: Update a specific item in a row
            function updateItem(rowIndex, itemIndex, newItemData) {
                var newRows = rows.slice();
                var newItems = newRows[rowIndex].items.slice();
                newItems[itemIndex] = Object.assign({}, newItems[itemIndex], newItemData);
                newRows[rowIndex] = Object.assign({}, newRows[rowIndex], { items: newItems });
                setAttributes({ rows: newRows });
            }

            // Helper: Add a new row
            function addRow() {
                if (rows.length >= 4) return;
                var newRows = rows.slice();
                newRows.push({ items: [] });
                setAttributes({ rows: newRows });
            }

            // Helper: Remove a row
            function removeRow(rowIndex) {
                var newRows = rows.slice();
                newRows.splice(rowIndex, 1);
                setAttributes({ rows: newRows });
            }

            // Helper: Add an item to a row
            function addItem(rowIndex) {
                if (rows[rowIndex].items.length >= 5) return;
                var newRows = rows.slice();
                var newItems = newRows[rowIndex].items.slice();
                newItems.push({
                    imageUrl: '',
                    title: '',
                    buttonText: '',
                    buttonUrl: '',
                    width: 0
                });
                newRows[rowIndex] = Object.assign({}, newRows[rowIndex], { items: newItems });
                setAttributes({ rows: newRows });
            }

            // Helper: Remove an item from a row
            function removeItem(rowIndex, itemIndex) {
                var newRows = rows.slice();
                var newItems = newRows[rowIndex].items.slice();
                newItems.splice(itemIndex, 1);
                newRows[rowIndex] = Object.assign({}, newRows[rowIndex], { items: newItems });
                setAttributes({ rows: newRows });
            }

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    // Header Settings
                    createElement(
                        PanelBody,
                        { title: 'Header Settings', initialOpen: true },
                        createElement(TextControl, {
                            label: 'Eyebrow Text',
                            value: eyebrowText,
                            onChange: function (value) {
                                setAttributes({ eyebrowText: value });
                            }
                        }),
                        createElement(TextControl, {
                            label: 'Heading',
                            value: heading,
                            onChange: function (value) {
                                setAttributes({ heading: value });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: 'Show Top Button',
                            checked: showTopButton,
                            onChange: function (value) {
                                setAttributes({ showTopButton: value });
                            }
                        }),
                        showTopButton && createElement(TextControl, {
                            label: 'Button Text',
                            value: topButtonText,
                            onChange: function (value) {
                                setAttributes({ topButtonText: value });
                            }
                        }),
                        showTopButton && createElement(TextControl, {
                            label: 'Button URL',
                            value: topButtonUrl,
                            onChange: function (value) {
                                setAttributes({ topButtonUrl: value });
                            }
                        })
                    ),
                    // Grid Settings
                    createElement(
                        PanelBody,
                        { title: 'Grid Settings', initialOpen: false },
                        createElement(RangeControl, {
                            label: 'Gap Between Items (px)',
                            value: gap,
                            onChange: function (value) {
                                setAttributes({ gap: value });
                            },
                            min: 0,
                            max: 60
                        }),
                        createElement(RangeControl, {
                            label: 'Card Border Radius (px)',
                            value: cardBorderRadius,
                            onChange: function (value) {
                                setAttributes({ cardBorderRadius: value });
                            },
                            min: 0,
                            max: 40
                        }),
                        createElement(RangeControl, {
                            label: 'Card Min Height (px)',
                            value: cardMinHeight,
                            onChange: function (value) {
                                setAttributes({ cardMinHeight: value });
                            },
                            min: 150,
                            max: 500
                        })
                    ),
                    // Rows Management
                    createElement(
                        PanelBody,
                        { title: 'Rows (' + rows.length + '/4)', initialOpen: true },
                        rows.map(function (row, rowIndex) {
                            return createElement(
                                Card,
                                { key: rowIndex, style: { marginBottom: '16px' } },
                                createElement(
                                    CardHeader,
                                    null,
                                    createElement(
                                        'div',
                                        { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%' } },
                                        createElement('strong', null, 'Row ' + (rowIndex + 1) + ' (' + row.items.length + ' items)'),
                                        createElement(
                                            Button,
                                            {
                                                isDestructive: true,
                                                isSmall: true,
                                                onClick: function () { removeRow(rowIndex); }
                                            },
                                            'Remove Row'
                                        )
                                    )
                                ),
                                createElement(
                                    CardBody,
                                    null,
                                    row.items.map(function (item, itemIndex) {
                                        return createElement(
                                            'div',
                                            {
                                                key: itemIndex,
                                                style: {
                                                    padding: '12px',
                                                    marginBottom: '12px',
                                                    background: '#f0f0f0',
                                                    borderRadius: '4px'
                                                }
                                            },
                                            createElement(
                                                'div',
                                                { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '8px' } },
                                                createElement('strong', null, 'Item ' + (itemIndex + 1)),
                                                createElement(
                                                    Button,
                                                    {
                                                        isDestructive: true,
                                                        isSmall: true,
                                                        onClick: function () { removeItem(rowIndex, itemIndex); }
                                                    },
                                                    'Remove'
                                                )
                                            ),
                                            createElement(
                                                'div',
                                                { style: { marginBottom: '12px' } },
                                                createElement('label', { style: { display: 'block', marginBottom: '4px', fontWeight: '500' } }, 'Image'),
                                                item.imageUrl && createElement('img', {
                                                    src: item.imageUrl,
                                                    style: { maxWidth: '100%', height: 'auto', marginBottom: '8px', borderRadius: '4px' }
                                                }),
                                                createElement(
                                                    MediaUploadCheck,
                                                    null,
                                                    createElement(MediaUpload, {
                                                        onSelect: function (media) {
                                                            var imageUrl = media.url;
                                                            if (media.sizes && media.sizes.large) {
                                                                imageUrl = media.sizes.large.url;
                                                            } else if (media.sizes && media.sizes.full) {
                                                                imageUrl = media.sizes.full.url;
                                                            }
                                                            updateItem(rowIndex, itemIndex, { imageUrl: imageUrl });
                                                        },
                                                        allowedTypes: ['image'],
                                                        render: function (obj) {
                                                            return createElement(
                                                                Button,
                                                                {
                                                                    onClick: obj.open,
                                                                    variant: 'secondary',
                                                                    style: { marginRight: '8px' }
                                                                },
                                                                item.imageUrl ? 'Replace Image' : 'Select Image'
                                                            );
                                                        }
                                                    })
                                                ),
                                                item.imageUrl && createElement(
                                                    Button,
                                                    {
                                                        onClick: function () {
                                                            updateItem(rowIndex, itemIndex, { imageUrl: '' });
                                                        },
                                                        variant: 'tertiary',
                                                        isDestructive: true
                                                    },
                                                    'Remove'
                                                ),
                                                createElement(TextControl, {
                                                    label: 'Or paste URL',
                                                    value: item.imageUrl,
                                                    onChange: function (value) {
                                                        updateItem(rowIndex, itemIndex, { imageUrl: value });
                                                    },
                                                    style: { marginTop: '8px' }
                                                })
                                            ),
                                            createElement(TextControl, {
                                                label: 'Title',
                                                value: item.title,
                                                onChange: function (value) {
                                                    updateItem(rowIndex, itemIndex, { title: value });
                                                }
                                            }),
                                            createElement(TextControl, {
                                                label: 'Button Text',
                                                value: item.buttonText,
                                                onChange: function (value) {
                                                    updateItem(rowIndex, itemIndex, { buttonText: value });
                                                }
                                            }),
                                            createElement(TextControl, {
                                                label: 'Button URL',
                                                value: item.buttonUrl,
                                                onChange: function (value) {
                                                    updateItem(rowIndex, itemIndex, { buttonUrl: value });
                                                }
                                            }),
                                            createElement(RangeControl, {
                                                label: 'Width % (0 = equal)',
                                                value: item.width || 0,
                                                onChange: function (value) {
                                                    updateItem(rowIndex, itemIndex, { width: value });
                                                },
                                                min: 0,
                                                max: 100
                                            })
                                        );
                                    }),
                                    row.items.length < 5 && createElement(
                                        Button,
                                        {
                                            variant: 'secondary',
                                            onClick: function () { addItem(rowIndex); }
                                        },
                                        'Add Item to Row'
                                    )
                                )
                            );
                        }),
                        rows.length < 4 && createElement(
                            Button,
                            {
                                variant: 'primary',
                                onClick: addRow,
                                style: { marginTop: '8px' }
                            },
                            'Add Row'
                        )
                    )
                ),
                createElement(
                    'div',
                    blockProps,
                    createElement(ServerSideRender, {
                        block: 'ihowz/solutions-grid',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'grid-view',
                                    label: 'Solutions Grid'
                                },
                                'Add rows and items in the sidebar to build your grid.'
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'grid-view',
                                    label: 'Solutions Grid'
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
    window.wp.serverSideRender
);

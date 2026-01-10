(function (blocks, element, blockEditor, components, serverSideRender) {
    const { registerBlockType } = blocks;
    const { createElement, Fragment } = element;
    const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = blockEditor;
    const {
        PanelBody,
        TextControl,
        TextareaControl,
        ToggleControl,
        RangeControl,
        SelectControl,
        Button,
        ColorPicker,
        Placeholder,
        Spinner
    } = components;
    const ServerSideRender = serverSideRender;

    registerBlockType('ihowz/hero', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                backgroundType,
                backgroundImage,
                backgroundImageId,
                backgroundVideo,
                overlayOpacity,
                overlayColor,
                heading,
                subheading,
                primaryButtonText,
                primaryButtonUrl,
                secondaryButtonText,
                secondaryButtonUrl,
                showLeftCard,
                leftCardImage,
                leftCardImageId,
                leftCardTitle,
                leftCardText,
                showRightCard,
                rightCardImage,
                rightCardImageId,
                rightCardTitle,
                rightCardText,
                minHeight
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-hero-editor'
            });

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    // Background Panel
                    createElement(
                        PanelBody,
                        { title: 'Background', initialOpen: true },
                        createElement(SelectControl, {
                            label: 'Background Type',
                            value: backgroundType || 'image',
                            options: [
                                { label: 'Image', value: 'image' },
                                { label: 'Video', value: 'video' }
                            ],
                            onChange: function (value) {
                                setAttributes({ backgroundType: value });
                            }
                        }),
                        (backgroundType || 'image') === 'image' && createElement(TextControl, {
                            label: 'Image URL',
                            value: backgroundImage,
                            onChange: function (value) {
                                setAttributes({ backgroundImage: value });
                            }
                        }),
                        backgroundType === 'video' && createElement(TextControl, {
                            label: 'Video URL (MP4)',
                            help: 'Paste the full URL to your MP4 video file',
                            value: backgroundVideo,
                            onChange: function (value) {
                                setAttributes({ backgroundVideo: value });
                            }
                        }),
                        createElement(RangeControl, {
                            label: 'Overlay Opacity',
                            value: overlayOpacity,
                            onChange: function (value) {
                                setAttributes({ overlayOpacity: value });
                            },
                            min: 0,
                            max: 100
                        }),
                        createElement(
                            'div',
                            { style: { marginTop: '16px' } },
                            createElement('label', { style: { display: 'block', marginBottom: '8px' } }, 'Overlay Color'),
                            createElement(ColorPicker, {
                                color: overlayColor,
                                onChangeComplete: function (value) {
                                    setAttributes({ overlayColor: value.hex });
                                }
                            })
                        ),
                        createElement(RangeControl, {
                            label: 'Minimum Height (px)',
                            value: minHeight,
                            onChange: function (value) {
                                setAttributes({ minHeight: value });
                            },
                            min: 300,
                            max: 1000
                        })
                    ),
                    // Content Panel
                    createElement(
                        PanelBody,
                        { title: 'Content', initialOpen: false },
                        createElement(TextareaControl, {
                            label: 'Heading',
                            value: heading,
                            onChange: function (value) {
                                setAttributes({ heading: value });
                            }
                        }),
                        createElement(TextareaControl, {
                            label: 'Subheading',
                            value: subheading,
                            onChange: function (value) {
                                setAttributes({ subheading: value });
                            }
                        })
                    ),
                    // Buttons Panel
                    createElement(
                        PanelBody,
                        { title: 'Buttons', initialOpen: false },
                        createElement(TextControl, {
                            label: 'Primary Button Text',
                            value: primaryButtonText,
                            onChange: function (value) {
                                setAttributes({ primaryButtonText: value });
                            }
                        }),
                        createElement(TextControl, {
                            label: 'Primary Button URL',
                            value: primaryButtonUrl,
                            onChange: function (value) {
                                setAttributes({ primaryButtonUrl: value });
                            }
                        }),
                        createElement(TextControl, {
                            label: 'Secondary Button Text',
                            value: secondaryButtonText,
                            onChange: function (value) {
                                setAttributes({ secondaryButtonText: value });
                            }
                        }),
                        createElement(TextControl, {
                            label: 'Secondary Button URL',
                            value: secondaryButtonUrl,
                            onChange: function (value) {
                                setAttributes({ secondaryButtonUrl: value });
                            }
                        })
                    ),
                    // Left Card Panel
                    createElement(
                        PanelBody,
                        { title: 'Left Info Card', initialOpen: false },
                        createElement(ToggleControl, {
                            label: 'Show Left Card',
                            checked: showLeftCard,
                            onChange: function (value) {
                                setAttributes({ showLeftCard: value });
                            }
                        }),
                        showLeftCard && createElement(
                            Fragment,
                            null,
                            createElement(
                                MediaUploadCheck,
                                null,
                                createElement(MediaUpload, {
                                    onSelect: function (media) {
                                        setAttributes({
                                            leftCardImage: media.url,
                                            leftCardImageId: media.id
                                        });
                                    },
                                    allowedTypes: ['image'],
                                    value: leftCardImageId,
                                    render: function (obj) {
                                        return createElement(
                                            'div',
                                            { style: { marginBottom: '16px' } },
                                            leftCardImage
                                                ? createElement(
                                                    'div',
                                                    null,
                                                    createElement('img', {
                                                        src: leftCardImage,
                                                        style: { maxWidth: '100%', marginBottom: '10px' }
                                                    }),
                                                    createElement(
                                                        Button,
                                                        { onClick: obj.open, variant: 'secondary', style: { marginRight: '8px' } },
                                                        'Replace'
                                                    ),
                                                    createElement(
                                                        Button,
                                                        {
                                                            onClick: function () {
                                                                setAttributes({ leftCardImage: '', leftCardImageId: 0 });
                                                            },
                                                            variant: 'tertiary',
                                                            isDestructive: true
                                                        },
                                                        'Remove'
                                                    )
                                                )
                                                : createElement(Button, { onClick: obj.open, variant: 'secondary' }, 'Select Card Image')
                                        );
                                    }
                                })
                            ),
                            createElement(TextControl, {
                                label: 'Card Title',
                                value: leftCardTitle,
                                onChange: function (value) {
                                    setAttributes({ leftCardTitle: value });
                                }
                            }),
                            createElement(TextareaControl, {
                                label: 'Card Text',
                                value: leftCardText,
                                onChange: function (value) {
                                    setAttributes({ leftCardText: value });
                                }
                            })
                        )
                    ),
                    // Right Card Panel
                    createElement(
                        PanelBody,
                        { title: 'Right Info Card', initialOpen: false },
                        createElement(ToggleControl, {
                            label: 'Show Right Card',
                            checked: showRightCard,
                            onChange: function (value) {
                                setAttributes({ showRightCard: value });
                            }
                        }),
                        showRightCard && createElement(
                            Fragment,
                            null,
                            createElement(TextControl, {
                                label: 'Card Image URL',
                                value: rightCardImage,
                                onChange: function (value) {
                                    setAttributes({ rightCardImage: value });
                                }
                            }),
                            createElement(TextControl, {
                                label: 'Card Title',
                                value: rightCardTitle,
                                onChange: function (value) {
                                    setAttributes({ rightCardTitle: value });
                                }
                            }),
                            createElement(TextareaControl, {
                                label: 'Card Text',
                                value: rightCardText,
                                onChange: function (value) {
                                    setAttributes({ rightCardText: value });
                                }
                            })
                        )
                    )
                ),
                createElement(
                    'div',
                    blockProps,
                    createElement(ServerSideRender, {
                        block: 'ihowz/hero',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'cover-image',
                                    label: 'Hero Section'
                                },
                                'Configure your hero section in the sidebar settings.'
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'cover-image',
                                    label: 'Hero Section'
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

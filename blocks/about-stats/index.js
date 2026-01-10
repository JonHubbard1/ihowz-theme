(function (blocks, element, blockEditor, components, serverSideRender) {
    const { registerBlockType } = blocks;
    const { createElement, Fragment } = element;
    const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = blockEditor;
    const {
        PanelBody,
        TextControl,
        TextareaControl,
        ToggleControl,
        Button,
        Card,
        CardBody,
        CardHeader,
        Placeholder,
        Spinner
    } = components;
    const ServerSideRender = serverSideRender;

    registerBlockType('ihowz/about-stats', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                mainText,
                stats,
                secondaryText,
                showButton,
                buttonText,
                buttonUrl,
                image1Url,
                image2Url
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-about-stats-editor'
            });

            // Helper: Update a specific stat
            function updateStat(statIndex, newStatData) {
                var newStats = stats.slice();
                newStats[statIndex] = Object.assign({}, newStats[statIndex], newStatData);
                setAttributes({ stats: newStats });
            }

            // Helper: Add a new stat
            function addStat() {
                if (stats.length >= 4) return;
                var newStats = stats.slice();
                newStats.push({ number: '', label1: '', label2: '' });
                setAttributes({ stats: newStats });
            }

            // Helper: Remove a stat
            function removeStat(statIndex) {
                var newStats = stats.slice();
                newStats.splice(statIndex, 1);
                setAttributes({ stats: newStats });
            }

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    // Top Content
                    createElement(
                        PanelBody,
                        { title: 'Top Content', initialOpen: true },
                        createElement(TextControl, {
                            label: 'Eyebrow Text',
                            value: eyebrowText,
                            onChange: function (value) {
                                setAttributes({ eyebrowText: value });
                            }
                        }),
                        createElement(TextareaControl, {
                            label: 'Main Text',
                            help: 'Large text displayed in the top right',
                            value: mainText,
                            onChange: function (value) {
                                setAttributes({ mainText: value });
                            }
                        })
                    ),
                    // Stats
                    createElement(
                        PanelBody,
                        { title: 'Statistics (' + stats.length + '/4)', initialOpen: true },
                        stats.map(function (stat, statIndex) {
                            return createElement(
                                Card,
                                { key: statIndex, style: { marginBottom: '12px' } },
                                createElement(
                                    CardHeader,
                                    null,
                                    createElement(
                                        'div',
                                        { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%' } },
                                        createElement('strong', null, 'Stat ' + (statIndex + 1)),
                                        createElement(
                                            Button,
                                            {
                                                isDestructive: true,
                                                isSmall: true,
                                                onClick: function () { removeStat(statIndex); }
                                            },
                                            'Remove'
                                        )
                                    )
                                ),
                                createElement(
                                    CardBody,
                                    null,
                                    createElement(TextControl, {
                                        label: 'Number (e.g., 500+)',
                                        value: stat.number,
                                        onChange: function (value) {
                                            updateStat(statIndex, { number: value });
                                        }
                                    }),
                                    createElement(TextControl, {
                                        label: 'Label Line 1',
                                        value: stat.label1,
                                        onChange: function (value) {
                                            updateStat(statIndex, { label1: value });
                                        }
                                    }),
                                    createElement(TextControl, {
                                        label: 'Label Line 2 (optional)',
                                        value: stat.label2,
                                        onChange: function (value) {
                                            updateStat(statIndex, { label2: value });
                                        }
                                    })
                                )
                            );
                        }),
                        stats.length < 4 && createElement(
                            Button,
                            {
                                variant: 'primary',
                                onClick: addStat
                            },
                            'Add Stat'
                        )
                    ),
                    // Bottom Content
                    createElement(
                        PanelBody,
                        { title: 'Bottom Content', initialOpen: false },
                        createElement(TextareaControl, {
                            label: 'Secondary Text',
                            value: secondaryText,
                            onChange: function (value) {
                                setAttributes({ secondaryText: value });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: 'Show Button',
                            checked: showButton,
                            onChange: function (value) {
                                setAttributes({ showButton: value });
                            }
                        }),
                        showButton && createElement(TextControl, {
                            label: 'Button Text',
                            value: buttonText,
                            onChange: function (value) {
                                setAttributes({ buttonText: value });
                            }
                        }),
                        showButton && createElement(TextControl, {
                            label: 'Button URL',
                            value: buttonUrl,
                            onChange: function (value) {
                                setAttributes({ buttonUrl: value });
                            }
                        })
                    ),
                    // Images
                    createElement(
                        PanelBody,
                        { title: 'Images', initialOpen: false },
                        // Image 1
                        createElement(
                            'div',
                            { style: { marginBottom: '20px' } },
                            createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '600' } }, 'Image 1 (Small, Left)'),
                            image1Url && createElement('img', {
                                src: image1Url,
                                style: { maxWidth: '100%', height: 'auto', marginBottom: '8px', borderRadius: '4px' }
                            }),
                            createElement(
                                MediaUploadCheck,
                                null,
                                createElement(MediaUpload, {
                                    onSelect: function (media) {
                                        var imageUrl = media.url;
                                        if (media.sizes && media.sizes.medium_large) {
                                            imageUrl = media.sizes.medium_large.url;
                                        } else if (media.sizes && media.sizes.large) {
                                            imageUrl = media.sizes.large.url;
                                        }
                                        setAttributes({ image1Url: imageUrl });
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
                                            image1Url ? 'Replace' : 'Select Image'
                                        );
                                    }
                                })
                            ),
                            image1Url && createElement(
                                Button,
                                {
                                    onClick: function () { setAttributes({ image1Url: '' }); },
                                    variant: 'tertiary',
                                    isDestructive: true
                                },
                                'Remove'
                            ),
                            createElement(TextControl, {
                                label: 'Or paste URL',
                                value: image1Url,
                                onChange: function (value) {
                                    setAttributes({ image1Url: value });
                                }
                            })
                        ),
                        // Image 2
                        createElement(
                            'div',
                            null,
                            createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '600' } }, 'Image 2 (Large, Right)'),
                            image2Url && createElement('img', {
                                src: image2Url,
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
                                        setAttributes({ image2Url: imageUrl });
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
                                            image2Url ? 'Replace' : 'Select Image'
                                        );
                                    }
                                })
                            ),
                            image2Url && createElement(
                                Button,
                                {
                                    onClick: function () { setAttributes({ image2Url: '' }); },
                                    variant: 'tertiary',
                                    isDestructive: true
                                },
                                'Remove'
                            ),
                            createElement(TextControl, {
                                label: 'Or paste URL',
                                value: image2Url,
                                onChange: function (value) {
                                    setAttributes({ image2Url: value });
                                }
                            })
                        )
                    )
                ),
                createElement(
                    'div',
                    blockProps,
                    createElement(ServerSideRender, {
                        block: 'ihowz/about-stats',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'chart-bar',
                                    label: 'About Stats'
                                },
                                'Configure your about section in the sidebar.'
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'chart-bar',
                                    label: 'About Stats'
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

/**
 * Features Banner Block
 *
 * @package iHowz Theme
 */

(function (blocks, element, blockEditor, components, i18n) {
    const { registerBlockType } = blocks;
    const { createElement: el, Fragment } = element;
    const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, useBlockProps } = blockEditor;
    const { PanelBody, TextControl, TextareaControl, SelectControl, RangeControl, ColorPicker, Button, IconButton } = components;
    const { __ } = i18n;

    // Icon options for feature cards
    const iconOptions = [
        { label: 'Lightbulb', value: 'lightbulb' },
        { label: 'Home', value: 'home' },
        { label: 'Star', value: 'star' },
        { label: 'Shield', value: 'shield' },
        { label: 'Users', value: 'users' },
        { label: 'Chart', value: 'chart' },
        { label: 'Check', value: 'check' },
        { label: 'Heart', value: 'heart' },
    ];

    // SVG icons for preview
    const iconSvgs = {
        lightbulb: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M9 18h6M10 22h4M12 2v1M12 8a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.4V18h4v-2.6c1.2-.6 2-1.9 2-3.4a4 4 0 0 0-4-4z' })
        ),
        home: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z' }),
            el('polyline', { points: '9 22 9 12 15 12 15 22' })
        ),
        star: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('polygon', { points: '12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2' })
        ),
        shield: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' })
        ),
        users: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }),
            el('circle', { cx: '9', cy: '7', r: '4' }),
            el('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }),
            el('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })
        ),
        chart: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('line', { x1: '18', y1: '20', x2: '18', y2: '10' }),
            el('line', { x1: '12', y1: '20', x2: '12', y2: '4' }),
            el('line', { x1: '6', y1: '20', x2: '6', y2: '14' })
        ),
        check: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('polyline', { points: '20 6 9 17 4 12' })
        ),
        heart: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' })
        ),
    };

    registerBlockType('ihowz/features-banner', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                heading,
                imageCardTitle,
                imageCardSubtitle,
                imageCardTitlePosition,
                imageId,
                imageUrl,
                imageMinHeight,
                features,
                backgroundColor
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-features-banner',
                style: { backgroundColor: backgroundColor }
            });

            // Update a feature at specific index
            const updateFeature = (index, key, value) => {
                const newFeatures = [...features];
                newFeatures[index] = { ...newFeatures[index], [key]: value };
                setAttributes({ features: newFeatures });
            };

            // Add new feature
            const addFeature = () => {
                const newFeatures = [...features, {
                    icon: 'lightbulb',
                    title: 'New Feature',
                    description: 'Describe your feature here.'
                }];
                setAttributes({ features: newFeatures });
            };

            // Remove feature
            const removeFeature = (index) => {
                const newFeatures = features.filter((_, i) => i !== index);
                setAttributes({ features: newFeatures });
            };

            return el(
                Fragment,
                null,
                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,
                    // General Settings
                    el(
                        PanelBody,
                        { title: __('General Settings', 'ihowz-theme'), initialOpen: true },
                        el(TextControl, {
                            label: __('Eyebrow Text', 'ihowz-theme'),
                            value: eyebrowText,
                            onChange: (value) => setAttributes({ eyebrowText: value })
                        }),
                        el(TextControl, {
                            label: __('Heading', 'ihowz-theme'),
                            value: heading,
                            onChange: (value) => setAttributes({ heading: value })
                        })
                    ),
                    // Image Card Settings
                    el(
                        PanelBody,
                        { title: __('Image Card', 'ihowz-theme'), initialOpen: false },
                        el(TextControl, {
                            label: __('Image Card Title', 'ihowz-theme'),
                            value: imageCardTitle,
                            onChange: (value) => setAttributes({ imageCardTitle: value })
                        }),
                        el(TextControl, {
                            label: __('Image Card Subtitle', 'ihowz-theme'),
                            value: imageCardSubtitle,
                            onChange: (value) => setAttributes({ imageCardSubtitle: value })
                        }),
                        el(SelectControl, {
                            label: __('Title Position', 'ihowz-theme'),
                            value: imageCardTitlePosition || 'top',
                            options: [
                                { label: 'Top', value: 'top' },
                                { label: 'Bottom', value: 'bottom' }
                            ],
                            onChange: (value) => setAttributes({ imageCardTitlePosition: value })
                        }),
                        el(RangeControl, {
                            label: __('Image Min Height (px)', 'ihowz-theme'),
                            value: imageMinHeight || 400,
                            onChange: (value) => setAttributes({ imageMinHeight: value }),
                            min: 200,
                            max: 800
                        }),
                        el(
                            MediaUploadCheck,
                            null,
                            el(MediaUpload, {
                                onSelect: (media) => setAttributes({
                                    imageId: media.id,
                                    imageUrl: media.url
                                }),
                                allowedTypes: ['image'],
                                value: imageId,
                                render: ({ open }) => el(
                                    'div',
                                    { className: 'editor-media-upload' },
                                    imageUrl
                                        ? el(
                                            'div',
                                            null,
                                            el('img', { src: imageUrl, style: { maxWidth: '100%', marginBottom: '10px' } }),
                                            el(Button, { onClick: open, variant: 'secondary' }, __('Replace Image', 'ihowz-theme')),
                                            el(Button, {
                                                onClick: () => setAttributes({ imageId: 0, imageUrl: '' }),
                                                variant: 'link',
                                                isDestructive: true,
                                                style: { marginLeft: '10px' }
                                            }, __('Remove', 'ihowz-theme'))
                                        )
                                        : el(Button, { onClick: open, variant: 'primary' }, __('Select Image', 'ihowz-theme'))
                                )
                            })
                        )
                    ),
                    // Feature Cards Settings
                    el(
                        PanelBody,
                        { title: __('Feature Cards', 'ihowz-theme'), initialOpen: false },
                        features.map((feature, index) =>
                            el(
                                'div',
                                { key: index, className: 'feature-card-settings', style: { marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #ddd' } },
                                el('h4', { style: { marginBottom: '10px' } }, __('Feature', 'ihowz-theme') + ' ' + (index + 1)),
                                el(SelectControl, {
                                    label: __('Icon', 'ihowz-theme'),
                                    value: feature.icon,
                                    options: iconOptions,
                                    onChange: (value) => updateFeature(index, 'icon', value)
                                }),
                                el(TextControl, {
                                    label: __('Title', 'ihowz-theme'),
                                    value: feature.title,
                                    onChange: (value) => updateFeature(index, 'title', value)
                                }),
                                el(TextareaControl, {
                                    label: __('Description', 'ihowz-theme'),
                                    value: feature.description,
                                    onChange: (value) => updateFeature(index, 'description', value)
                                }),
                                el(Button, {
                                    onClick: () => removeFeature(index),
                                    variant: 'link',
                                    isDestructive: true
                                }, __('Remove Feature', 'ihowz-theme'))
                            )
                        ),
                        el(Button, {
                            onClick: addFeature,
                            variant: 'secondary',
                            style: { marginTop: '10px' }
                        }, __('Add Feature', 'ihowz-theme'))
                    ),
                    // Style Settings
                    el(
                        PanelBody,
                        { title: __('Style', 'ihowz-theme'), initialOpen: false },
                        el('label', { style: { display: 'block', marginBottom: '8px' } }, __('Background Color', 'ihowz-theme')),
                        el(ColorPicker, {
                            color: backgroundColor,
                            onChangeComplete: (value) => setAttributes({ backgroundColor: value.hex })
                        })
                    )
                ),
                // Block Preview
                el(
                    'section',
                    blockProps,
                    el(
                        'div',
                        { className: 'features-banner-container' },
                        // Header
                        el(
                            'div',
                            { className: 'features-banner-header' },
                            el('span', { className: 'features-banner-eyebrow' }, eyebrowText),
                            el('h2', { className: 'features-banner-heading' }, heading)
                        ),
                        // Content Grid
                        el(
                            'div',
                            { className: 'features-banner-grid' },
                            // Image Card
                            el(
                                'div',
                                { className: 'features-banner-image-card title-position-' + (imageCardTitlePosition || 'top'), style: { minHeight: (imageMinHeight || 400) + 'px' } },
                                imageUrl
                                    ? el('img', { src: imageUrl, className: 'features-banner-image', alt: imageCardTitle })
                                    : el('div', { className: 'features-banner-image-placeholder' }, __('Select an image', 'ihowz-theme')),
                                el(
                                    'div',
                                    { className: 'features-banner-image-overlay' },
                                    el(
                                        'div',
                                        { className: 'features-banner-image-content' },
                                        el('h3', { className: 'features-banner-image-title' }, imageCardTitle),
                                        el('p', { className: 'features-banner-image-subtitle' }, imageCardSubtitle)
                                    ),
                                    el(
                                        'div',
                                        { className: 'features-banner-decorative-circle' },
                                        el('svg', { viewBox: '0 0 100 100', className: 'circular-progress' },
                                            el('circle', { cx: '50', cy: '50', r: '45', fill: 'none', stroke: 'rgba(255,255,255,0.2)', strokeWidth: '2' }),
                                            el('circle', { cx: '50', cy: '50', r: '45', fill: 'none', stroke: 'rgba(255,255,255,0.8)', strokeWidth: '2', strokeDasharray: '200 283', strokeLinecap: 'round' })
                                        )
                                    )
                                )
                            ),
                            // Feature Cards
                            features.map((feature, index) =>
                                el(
                                    'div',
                                    { key: index, className: 'features-banner-card' },
                                    el('div', { className: 'features-banner-card-icon' }, iconSvgs[feature.icon] || iconSvgs.lightbulb),
                                    el('h3', { className: 'features-banner-card-title' }, feature.title),
                                    el('p', { className: 'features-banner-card-description' }, feature.description)
                                )
                            )
                        )
                    )
                )
            );
        },

        save: function () {
            // Dynamic block - rendered via PHP
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n
);

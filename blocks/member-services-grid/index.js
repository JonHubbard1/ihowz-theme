/**
 * Member Services Grid Block
 *
 * @package iHowz Theme
 */

(function (blocks, element, blockEditor, components, i18n) {
    const { registerBlockType } = blocks;
    const { createElement: el, Fragment } = element;
    const { InspectorControls, useBlockProps } = blockEditor;
    const { PanelBody, TextControl, TextareaControl, SelectControl, ColorPicker, Button } = components;
    const { __ } = i18n;

    // Icon options
    const iconOptions = [
        { label: 'Calendar (Meetings)', value: 'calendar' },
        { label: 'Lightbulb (Advice)', value: 'lightbulb' },
        { label: 'Newspaper (News)', value: 'newspaper' },
        { label: 'Megaphone (Campaigns)', value: 'megaphone' },
        { label: 'Tag (Offers)', value: 'tag' },
        { label: 'Folder (Documents)', value: 'folder' },
        { label: 'Home', value: 'home' },
        { label: 'Users', value: 'users' },
        { label: 'Shield', value: 'shield' },
        { label: 'Chart', value: 'chart' },
        { label: 'Star', value: 'star' },
        { label: 'Heart', value: 'heart' }
    ];

    // SVG icons
    const iconSvgs = {
        calendar: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('rect', { x: '3', y: '4', width: '18', height: '18', rx: '2', ry: '2' }),
            el('line', { x1: '16', y1: '2', x2: '16', y2: '6' }),
            el('line', { x1: '8', y1: '2', x2: '8', y2: '6' }),
            el('line', { x1: '3', y1: '10', x2: '21', y2: '10' })
        ),
        lightbulb: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M9 18h6M10 22h4M12 2v1M12 8a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.4V18h4v-2.6c1.2-.6 2-1.9 2-3.4a4 4 0 0 0-4-4z' })
        ),
        newspaper: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2' }),
            el('line', { x1: '10', y1: '6', x2: '18', y2: '6' }),
            el('line', { x1: '10', y1: '10', x2: '18', y2: '10' }),
            el('line', { x1: '10', y1: '14', x2: '18', y2: '14' })
        ),
        megaphone: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M3 11l18-5v12L3 13v-2z' }),
            el('path', { d: 'M11.6 16.8a3 3 0 1 1-5.8-1.6' })
        ),
        tag: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z' }),
            el('line', { x1: '7', y1: '7', x2: '7.01', y2: '7' })
        ),
        folder: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z' })
        ),
        home: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z' }),
            el('polyline', { points: '9 22 9 12 15 12 15 22' })
        ),
        users: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }),
            el('circle', { cx: '9', cy: '7', r: '4' }),
            el('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }),
            el('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })
        ),
        shield: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' })
        ),
        chart: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('line', { x1: '18', y1: '20', x2: '18', y2: '10' }),
            el('line', { x1: '12', y1: '20', x2: '12', y2: '4' }),
            el('line', { x1: '6', y1: '20', x2: '6', y2: '14' })
        ),
        star: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('polygon', { points: '12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2' })
        ),
        heart: el('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
            el('path', { d: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' })
        )
    };

    registerBlockType('ihowz/member-services-grid', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                heading,
                subheading,
                services,
                backgroundColor
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-member-services-grid',
                style: { backgroundColor: backgroundColor }
            });

            // Update a service at specific index
            const updateService = (index, key, value) => {
                const newServices = [...services];
                newServices[index] = { ...newServices[index], [key]: value };
                setAttributes({ services: newServices });
            };

            // Add new service
            const addService = () => {
                if (services.length >= 6) return;
                const newServices = [...services, {
                    icon: 'calendar',
                    title: 'New Service',
                    description: 'Describe this member service.',
                    buttonText: 'Learn More',
                    buttonUrl: '#'
                }];
                setAttributes({ services: newServices });
            };

            // Remove service
            const removeService = (index) => {
                const newServices = services.filter((_, i) => i !== index);
                setAttributes({ services: newServices });
            };

            return el(
                Fragment,
                null,
                // Inspector Controls
                el(
                    InspectorControls,
                    null,
                    // Header Settings
                    el(
                        PanelBody,
                        { title: __('Header Settings', 'ihowz-theme'), initialOpen: true },
                        el(TextControl, {
                            label: __('Eyebrow Text', 'ihowz-theme'),
                            value: eyebrowText,
                            onChange: (value) => setAttributes({ eyebrowText: value })
                        }),
                        el(TextControl, {
                            label: __('Heading', 'ihowz-theme'),
                            value: heading,
                            onChange: (value) => setAttributes({ heading: value })
                        }),
                        el(TextareaControl, {
                            label: __('Subheading', 'ihowz-theme'),
                            value: subheading,
                            onChange: (value) => setAttributes({ subheading: value })
                        })
                    ),
                    // Services
                    el(
                        PanelBody,
                        { title: __('Services', 'ihowz-theme'), initialOpen: false },
                        services.map((service, index) =>
                            el(
                                'div',
                                { key: index, className: 'service-settings', style: { marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #ddd' } },
                                el('h4', { style: { marginBottom: '10px' } }, __('Service', 'ihowz-theme') + ' ' + (index + 1)),
                                el(SelectControl, {
                                    label: __('Icon', 'ihowz-theme'),
                                    value: service.icon,
                                    options: iconOptions,
                                    onChange: (value) => updateService(index, 'icon', value)
                                }),
                                el(TextControl, {
                                    label: __('Title', 'ihowz-theme'),
                                    value: service.title,
                                    onChange: (value) => updateService(index, 'title', value)
                                }),
                                el(TextareaControl, {
                                    label: __('Description', 'ihowz-theme'),
                                    value: service.description,
                                    onChange: (value) => updateService(index, 'description', value)
                                }),
                                el(TextControl, {
                                    label: __('Button Text', 'ihowz-theme'),
                                    value: service.buttonText,
                                    onChange: (value) => updateService(index, 'buttonText', value)
                                }),
                                el(TextControl, {
                                    label: __('Button URL', 'ihowz-theme'),
                                    value: service.buttonUrl,
                                    onChange: (value) => updateService(index, 'buttonUrl', value)
                                }),
                                el(Button, {
                                    onClick: () => removeService(index),
                                    variant: 'link',
                                    isDestructive: true
                                }, __('Remove Service', 'ihowz-theme'))
                            )
                        ),
                        services.length < 6 && el(Button, {
                            onClick: addService,
                            variant: 'secondary',
                            style: { marginTop: '10px' }
                        }, __('Add Service', 'ihowz-theme'))
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
                        { className: 'member-services-grid-container' },
                        // Header
                        el(
                            'div',
                            { className: 'member-services-grid-header' },
                            eyebrowText && el('span', { className: 'member-services-grid-eyebrow' }, eyebrowText),
                            heading && el('h2', { className: 'member-services-grid-heading' }, heading),
                            subheading && el('p', { className: 'member-services-grid-subheading' }, subheading)
                        ),
                        // Grid
                        el(
                            'div',
                            { className: 'member-services-grid-cards' },
                            services.map((service, index) =>
                                el(
                                    'div',
                                    { key: index, className: 'member-services-grid-card' },
                                    el('div', { className: 'member-services-grid-card-icon' }, iconSvgs[service.icon] || iconSvgs.calendar),
                                    el('h3', { className: 'member-services-grid-card-title' }, service.title),
                                    el('p', { className: 'member-services-grid-card-description' }, service.description),
                                    service.buttonText && el(
                                        'a',
                                        { href: service.buttonUrl || '#', className: 'member-services-grid-card-btn' },
                                        service.buttonText
                                    )
                                )
                            ),
                            services.length === 0 && el(
                                'p',
                                { style: { gridColumn: '1 / -1', textAlign: 'center', color: '#999' } },
                                __('Add services in the sidebar settings.', 'ihowz-theme')
                            )
                        )
                    )
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
    window.wp.i18n
);

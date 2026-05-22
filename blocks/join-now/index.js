/**
 * Join Now Block
 *
 * @package iHowz Theme
 */

(function (blocks, element, blockEditor, components, i18n) {
    const { registerBlockType } = blocks;
    const { createElement: el, Fragment } = element;
    const { InspectorControls, useBlockProps, ServerSideRender } = blockEditor;
    const { PanelBody, TextControl, TextareaControl, ToggleControl, ColorPicker, Placeholder, Spinner, SelectControl } = components;
    const { __ } = i18n;

    // Get membership types for the select control
    // In a real implementation, these would come from an API or be passed from PHP
    const membershipTypeOptions = window.ihowzJoinBlockTypes || [
        { label: __('Use default / Any available', 'ihowz-theme'), value: '' }
    ];

    registerBlockType('ihowz/join-now', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                heading,
                description,
                membershipTypeId,
                backgroundColor,
                showPricing,
                successMessage
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-join-now-editor'
            });

            return el(
                Fragment,
                null,
                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: __('Header Content', 'ihowz-theme'), initialOpen: true },
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
                            label: __('Description', 'ihowz-theme'),
                            value: description,
                            onChange: (value) => setAttributes({ description: value }),
                            help: __('Optional description text below the heading.', 'ihowz-theme')
                        })
                    ),
                    el(
                        PanelBody,
                        { title: __('Membership Settings', 'ihowz-theme'), initialOpen: false },
                        el(SelectControl, {
                            label: __('Default Membership Type', 'ihowz-theme'),
                            value: membershipTypeId,
                            options: membershipTypeOptions,
                            onChange: (value) => setAttributes({ membershipTypeId: value }),
                            help: __('Pre-select a membership type. Leave empty to let user choose.', 'ihowz-theme')
                        }),
                        el(ToggleControl, {
                            label: __('Show Pricing', 'ihowz-theme'),
                            checked: showPricing,
                            onChange: (value) => setAttributes({ showPricing: value }),
                            help: __('Display prices on membership type cards.', 'ihowz-theme')
                        })
                    ),
                    el(
                        PanelBody,
                        { title: __('Styling', 'ihowz-theme'), initialOpen: false },
                        el('label', { style: { display: 'block', marginBottom: '8px' } }, __('Background Color', 'ihowz-theme')),
                        el(ColorPicker, {
                            color: backgroundColor,
                            onChangeComplete: (value) => setAttributes({ backgroundColor: value.hex })
                        })
                    ),
                    el(
                        PanelBody,
                        { title: __('Messages', 'ihowz-theme'), initialOpen: false },
                        el(TextareaControl, {
                            label: __('Success Message', 'ihowz-theme'),
                            value: successMessage,
                            onChange: (value) => setAttributes({ successMessage: value }),
                            help: __('Message shown after successful signup and payment.', 'ihowz-theme')
                        })
                    )
                ),
                // Block Preview
                el(
                    'div',
                    blockProps,
                    el(ServerSideRender, {
                        block: 'ihowz/join-now',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return el(
                                Placeholder,
                                {
                                    icon: 'groups',
                                    label: __('Join Now', 'ihowz-theme')
                                },
                                __('Configure your membership signup form in the sidebar settings.', 'ihowz-theme')
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return el(
                                Placeholder,
                                {
                                    icon: 'groups',
                                    label: __('Join Now', 'ihowz-theme')
                                },
                                el(Spinner)
                            );
                        }
                    })
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

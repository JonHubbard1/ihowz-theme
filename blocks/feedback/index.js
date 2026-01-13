(function (blocks, element, blockEditor, components, serverSideRender) {
    const { registerBlockType } = blocks;
    const { createElement, Fragment } = element;
    const { InspectorControls, useBlockProps } = blockEditor;
    const {
        PanelBody,
        TextControl,
        TextareaControl,
        ToggleControl,
        SelectControl,
        Notice,
        Placeholder,
        Spinner,
        ExternalLink
    } = components;
    const ServerSideRender = serverSideRender;

    registerBlockType('ihowz/feedback', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                heading,
                showButton,
                buttonText,
                buttonUrl,
                displayRows,
                testimonials
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-feedback-editor'
            });

            // Check for legacy data (testimonials stored in block attributes)
            const hasLegacyData = testimonials && testimonials.length > 0;

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    // Header Content Panel (unchanged)
                    createElement(
                        PanelBody,
                        { title: 'Header Content', initialOpen: true },
                        createElement(TextControl, {
                            label: 'Eyebrow Text',
                            value: eyebrowText,
                            onChange: function (value) {
                                setAttributes({ eyebrowText: value });
                            }
                        }),
                        createElement(TextareaControl, {
                            label: 'Heading',
                            value: heading,
                            onChange: function (value) {
                                setAttributes({ heading: value });
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
                    // Testimonials Display Panel
                    createElement(
                        PanelBody,
                        { title: 'Testimonials Display', initialOpen: true },
                        hasLegacyData && createElement(
                            Notice,
                            { status: 'warning', isDismissible: false },
                            'This block contains legacy testimonial data. After running the migration tool, testimonials will be loaded from the database instead.'
                        ),
                        createElement(SelectControl, {
                            label: 'Number of Rows',
                            value: displayRows || '2',
                            options: [
                                { label: '1 row (2 testimonials)', value: '1' },
                                { label: '2 rows (5 testimonials)', value: '2' }
                            ],
                            onChange: function (value) {
                                setAttributes({ displayRows: value });
                            },
                            help: 'Testimonials are randomly selected from the database on each page load.'
                        }),
                        createElement(
                            'div',
                            { style: { marginTop: '16px' } },
                            createElement(
                                ExternalLink,
                                { href: '/wp-admin/edit.php?post_type=ihowz_testimonial' },
                                'Manage Testimonials'
                            )
                        ),
                        createElement(
                            'div',
                            { style: { marginTop: '8px' } },
                            createElement(
                                ExternalLink,
                                { href: '/wp-admin/post-new.php?post_type=ihowz_testimonial' },
                                'Add New Testimonial'
                            )
                        )
                    )
                ),
                createElement(
                    'div',
                    blockProps,
                    createElement(ServerSideRender, {
                        block: 'ihowz/feedback',
                        attributes: attributes,
                        EmptyResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'format-quote',
                                    label: 'Feedback / Testimonials'
                                },
                                createElement(
                                    'p',
                                    null,
                                    'No testimonials found. '
                                ),
                                createElement(
                                    ExternalLink,
                                    { href: '/wp-admin/post-new.php?post_type=ihowz_testimonial' },
                                    'Add your first testimonial'
                                )
                            );
                        },
                        LoadingResponsePlaceholder: function () {
                            return createElement(
                                Placeholder,
                                {
                                    icon: 'format-quote',
                                    label: 'Feedback / Testimonials'
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

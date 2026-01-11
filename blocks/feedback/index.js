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

    registerBlockType('ihowz/feedback', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                eyebrowText,
                heading,
                showButton,
                buttonText,
                buttonUrl,
                testimonials
            } = attributes;

            const blockProps = useBlockProps({
                className: 'ihowz-feedback-editor'
            });

            // Helper: Update a specific testimonial
            function updateTestimonial(index, newData) {
                var newTestimonials = testimonials.slice();
                newTestimonials[index] = Object.assign({}, newTestimonials[index], newData);
                setAttributes({ testimonials: newTestimonials });
            }

            // Helper: Add a new testimonial
            function addTestimonial() {
                if (testimonials.length >= 6) return;
                var newTestimonials = testimonials.slice();
                newTestimonials.push({ quote: '', authorName: '', authorRole: '', authorImage: '' });
                setAttributes({ testimonials: newTestimonials });
            }

            // Helper: Remove a testimonial
            function removeTestimonial(index) {
                var newTestimonials = testimonials.slice();
                newTestimonials.splice(index, 1);
                setAttributes({ testimonials: newTestimonials });
            }

            return createElement(
                Fragment,
                null,
                createElement(
                    InspectorControls,
                    null,
                    // Header Content
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
                    // Testimonials
                    createElement(
                        PanelBody,
                        { title: 'Testimonials (' + testimonials.length + '/6)', initialOpen: true },
                        testimonials.map(function (testimonial, index) {
                            return createElement(
                                Card,
                                { key: index, style: { marginBottom: '12px' } },
                                createElement(
                                    CardHeader,
                                    null,
                                    createElement(
                                        'div',
                                        { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%' } },
                                        createElement('strong', null, 'Testimonial ' + (index + 1)),
                                        createElement(
                                            Button,
                                            {
                                                isDestructive: true,
                                                isSmall: true,
                                                onClick: function () { removeTestimonial(index); }
                                            },
                                            'Remove'
                                        )
                                    )
                                ),
                                createElement(
                                    CardBody,
                                    null,
                                    createElement(TextareaControl, {
                                        label: 'Quote',
                                        value: testimonial.quote,
                                        onChange: function (value) {
                                            updateTestimonial(index, { quote: value });
                                        }
                                    }),
                                    createElement(TextControl, {
                                        label: 'Author Name',
                                        value: testimonial.authorName,
                                        onChange: function (value) {
                                            updateTestimonial(index, { authorName: value });
                                        }
                                    }),
                                    createElement(TextControl, {
                                        label: 'Author Role/Company',
                                        value: testimonial.authorRole,
                                        onChange: function (value) {
                                            updateTestimonial(index, { authorRole: value });
                                        }
                                    }),
                                    // Author Image
                                    createElement(
                                        'div',
                                        { style: { marginTop: '12px' } },
                                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '600' } }, 'Author Photo'),
                                        testimonial.authorImage && createElement('img', {
                                            src: testimonial.authorImage,
                                            style: { width: '60px', height: '60px', borderRadius: '50%', objectFit: 'cover', marginBottom: '8px', display: 'block' }
                                        }),
                                        createElement(
                                            MediaUploadCheck,
                                            null,
                                            createElement(MediaUpload, {
                                                onSelect: function (media) {
                                                    var imageUrl = media.url;
                                                    if (media.sizes && media.sizes.thumbnail) {
                                                        imageUrl = media.sizes.thumbnail.url;
                                                    }
                                                    updateTestimonial(index, { authorImage: imageUrl });
                                                },
                                                allowedTypes: ['image'],
                                                render: function (obj) {
                                                    return createElement(
                                                        Button,
                                                        {
                                                            onClick: obj.open,
                                                            variant: 'secondary',
                                                            isSmall: true,
                                                            style: { marginRight: '8px' }
                                                        },
                                                        testimonial.authorImage ? 'Replace' : 'Select Photo'
                                                    );
                                                }
                                            })
                                        ),
                                        testimonial.authorImage && createElement(
                                            Button,
                                            {
                                                onClick: function () { updateTestimonial(index, { authorImage: '' }); },
                                                variant: 'tertiary',
                                                isDestructive: true,
                                                isSmall: true
                                            },
                                            'Remove'
                                        ),
                                        createElement(TextControl, {
                                            label: 'Or paste image URL',
                                            value: testimonial.authorImage,
                                            onChange: function (value) {
                                                updateTestimonial(index, { authorImage: value });
                                            }
                                        })
                                    )
                                )
                            );
                        }),
                        testimonials.length < 6 && createElement(
                            Button,
                            {
                                variant: 'primary',
                                onClick: addTestimonial
                            },
                            'Add Testimonial'
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
                                'Configure your testimonials in the sidebar.'
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

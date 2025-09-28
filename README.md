# iHowz WordPress Theme

A modern, responsive WordPress theme designed specifically for the iHowz website. This theme was created by converting from an Oxygen Builder layout to a traditional WordPress theme structure.

## Description

The iHowz theme provides a clean, professional design perfect for news and content websites. It features a responsive grid layout, modern typography, and seamless integration with the iHowz Management Suite plugin.

## Features

### Design & Layout
- **Responsive Design**: Mobile-first approach that works on all devices
- **Modern Grid Layout**: Clean card-based design for posts and pages
- **Professional Typography**: Easy-to-read fonts and proper spacing
- **Smooth Animations**: Hover effects and transitions for better UX

### Functionality
- **Custom Navigation Menus**: Primary header and footer menus
- **Widget Areas**: Sidebar and 3 footer widget areas
- **Search Functionality**: Built-in search with custom styling
- **Post Pagination**: Clean pagination for blog posts
- **Breadcrumb Navigation**: Helps users understand site structure
- **Back-to-Top Button**: Smooth scrolling enhancement

### WordPress Features
- **Custom Logo Support**: Upload and display your logo
- **Post Thumbnails**: Featured image support
- **Comment System**: Fully styled comment forms and display
- **SEO Friendly**: Clean HTML5 markup
- **Accessibility**: WCAG compliant with proper focus states
- **Plugin Integration**: Optimized for iHowz Management Suite plugin

## Installation

1. Download the theme files
2. Upload to `/wp-content/themes/ihowz/` directory
3. Activate the theme in WordPress Admin > Appearance > Themes
4. Configure menus in Appearance > Menus
5. Add widgets in Appearance > Widgets

## Template Files

- `index.php` - Main blog listing page
- `page.php` - Generic page template
- `page-news.php` - Special template for News page
- `single.php` - Individual post template
- `404.php` - Error page template
- `header.php` - Site header
- `footer.php` - Site footer
- `comments.php` - Comment system
- `searchform.php` - Search form

## Customization

### Menus
Create menus in **Appearance > Menus** and assign them to:
- **Primary Menu**: Main navigation in header
- **Footer Menu**: Links in footer area

### Widget Areas
The theme includes several widget areas:
- **Sidebar**: Main content sidebar
- **Footer 1, 2, 3**: Three footer columns

### Custom Logo
Upload your logo in **Appearance > Customize > Site Identity**

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Technical Requirements

- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+

## Theme Structure

```
ihowz/
├── style.css           # Main stylesheet and theme info
├── index.php           # Main template file
├── functions.php       # Theme functions and setup
├── header.php          # Header template
├── footer.php          # Footer template
├── page.php            # Page template
├── page-news.php       # News page template
├── single.php          # Single post template
├── comments.php        # Comments template
├── searchform.php      # Search form template
├── 404.php             # Error page template
├── js/
│   └── main.js         # Theme JavaScript
└── README.md           # This file
```

## Integration with iHowz Plugin

This theme is designed to work seamlessly with the iHowz Management Suite plugin, providing:
- Styled advertisement blocks
- Member dashboard integration
- Event listing compatibility
- Support ticket system styling

## Development

### File Structure
The theme follows WordPress coding standards and best practices:
- All functions are prefixed with `ihowz_theme_`
- Proper escaping and sanitization
- Semantic HTML5 markup
- CSS follows BEM methodology where applicable

### Hooks and Filters
The theme provides several hooks for customization:
- `ihowz_theme_setup` - Theme setup action
- `ihowz_theme_scripts` - Enqueue scripts action
- Various template hooks for customization

## Support

For support and customization requests, please contact the development team.

## Changelog

### Version 1.0.0
- Initial release
- Converted from Oxygen Builder layout
- Complete responsive design
- Integration with iHowz plugin
- SEO and accessibility optimizations

## License

This theme is licensed under the GPL v2 or later.

## Credits

- Developed for iHowz website
- Converted from Oxygen Builder design
- Built with WordPress best practices
- Responsive design principles
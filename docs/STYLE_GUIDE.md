**iHowz Landlord Association - Design System & Style Guide**

**Brand Vision**

A modern, trustworthy digital platform that empowers landlords with clear guidance, professional resources, and expert support. The design balances professional authority with approachable usability, reflecting 50+ years of industry expertise while embracing contemporary digital standards.

**Colour Palette**

**Primary Colours**

- **Primary Green** #9FC045 (main brand colour for buttons, links and primary actions)
- **Primary White** #FFFFFF (clean backgrounds and card surfaces)
- **Primary Charcoal** #263238 (primary text and strong contrast elements)

**Secondary Colours**

- **Secondary Forest Green** #5F8422 (hover states, active elements and emphasis)
- **Secondary Sage Green** #C8E085 (highlights, selected states and secondary buttons)
- **Secondary Light Green** #F4F9E8 (backgrounds, notifications and subtle accents)

**Accent Colours**

- **Accent Blue** #1976D2 (informational content and trust indicators)
- **Accent Gold** #FF8F00 (premium features and membership highlights)
- **Success Green** #388E3C (confirmations and positive feedback)
- **Warning Orange** #F57C00 (alerts and important notices)
- **Error Red** #D32F2F (errors and critical information)
- **Neutral Gray** #757575 (secondary text and inactive states)
- **Light Gray** #BDBDBD (borders and subtle dividers)

**Background Colours**

- **Background Pure White** #FFFFFF (card backgrounds and content areas)
- **Background Subtle** #FAFAFA (page backgrounds and section dividers)
- **Background Light Green** #F7FCF0 (feature sections and call-to-action areas)
- **Background Dark** #37474F (footer and dark mode primary background)

**Typography**

**Primary Font Family**

- **Primary Font** - Mona Sans (clean, modern sans-serif for optimal readability)
- **Secondary Font** - Georgia (serif for formal documentation and legal text)

**CSS Variable**: `var(--primary-font, "Mona Sans", sans-serif)`

**Font Weights**

- **Light** - 300 (large headings and display text)
- **Regular** - 400 (body text and standard content)
- **Medium** - 500 (subheadings and emphasis)
- **Semi-Bold** - 600 (section headings and important information)
- **Bold** - 700 (page titles and strong emphasis)

**Text Styles**

**Headings**

**H1 - Hero Title**

- 32px/40px, Bold, Letter spacing -0.3px
- Used for main page titles and hero sections

**H2 - Section Header**

- 28px/36px, Semi-Bold, Letter spacing -0.2px
- Used for major section titles and service categories

**H3 - Subsection Header**

- 24px/32px, Semi-Bold, Letter spacing -0.1px
- Used for article titles and feature headings

**H4 - Content Header**

- 20px/28px, Medium, Letter spacing 0px
- Used for card titles and content groupings

**H5 - Minor Header**

- 18px/24px, Medium, Letter spacing 0px
- Used for form labels and small section titles

**Body Text**

**Body Large**

- 18px/28px, Regular, Letter spacing 0px
- Used for important introductory text and key information

**Body Standard**

- 16px/24px, Regular, Letter spacing 0px
- Primary text for articles, descriptions and general content

**Body Small**

- 14px/20px, Regular, Letter spacing 0.1px
- Secondary information, captions and supporting text

**Special Text**

**Caption Text**

- 12px/16px, Medium, Letter spacing 0.3px
- Used for metadata, timestamps and fine print

**Button Text**

- 16px/24px, Medium, Letter spacing 0.1px
- Specifically for interactive button elements

**Link Text**

- 16px/24px, Medium, Letter spacing 0px, Primary Green (#9FC045)
- Used for all clickable text links throughout the site

**Legal Text**

- 14px/22px, Georgia Regular, Letter spacing 0px
- Used for terms, conditions and formal documentation

**Component Styling**

**Buttons**

**Primary Button**

- Background: Primary Green (#9FC045)
- Text: White (#FFFFFF)
- Height: 48px
- Corner Radius: 8px
- Padding: 24px horizontal, 12px vertical
- Hover: Secondary Forest Green (#5F8422)

**Secondary Button**

- Border: 2px Primary Green (#9FC045)
- Background: Transparent
- Text: Primary Green (#9FC045)
- Height: 48px
- Corner Radius: 8px
- Padding: 22px horizontal, 10px vertical
- Hover: Background Light Green (#F4F9E8)

**Ghost Button**

- No background or border
- Text: Primary Green (#9FC045)
- Height: 44px
- Padding: 16px horizontal
- Hover: Background Light Green (#F4F9E8)

**CTA Button (Membership)**

- Background: Accent Gold (#FF8F00)
- Text: White (#FFFFFF)
- Height: 56px
- Corner Radius: 12px
- Padding: 32px horizontal, 16px vertical
- Shadow: 0 4px 12px rgba(255, 143, 0, 0.3)

**Cards**

**Standard Card**

- Background: White (#FFFFFF)
- Shadow: 0 2px 12px rgba(38, 50, 56, 0.08)
- Corner Radius: 12px
- Padding: 24px
- Border: 1px solid #F0F0F0

**Feature Card**

- Background: White (#FFFFFF)
- Shadow: 0 4px 20px rgba(38, 50, 56, 0.12)
- Corner Radius: 16px
- Padding: 32px
- Border: 2px solid Light Green (#F4F9E8)

**Article Card**

- Background: White (#FFFFFF)
- Shadow: 0 1px 8px rgba(38, 50, 56, 0.06)
- Corner Radius: 8px
- Padding: 20px
- Hover: Shadow 0 4px 16px rgba(38, 50, 56, 0.12)

**Input Fields**

**Text Input**

- Height: 56px
- Corner Radius: 8px
- Border: 1px Neutral Gray (#757575)
- Active Border: 2px Primary Green (#9FC045)
- Background: White (#FFFFFF)
- Text: Primary Charcoal (#263238)
- Placeholder: Light Gray (#BDBDBD)
- Padding: 16px horizontal

**Textarea**

- Min Height: 120px
- Corner Radius: 8px
- Border: 1px Neutral Gray (#757575)
- Active Border: 2px Primary Green (#9FC045)
- Background: White (#FFFFFF)
- Padding: 16px
- Resize: vertical only

**Select Dropdown**

- Height: 56px
- Corner Radius: 8px
- Border: 1px Neutral Gray (#757575)
- Background: White (#FFFFFF)
- Arrow: Primary Green (#9FC045)
- Padding: 16px horizontal

**Navigation**

**Primary Navigation**

- Background: White (#FFFFFF)
- Height: 72px
- Shadow: 0 2px 8px rgba(38, 50, 56, 0.06)
- Link Text: Primary Charcoal (#263238)
- Active Link: Primary Green (#9FC045)
- Hover: Secondary Sage Green (#C8E085)

**Mobile Navigation**

- Background: White (#FFFFFF)
- Overlay: rgba(38, 50, 56, 0.8)
- Slide Animation: 300ms ease-out
- Link Padding: 20px vertical

**Icons**

**Icon Specifications**

- **Primary Icons**: 24px × 24px (standard interface icons)
- **Small Icons**: 20px × 20px (inline and compact spaces)
- **Large Icons**: 32px × 32px (feature highlights and hero sections)
- **Navigation Icons**: 28px × 28px (main navigation elements)

**Icon Colours**

- **Primary Active**: Primary Green (#9FC045) for interactive icons
- **Secondary Inactive**: Neutral Gray (#757575) for inactive/neutral icons
- **Accent Blue**: Accent Blue (#1976D2) for informational icons
- **White**: White (#FFFFFF) for icons on dark backgrounds

**Icon Style**

- Outline style preferred for consistency
- 2px stroke width for optimal clarity
- Rounded line caps for friendly appearance

**Spacing System**

**Spacing Scale**

- **2px** - Micro spacing (between closely related elements)
- **4px** - Tiny spacing (internal component spacing)
- **8px** - Small spacing (internal padding, tight layouts)
- **12px** - Compact spacing (form elements, button padding)
- **16px** - Default spacing (standard margins and padding)
- **24px** - Medium spacing (between content sections)
- **32px** - Large spacing (major section separation)
- **48px** - Extra large spacing (hero sections, page margins)
- **64px** - Maximum spacing (major page divisions)

**Layout Grid**

**IMPORTANT: Standard Content Width Rule**

All content on the site follows a consistent width constraint:
- **Width**: 90% of viewport width
- **Max Width**: 1600px
- **Centering**: `margin-left: auto; margin-right: auto;`

This rule applies to:
- Header content (logo, navigation)
- All block content (text, grids, cards)
- Footer content
- Any element that should align with the main content area

**Full-Width Backgrounds with Contained Content**

When a section needs a full-width background (e.g., coloured backgrounds, images) but the content should stay within the standard width:
1. The outer container spans 100% viewport width (for the background)
2. An inner container constrains the content to 90%/1600px

```css
/* Outer wrapper - full width background */
.section-fullwidth {
    width: 100%;
    background-color: #example;
}

/* Inner wrapper - standard content width */
.section-fullwidth .section-inner {
    width: 90%;
    max-width: 1600px;
    margin: 0 auto;
}
```

**Breaking Out of Container (Edge-to-Edge)**

For elements that need to span the full viewport while inside a constrained container, use the negative margin technique:

```css
.full-viewport-width {
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
}
```

**Additional Layout Specifications**

- **Tablet**: 16px side margins
- **Mobile**: 16px side margins, width 90%
- **Grid Columns**: 12-column system with 24px gutters
- **Block Container Padding**: 0 20px (standard blocks)

**Motion and Animation**

**Standard Transitions**

- **Micro Interactions**: 150ms, ease-in-out (hover states, clicks)
- **Standard Transition**: 250ms, ease-out (general UI changes)
- **Emphasis Transition**: 400ms, cubic-bezier(0.25, 0.46, 0.45, 0.94) (important actions)
- **Page Transitions**: 500ms, cubic-bezier(0.2, 0.8, 0.2, 1) (navigation changes)

**Animation Principles**

- **Subtle Bounce**: Spring physics for playful interactions
- **Smooth Slides**: Linear movement for navigation
- **Gentle Fades**: Opacity changes for content loading
- **Progressive Loading**: Staggered animations for lists and cards

**Specific Animations**

- **Button Hover**: Scale 1.02, 150ms ease-out
- **Card Hover**: Translate Y -4px, Shadow increase, 250ms ease-out
- **Modal Open**: Scale from 0.9 to 1.0, Fade in, 300ms ease-out
- **Form Validation**: Shake animation for errors, 400ms

**Accessibility Standards**

**Contrast Ratios**

- **Normal Text**: Minimum 4.5:1 against background
- **Large Text**: Minimum 3:1 against background
- **Interactive Elements**: Minimum 3:1 for focus indicators

**Focus States**

- **Focus Ring**: 2px solid Primary Green (#9FC045)
- **Focus Offset**: 2px from element edge
- **Keyboard Navigation**: Clear focus progression through all interactive elements

**Touch Targets**

- **Minimum Size**: 44px × 44px for all interactive elements
- **Spacing**: Minimum 8px between adjacent touch targets
- **Mobile Optimization**: Increased padding for mobile interfaces

**Dark Mode Variants**

**Dark Mode Colours**

- **Dark Background Primary**: #121212
- **Dark Surface**: #1E1E1E (card backgrounds)
- **Dark Primary Green**: #B8D365 (adjusted for contrast)
- **Dark Text Primary**: #FFFFFF
- **Dark Text Secondary**: #B0BEC5
- **Dark Border**: #333333

**Dark Mode Implementation**

- Automatic system preference detection
- Manual toggle option in site header
- Consistent component behavior across modes
- Maintained accessibility standards in dark theme

**Print and Social Media Guidelines**

**Print Adaptations**

- **Logo**: High contrast black version for print materials
- **Typography**: Georgia preferred for formal documents
- **Colours**: CMYK equivalents provided for professional printing
- **Layout**: Single column layouts for optimal print readability

**Social Media Brand Assets**

- **Profile Images**: Simplified logo versions for small formats
- **Cover Images**: Brand photography with overlay text capabilities
- **Post Templates**: Consistent colour usage and typography hierarchy
- **Story Templates**: Mobile-optimized vertical layouts

**Custom Gutenberg Blocks**

The theme includes custom Gutenberg blocks with server-side rendering. All blocks follow consistent styling patterns.

**Block Container Standards**

All blocks follow the **Standard Content Width Rule** (see Layout Grid section):
- **Width**: 90% of viewport
- **Max Width**: 1600px
- **Horizontal Centering**: `margin-left: auto; margin-right: auto;`
- **Vertical Spacing**: 60px top and bottom margin
- **Padding**: 0 20px (horizontal, for text content)
- **Border Radius**: 16px (for blocks with backgrounds)

For blocks with full-width backgrounds:
- Outer container: 100% width (for background colour/image)
- Inner container: 90%/1600px (for content alignment)
- Use CSS classes: `.ihowz-width-full` for outer, `.ihowz-block-inner` for inner

**Available Blocks**

**Features Banner** (`ihowz/features-banner`)
- Full-width banner with background image/colour
- Eyebrow text, heading, description, and CTA button
- 16px border radius
- Supports wide and full alignment

**Hero Section** (`ihowz/hero`)
- Background image or video support
- Title, subtitle, description text
- Primary and secondary CTA buttons
- Optional floating info cards (left and right)
- Video autoplay with muted, loop, playsinline attributes
- Content aligned to top (flex-start)
- Max content width: 1200px

**Content with Sidebar** (`ihowz/content-with-sidebar`)
- Two-column layout with main content and sidebar
- Sidebar background: 25% primary green (rgba based)
- Flexible content areas using InnerBlocks

**Page Navigation** (`ihowz/page-navigation`)
- Hierarchical page list with depth control (1-5 levels)
- Options: show only children, select parent page, exclude pages
- Styled navigation buttons with green hover states
- Second level: 15% green fill background

**Solutions Grid** (`ihowz/solutions-grid`)
- Flexible grid with up to 4 rows, 5 items per row
- Optional eyebrow, heading, and top CTA button
- Per-item: image, title, button, width percentage
- MediaUpload with URL fallback for images

**About Stats** (`ihowz/about-stats`)
- 5-column CSS Grid layout
- Row 1: Eyebrow (cols 1-2), Main text (cols 3-5)
- Row 2: Stats in columns 1, 2, 3 (vertically centered), Large image (cols 4-5, spans rows 2-3)
- Row 3: Secondary text + button (cols 1-2), Small image (col 3)
- Stats counter animation using Intersection Observer
- Plain numbers animate from 0 when scrolled into view
- Supports prefixes ($, £), suffixes (+), and thousand separators
- Square images with aspect-ratio: 1/1

**Feedback/Testimonials** (`ihowz/feedback`)
- 3-column grid layout
- Header in column 1, row 1; testimonial cards fill remaining grid
- Up to 6 testimonial cards
- Card contents: orange quote icon, quote text, author photo, name, role
- Container: 5% gray background, 16px border radius, 50px 40px padding
- Decorative roof SVG background at 15% opacity, bottom-right aligned
- Author role displayed in accent gold (#FF8F00)

**Block Typography Standards**

- **Eyebrow Text**: 1.25rem, font-weight 500, Primary Green
- **Main/Heading Text**: 1.5rem - 2.5rem (clamp for responsiveness), font-weight 500-700
- **Body/Secondary Text**: 1rem, font-weight 400, line-height 1.7, Neutral Gray
- **Stat Numbers**: clamp(2.5rem, 5vw, 3.5rem), font-weight 700, Primary Charcoal
- **Stat Labels**: 0.875rem, font-weight 500, Neutral Gray (line 2: Light Gray)

**Block Button Standards**

- **CTA Buttons**: Accent Gold (#FF8F00) background, white text
- **Padding**: 12px 24px
- **Border Radius**: 8px
- **Font**: 0.9375rem, font-weight 600
- **Hover**: Darker gold (#e67e00), translateY(-2px), box-shadow
- **Icon**: 18px inline SVG arrow

**Block Image Standards**

- **Border Radius**: 16px
- **Overflow**: hidden (on container)
- **Object Fit**: cover
- **Square Images**: aspect-ratio: 1/1

**Block Responsive Breakpoints**

- **Desktop**: Full grid layouts
- **992px**: Simplified grids (2-3 columns)
- **768px**: Further reduced columns, stacked layouts
- **540px**: Single column layouts
- **480px**: Full-width elements

**Implementation Notes**

**Technical Specifications**

- **CSS Custom Properties**: All colours and spacing defined as CSS variables

**CSS Variables Used in Blocks**

```css
--primary-font: "Mona Sans", sans-serif
--primary-green: #9cc130
--primary-charcoal: #263238
--accent-gold: #FF8F00
--neutral-gray: #757575
--light-gray: #BDBDBD
```
- **Component Library**: Reusable components built with consistent styling
- **Responsive Breakpoints**:
    - Mobile: 0-767px
    - Tablet: 768-1023px
    - Desktop: 1024px+
- **Performance**: Optimized font loading and minimal animation impact

**Quality Assurance**

- **Cross-browser Testing**: Chrome, Firefox, Safari, Edge
- **Device Testing**: iOS and Android mobile devices
- **Accessibility Testing**: Screen reader compatibility and keyboard navigation
- **Performance Monitoring**: Core Web Vitals optimization

This design system creates a modern, professional, and trustworthy digital presence for iHowz while maintaining the established brand equity and ensuring excellent user experience across all touchpoints.
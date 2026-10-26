<div align="center">

![Extension icon](Resources/Public/Icons/Extension.png)

# TYPO3 extension `typo3_styleguide`

![TYPO3](https://img.shields.io/badge/TYPO3-12.0%20%7C%2013.0%20%7C%2014.0-orange.svg)
[![CGL](https://img.shields.io/github/actions/workflow/status/move-elevator/typo3-styleguide/cgl.yml?label=cgl&logo=github)](https://github.com/move-elevator/typo3-styleguide/actions/workflows/cgl.yml)
[![License](https://poser.pugx.org/move-elevator/typo3-styleguide/license)](LICENSE.md)

</div>

This extension provides several tools for a simple TYPO3 based styleguide.

![Screenshot](Documentation/Images/screenshot.jpg)

> [!NOTE]
> This extension is more of a best practice for implementing and maintaining an editorial style guide in TYPO3 with small reusable helpers.

## ✨ Features

* Dedicated content elements for colors, fonts, icons, images, a gallery, Do and Don't guidelines, element documentation with live examples, technical headlines and a table of contents for child pages
* Automatic table of contents for technical headlines within a page
* Rich backend previews for all styleguide content elements
* Custom page type for styleguide pages with restricted content element availability
* Backend layout for styleguide pages
* Optional frame with header, breadcrumb and side menu for the styleguide pages
* Optional restriction of frontend access to logged-in backend users
* Collection of TYPO3 ViewHelpers for reuse in templates

## 🔥 Installation

### Requirements

* TYPO3 12, 13 or 14
* PHP 8.2+

### Composer

[![Packagist](https://img.shields.io/packagist/v/move-elevator/typo3-styleguide?label=version&logo=packagist)](https://packagist.org/packages/move-elevator/typo3-styleguide)
![Packagist Downloads](https://img.shields.io/packagist/dt/move-elevator/typo3-styleguide?color=brightgreen)

``` bash
composer require move-elevator/typo3-styleguide
```

### Setup

Include static TypoScript template via the backend or import it:

```
@import 'EXT:typo3_styleguide/Configuration/TypoScript/setup.typoscript'
```

#### Frame (optional)

The frame adds a header, breadcrumb, title area and a neutral theme to the styleguide pages. There are two ways to use it, depending on how your project renders its pages.

**Projects without their own page template.** The static template **TYPO3 Styleguide Frame** replaces the page template and renders the complete page, including the side menu. Add it as an extension template on the styleguide start page, so it only applies to that page and everything below it. The constants `plugin.tx_typo3styleguide.frame.*` set the brand text, where the styleguide sits in the page tree and which columns (`colPos`) are shown.

**Projects with their own page template (PAGEVIEW, Site Sets).** A project registers its CSS and scripts in its own layout, so replacing the page would lose them. The site set `move-elevator/typo3-styleguide-frame` only adds the building blocks: the menus, the stylesheet and the partials. Add the set to the dependencies of the site and render the frame in the layout of the project:

```html
<f:render partial="StyleguideFrame/Chrome" arguments="{_all}" />
```

The partial renders the header, the breadcrumb and the title. The menus are available as `styleguideHome`, `styleguideSections`, `styleguidePages` and `styleguideBreadcrumb`. `styleguideTree` holds what is below the current entry of the header (pages, or groups with their pages) for the side menu, and `styleguidePages` the pages of the current area, which tells when there is something to show. Place it next to the content of the page. The content always sits in the same container, with or without the side menu, so its width does not jump between pages:

```html
<div class="typo3-styleguide-frame typo3-styleguide-frame--chrome">
    <div class="typo3-styleguide-frame__inner typo3-styleguide-frame__layout">
        <f:if condition="{styleguidePages}">
            <aside class="typo3-styleguide-frame__aside">
                <f:render partial="StyleguideFrame/SideNav" arguments="{items: styleguideTree}" />
            </aside>
        </f:if>
        <div class="typo3-styleguide-frame__main">
            <f:render section="Main" arguments="{_all}" />
        </div>
    </div>
</div>
```

The content of the page then sits in a narrower column. If your project lays out its content with a grid that the elements join (such as a `subgrid` with named lines), give `.typo3-styleguide-frame__main` the same grid in your own stylesheet, so the elements find their columns. The settings `plugin.tx_typo3styleguide.frame.*` of the site set have the same meaning as the constants. If the project lays out its `body` as a grid, the frame spans all columns.

On pages with the frame, the [Technical Headline](Documentation/ContentElement.md#technical-headline) shows as a plain heading and its table of contents as a one-line navigation, as in the design. The anchor link appears when the heading is hovered, and a navigation with a single entry is hidden. Pages without the frame keep the previous look.

The logo in the header comes from the settings `plugin.tx_typo3styleguide.frame.logo` and `logoDark` (the constants of the static template work the same way). Without a logo the brand text is shown. Without a dark logo the light one sits on a light background in the dark theme.

The side menu shows what is below the current entry of the header, so the entries of the header are not repeated: the pages of the area, or groups that open and close with the number of their pages. A filter field above narrows them down. A page below a group is one level deeper in the page tree. The side menu stays in view while the page scrolls. Below the active entry it lists the headings of the page (the Technical Headlines and the Preview and Fields parts of an Element Documentation) and marks the one in view while you scroll. A page with fewer than two headings shows none.

Dark mode follows the setting of the system (`prefers-color-scheme`) until a visitor uses the switch at the right of the header. The switch sets the theme and the browser remembers it. Without JavaScript the switch is not shown and the theme follows the system. The frame sets the custom properties of the theme for it, and the styleguide elements take their colors from the page, so a project that colors headings and links itself for a light page does not break it. The preview frames show your pages as they are, so they stay light unless your project has a dark mode.

To adapt colors, font and corners, override the custom properties of `.typo3-styleguide-frame`. Set the same properties in a `prefers-color-scheme: dark` block for the dark theme.

#### Fluid layout

The content elements use the Fluid layout `Default`, as `fluid_styled_content` provides it. If your project has none, the extension supplies a minimal one that wraps the element in `<div id="c<uid>">`, the anchor of `fluid_styled_content`. A layout of your project always takes precedence.

## 📙 Documentation

- [Content Elements](Documentation/ContentElement.md)
- [ViewHelpers](Documentation/ViewHelpers/CLASSES.md)
- [Backend Layout](Documentation/BackendLayout.md)
- [Page Type](Documentation/PageType.md)
- [Restrict Frontend Access to Backend Users](Documentation/BackendUserOnlyAccess.md)


## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## ⭐ License

This project is licensed
under [GNU General Public License 2.0 (or later)](LICENSE.md).

# Frame

The frame wraps styleguide pages in a header, a breadcrumb, a title and a side menu, with a neutral theme and a dark mode. It is optional: pages without it keep their previous look.

How to add it depends on how your project renders pages:

| Your project | Use | Section |
|--------------|-----|---------|
| Has no page template of its own | Static template **TYPO3 Styleguide Frame** | [Static template](#static-template) |
| Renders pages with its own layout (PAGEVIEW, Site Sets) | Site set `move-elevator/typo3-styleguide-frame` | [Site set](#site-set) |

## Static template

The template replaces the page template and renders the whole page, including the side menu. Add it as an extension template on the styleguide start page, so it applies to that page and everything below it. Set the [settings](#settings) as constants:

```typoscript
plugin.tx_typo3styleguide.frame {
  brand = Brand Guide
  logo = EXT:site_package/Resources/Public/Images/Logo/logo.svg
}
```

## Site set

A project registers its CSS and scripts in its own layout, so replacing the page would lose them. The set only adds the menus, the stylesheet and the partials.

1. Add the set to the dependencies of the site (`config/sites/<site>/config.yaml`):

   ```yaml
   dependencies:
     - move-elevator/typo3-styleguide-frame
   ```

2. Render the header, breadcrumb and title in your layout:

   ```html
   <f:render partial="StyleguideFrame/Chrome" arguments="{_all}" />
   ```

3. Place the side menu next to the content. The content always sits in the same container, with or without the menu, so its width does not jump between pages:

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

4. Set the [settings](#settings) in `config/sites/<site>/settings.yaml`:

   ```yaml
   plugin:
     tx_typo3styleguide:
       frame:
         brand: Brand Guide
         logo: 'EXT:site_package/Resources/Public/Images/Logo/logo.svg'
   ```

The set provides these variables to the layout:

| Variable | Content |
|----------|---------|
| `styleguideHome` | The start page, for the first entry of the header |
| `styleguideSections` | The sections in the header |
| `styleguidePages` | The pages of the current section. Empty when there is nothing for the side menu |
| `styleguideTree` | What is below the current section, for the side menu: pages, or groups with their pages |
| `styleguideBreadcrumb` | The rootline of the current page |
| `styleguideBrand`, `styleguideLogo`, `styleguideLogoDark` | The brand and logo settings |
| `styleguideBare` | `1` when the page is loaded by a [Page Preview](ContentElement.md#page-preview), empty otherwise |

If your content uses a grid that the elements join (for example a `subgrid` with named lines), give `.typo3-styleguide-frame__main` the same grid in your stylesheet, so the elements find their columns. If your `body` is a grid, the frame spans all columns.

For a Page Preview the page is loaded with `sg_page_preview`, and the Chrome partial renders nothing. Use `styleguideBare` in your layout to leave out the side menu and the container as well.

## Settings

The static template reads them as constants, the site set as settings. Both use the keys below `plugin.tx_typo3styleguide.frame`.

| Setting | Default | Meaning |
|---------|---------|---------|
| `brand` | `Styleguide` | Text in the header when there is no logo |
| `logo` | empty | `EXT:` path of the logo in the header |
| `logoDark` | empty | `EXT:` path of the logo for the dark theme. Without it the light logo sits on a light background |
| `rootLevel` | `1` | Level of the start page in the page tree. The site root is level 0 |
| `sectionEntryLevel` | `1` | Menu level of the sections in the header |
| `pageEntryLevel` | `2` | Menu level of the pages in the side menu |
| `colPos` | `0` | Comma-separated columns shown in the main area, in this order. Constant of the static template only |

## Side menu

The side menu shows what is below the current section of the header, so the header entries are not repeated: the pages of the section, or groups that open and close and show the number of their pages. A filter field narrows them down. On a screen of up to 768px width the menu starts closed behind a button, and the header shows the logo and the theme switch in one row with the navigation below it.

Below the active page the menu lists its headings: the [Technical Headlines](ContentElement.md#technical-headline) and the Preview and Fields parts of an [Element Documentation](ContentElement.md#element-documentation). The heading in view is marked while the page scrolls. A page with fewer than two headings lists none.

## Dark mode

The theme follows `prefers-color-scheme` until a visitor uses the switch at the right of the header. The browser remembers the choice. Without JavaScript the switch is hidden and the theme follows the system.

The frame sets the custom properties of the theme, and the styleguide elements take their colors from the page, so a project that colors headings and links for a light page does not break it. Preview frames show your pages as they are, so they stay light unless your project has a dark mode.

## Styling

Override the custom properties of `.typo3-styleguide-frame` to change colors, font and corners. Set the same properties in a `prefers-color-scheme: dark` block for the dark theme.

```css
.typo3-styleguide-frame {
    --typo3-sg-accent: #8abb2b;
    --typo3-sg-radius: 4px;
}
```

On pages with the frame, a Technical Headline shows as a plain heading and its table of contents as a one-line navigation. A navigation with one entry is hidden.

## Fluid layout of the elements

The content elements use the Fluid layout `Default`, as `fluid_styled_content` provides it. If your project has none, the extension supplies a minimal one that wraps the element in `<div id="c<uid>">`, the anchor of `fluid_styled_content`. A layout of your project always takes precedence.

# Content Elements

This extension provides the following content elements for building a styleguide:

- [Technical Headline](#technical-headline)
- [Colors](#colors)
- [Fonts](#fonts)
- [Icons](#icons)
- [Images](#images)

> [!TIP]
> All styleguide content elements are only available on the custom [Styleguide page type](PageType.md) and grouped under a dedicated **Styleguide** tab in the New Content Element wizard.

## Technical Headline

The `Technical Headline` content element creates a structural headline for your styleguide.

![technical-headline.png](Images/technical-headline.png)

> [!TIP]
> It automatically generates a table of contents for all technical headlines within the page, which can be used to navigate through the document.

![ce-technical-headline.jpeg](Images/ce-technical-headline.jpg)

Use the `Headline-Level` attribute to change the hierarchy of the headline. The default is `h2`, but you can also use `h3` or `h4`. A rich text field is available for additional body text below the headline.

## Colors

The `Colors` content element displays a color palette. Each color is defined as an inline record with:

- **Color** — HEX color value (e.g. `#EAE7E2`)
- **Label** — Optional name for the color (e.g. "cararra")

Colors are rendered as swatches with their HEX value and label.

## Fonts

The `Fonts` content element showcases available fonts. Each font is defined as an inline record with:

- **Font** — Font family name (e.g. `Lexend`)
- **Font Weight** — Optional weight value (e.g. `700`)
- **Label** — Optional display name

Each font entry renders a specimen text in the specified font family and weight.

## Icons

The `Icons` content element displays all icon files from a given directory. Configure it with:

- **Path** — An `EXT:` path pointing to the icon directory (e.g. `EXT:sitepackage/Resources/Public/Icons/`)

All files in the directory are rendered as icons with their filename as label.

## Images

The `Images` content element displays a collection of images. Each image is defined as an inline record with:

- **Path** — An `EXT:` path to the image file (e.g. `EXT:sitepackage/Resources/Public/Images/logo.svg`)
- **Caption** — Optional caption text

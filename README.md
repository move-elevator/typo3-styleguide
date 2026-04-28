<div align="center">

![Extension icon](Resources/Public/Icons/Extension.png)

# TYPO3 extension `typo3_styleguide`

[![Supported TYPO3 versions](https://badgen.net/badge/TYPO3/12%20&%2013%20&%2014/orange)](https://extensions.typo3.org/extension/typo3_styleguide)
[![CGL](https://img.shields.io/github/actions/workflow/status/move-elevator/typo3-styleguide/cgl.yml?label=cgl&logo=github)](https://github.com/move-elevator/typo3-styleguide/actions/workflows/cgl.yml)
[![License](https://poser.pugx.org/move-elevator/typo3-styleguide/license)](LICENSE.md)

</div>

This extension provides several tools for a simple TYPO3 based styleguide.

![Screenshot](Documentation/Images/screenshot.jpg)

> [!NOTE]
> This extension is more of a best practice for implementing and maintaining an editorial style guide in TYPO3 with small reusable helpers.

## ✨ Features

* Dedicated content elements for colors, fonts, icons, images and technical headlines
* Automatic table of contents for technical headlines
* Rich backend previews for all styleguide content elements
* Custom page type for styleguide pages with restricted content element availability
* Backend layout for styleguide pages
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

## 📙 Documentation

- [Content Elements](Documentation/ContentElement.md)
- [ViewHelpers](Documentation/ViewHelpers/CLASSES.md)
- [Backend Layout](Documentation/BackendLayout.md)
- [Page Type](Documentation/PageType.md)


## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## ⭐ License

This project is licensed
under [GNU General Public License 2.0 (or later)](LICENSE.md).

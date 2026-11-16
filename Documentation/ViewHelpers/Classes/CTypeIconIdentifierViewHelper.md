# CTypeIconIdentifierViewHelper

This ViewHelper returns the icon identifier that TYPO3 registers for a content type (`typeicon_classes` of `tt_content`). It returns an empty string when the type is empty or has no icon.

Usage:
```html
<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  xmlns:core="http://typo3.org/ns/TYPO3/CMS/Core/ViewHelpers"
  data-namespace-typo3-fluid="true"
>

<core:icon identifier="{sg:cTypeIconIdentifier(ctype: 'typo3styleguide_colors')}" size="default" />
```

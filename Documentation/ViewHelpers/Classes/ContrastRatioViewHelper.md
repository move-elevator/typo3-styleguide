# ContrastRatioViewHelper

This ViewHelper returns the WCAG 2.x contrast ratio of two colors, rounded down to two decimals so a ratio below a threshold is never displayed as reaching it.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
{sg:contrastRatio(color: '#ff5733', against: '#ffffff')}

```

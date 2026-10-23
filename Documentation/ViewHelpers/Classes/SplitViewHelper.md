# SplitViewHelper

This ViewHelper splits a comma separated string into its trimmed, non-empty parts.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
<f:for each="{sg:format.split(value: '400, 700')}" as="weight">{weight}</f:for>

```

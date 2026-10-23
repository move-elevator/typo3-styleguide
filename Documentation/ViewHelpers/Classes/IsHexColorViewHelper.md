# IsHexColorViewHelper

This ViewHelper tells whether a value is a six digit HEX color, so a template can skip invalid values instead of failing.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
<f:if condition="{sg:isHexColor(color: '#ff5733')}">
    <f:then>valid</f:then>
    <f:else>invalid</f:else>
</f:if>

```

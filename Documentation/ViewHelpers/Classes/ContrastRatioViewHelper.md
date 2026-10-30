# ContrastRatioViewHelper

This ViewHelper returns the WCAG 2.x contrast ratio of two colors, from `1.0` to `21.0`.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
<f:variable name="ratio" value="{sg:contrastRatio(color: '#ff5733', background: '#ffffff')}" />
<f:format.number decimals="2">{ratio}</f:format.number>:1
<f:if condition="{ratio} >= 4.5">AA</f:if>

```

The value is not rounded, so a comparison against a WCAG threshold never passes a pair that actually fails. Round only for display.

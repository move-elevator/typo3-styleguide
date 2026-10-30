# ContrastLevelViewHelper

This ViewHelper maps a contrast ratio to the WCAG 2.x level it reaches:

- `aaa` from 7:1
- `aa` from 4.5:1
- `large` from 3:1, which is enough for large text and UI components only
- `fail` below 3:1

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
<f:variable name="ratio" value="{sg:contrastRatio(color: '#ff5733', background: '#ffffff')}" />
<sg:contrastLevel ratio="{ratio}" />

```

Combine it with the [ContrastRatioViewHelper](ContrastRatioViewHelper.md) and translate the result, so the level is never conveyed by color alone.

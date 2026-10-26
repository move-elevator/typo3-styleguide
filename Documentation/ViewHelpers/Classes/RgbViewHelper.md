# RgbViewHelper

This ViewHelper converts a HEX color into its RGB channels, separated by a comma and a space.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
<sg:format.rgb color="#ff5733" />

```

Renders `255, 87, 51`. Only six digit values are accepted, anything else throws an `InvalidArgumentException`.

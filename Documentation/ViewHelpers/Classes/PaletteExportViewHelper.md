# PaletteExportViewHelper

This ViewHelper renders color records as SCSS variables, LESS variables or a JSON file in the design tokens format. Colors without a valid HEX value or variable name are skipped, and an empty string is returned when nothing can be exported.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
{sg:paletteExport(colors: colors, format: 'scss')}

```

Supported formats: `scss`, `less` and `json`.

# FilesViewHelper

This ViewHelper generates a list of files in a specified directory.

Usage:
```html

<html
  xmlns:sg="http://typo3.org/ns/MoveElevator/Styleguide/ViewHelpers"
  data-namespace-typo3-fluid="true"
>

<f:for each="{sg:files(path: path)}" as="file">
    {file}
</f:for>

```

The optional `exclude` argument takes a comma-separated list of glob patterns. Every file
whose name matches one of them is skipped. The wildcards `*` and `?` are supported.

```html

<f:for each="{sg:files(path: path, exclude: '*-alt.svg, legacy-*, *.json')}" as="file">
    {file}
</f:for>

```

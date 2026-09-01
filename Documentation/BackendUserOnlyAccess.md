# Restrict Frontend Access to Backend Users

A style guide is internal tooling by nature and usually not meant for public visitors. Instead of hiding pages, which
would also hide them from editors browsing the backend page tree, this extension can restrict frontend access of
selected pages to logged-in TYPO3 backend users, while keeping the pages active and browsable in the backend.

## Enabling the restriction

The feature is disabled by default and can be enabled via the extension configuration
(`Admin Tools` > `Settings` > `Extension Configuration` > `typo3_styleguide`):

* `restrictToBackendUsers`: Enables the restriction.
* `restrictedRootPageUids`: Comma-separated list of page UIDs. The configured pages and all pages beneath them in the
  page tree are restricted to logged-in backend users. Anonymous visitors receive a 403 response, rendered via the
  site's configured error handling for that status code.

If `restrictToBackendUsers` is disabled, or no page UIDs are configured, no page is affected.

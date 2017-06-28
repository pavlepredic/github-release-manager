# GitHub Release Manager

CLI tool for managing GitHub releases. Currently supports the following operations:

## Listing

`./grm ls <repo>`

`<repo>` is the GitHub repository identifier, in `user/repo` format, eg `pavlepredic/github-release-manager`

### Optional arguments

- `--token` or `-t` : GitHub access token (if the repository is private, you must provide a token)
- `--type` : filter by type (release, prerelease or draft)
- `--author` or `-a` : filter by author (using GitHub handle)
- `--created-before` : filter releases by creation date (only return releases created before the provided date)
- `--published-before` : filter releases by publish date (only return releases published before the provided date)

## Deleting in bulk

`./grm rm <repo> --token=<token>`

### Optional arguments

- `--token` or `-t` : GitHub access token (required)
- `--type` : filter by type (release, prerelease or draft)
- `--author` or `-a` : filter by author (using GitHub handle)
- `--created-before` : filter releases by creation date (only delete releases created before the provided date)
- `--published-before` : filter releases by publish date (only delete releases published before the provided date)
- `--with-tags` : also delete the associated tag (no value required)

## Examples

- List all releases in this repository:

`./grm ls pavlepredic/github-release-manager`

- List drafts:

`./grm ls pavlepredic/github-release-manager --type=draft`

- List releases made by me in this repository:

`./grm ls pavlepredic/github-release-manager --author pavlepredic`

- List releases made by me and published before `2017-06-28` in this repository:

`./grm ls pavlepredic/github-release-manager --author pavlepredic --published-before 2017-06-28`

- Delete drafts:

`./grm rm pavlepredic/github-release-manager --type=draft --token=<token>`

- Delete old releases:

`./grm rm pavlepredic/github-release-manager --published-before 2017-06-28 --token=<token>`

- Delete old releases along with associated tags:

`./grm rm pavlepredic/github-release-manager --published-before 2017-06-28 --token=<token> --with-tags`

## Installation

- `git clone https://github.com/pavlepredic/github-release-manager`
- `cd github-release-manager`
- `composer install`

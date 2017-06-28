# GitHub Release Manager

CLI tool for managing GitHub releases. Currently supports the following operations:

## Listing

`./console.php ls <repo>`

`<repo>` is the GitHub repository identifier, in `user/repo` format, eg `pavlepredic/github-release-manager`

### Optional arguments

- `--token` or `-t` : GitHub access token (if the repository is private, you must provide a token)
- `--author` or `-a` : filter by author (using GitHub handle)
- `--before` or `-b` : filter releases by date (only return releases older than the provided date)

## Deleting in bulk

`./console.php del <repo> --token=<token>`

### Optional arguments

- `--token` or `-t` : GitHub access token (required)
- `--author` or `-a` : filter by author (using GitHub handle)
- `--before` or `-b` : filter releases by date (only delete releases older than the provided date)
- `--force` or `-f` : force the operation without asking for confirmation

## Examples

- List all releases in this repository:

`./console.php ls pavlepredic/github-release-manager`

- List releases made by me in this repository:

`./console.php ls pavlepredic/github-release-manager --author pavlepredic`

- List releases made by me and published before `2017-06-28` in this repository:

`./console.php ls pavlepredic/github-release-manager --author pavlepredic --before 2017-06-28`

- Delete old releases:

`./console.php del pavlepredic/github-release-manager --before 2017-06-28 --token=<token>`

- Delete old releases along with associated tags:

`./console.php del pavlepredic/github-release-manager --before 2017-06-28 --token=<token> --with-tags`

- Delete old releases without asking for confirmation (dangerous):

`./console.php del pavlepredic/github-release-manager --before 2017-06-28 --force=true --token=<token>`

## Installation

- `git clone https://github.com/pavlepredic/github-release-manager`
- `cd github-release-manager`
- `composer install`

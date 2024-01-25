# MNEMO
> Command line mnemonic password generator.
> Written in PHP and compiled as PHAR.

## Requisites
Install box : https://github.com/box-project/box

## Build
```sh
# Clone this repository
$ composer install
$ box compile
$ chmod +x build/mnemo.phar
```

On Linux / MINGW / Git Bash / MacOS :
```sh
$ mv build/mnemo.phar /usr/local/bin/mnemo
```
On Windows : move to a suitable folder and add it to your PATH.

## Usage
```sh
$ mnemo                       # generates 5 random passwords
$ mnemo -d  (or --no-dashes)  # generates 5 random passwords without dashes
```

## Development

PRs welcome !

## Releases

1.0.0 - First release
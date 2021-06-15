# Knigavuhe Downloader
Downloads audio books from knigavuhe.org to disk.

## Description

The script supports interactive and non-interactive modes of operation.

To download, find the book you are interested in and copy the title from the link. 

Just call `php src/app.php --book=...` and the download process will start.

## Requirements

- PHP >= 7.3
- php-cURL
- php-xml
- php-json
- Composer

## Installation

1. Clone this repo to your local machine.
2. Install project dependencies:
```sh
$ composer install
```

## Using

```shell script
$ php src/app.php --book=... [OPTION]...
```

### Options:
- --book=... book name from url
- --non-interactive run without interactive shell
- --user-agent=... use your user agent

## Example

If you need download **Cujo** by **Stephen King**. 

You need to find this book on the site. (`https://knigavuhe.org/book/kudzho/`)

Call script:
```sh
$ php src/app.php --book=kudzho
```

Information about the book will appear:
```
Source: https://knigavuhe.org/book/kudzho/
Author: Стивен Кинг
Book name: Куджо
Reader: Светлана Раскатова
Time: 16:17:48
Count files: 116
Status: allowed
Continue? [Y/N] :
```

If this is what you expect, agree with the download.

The download process will start
```
Download: [  0/116] [=====     ] [ 51%] [   2.32Mb/   29.5Mb]
```

The audio book will be located in the `./download/{author} - {name}[{reader}]` folder



# Installation

### Install via composer
This is the recommended way to install blypo. First, install composer on your system, then run the following commands and install the extension through the extension manager afterwards

```shell
cd typo3conf/ext
mkdir blypo
cd blypo
composer require blypo/blypo
```
### Clone from Github
You can also clone blypo from github directly, but be aware that this also requires a working composer installation. Run the following commands, then install through the extension manager

```shell
cd typo3conf/ext
mkdir blypo
cd blypo
git clone https://github.com/blypo/blypo
composer install
```

### TER
Blypo will not be published on the TER, as Blypo relies heavily on composer features.

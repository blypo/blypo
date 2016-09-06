# Installation
Be aware that this requires a working [composer](https://getcomposer.org/) installation!

### Clone from Github
Run the following commands, then install through the TYPO3 extension manager

```bash
cd typo3conf/ext
mkdir blypo
cd blypo
git clone https://github.com/blypo/blypo .
composer install
```

### Download Release/Zip
* Download the latest release from [here](https://github.com/blypo/blypo/archive/master.zip)
* Create a folder named `blypo` in `typo3conf/ext`.
* unzip release to this folder
* `composer install`
* install through the TYPO3 extension manager

### TER
Blypo will not be published on the TER, as Blypo relies heavily on composer features.

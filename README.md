# Kallikratis Administrative Data PHP Library

A PHP library providing access to hierarchical administrative data for Greece, based on the Kallikratis reform.

## Features

## Data Structure
The library follows the administrative divisions defined by the Kallikratis reform:
- **Regions** (Περιφέρειες)
- **Regional Units** (Περιφερειακές Ενότητες)
- **Municipalities** (Δήμοι)
- **Municipal Units** (Δημοτικές Ενότητες)
- **Communities** (Κοινότητες)
  - Municipal Communities (Δημοτικές Κοινότητες)
  - Local Communities (Τοπικές Κοινότητες)


## Installation

```bash
composer require achilleskal/kallikratis
```

## Usage
```php
use Kallikratis\Repository\KallikratisRepository;


$repository = new KallikratisRepository();

foreach ($repository->allRegions() as $region) {
    echo $region->name;
}
```

## Contributing

Contributions are welcome!

For major changes, please open an issue first to discuss what you would like to change or improve.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

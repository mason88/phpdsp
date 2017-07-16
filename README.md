## PHP-DSP

Digital Signal Processing library for PHP is a set of classes for creating and analyzing digital signals. It contains operations for converting between continuous signals to discrete signals, and to move data back and forth from time domain to frequency domain.

Its classes and code are ported from Python code in "Think DSP," a great book by Allen B. Downey (http://greenteapress.com/wp/think-dsp/).

Due to limitations in PHP language, DFT is not supported, while DCT is.

This library uses NumPHP library (https://numphp.org/) for running matrix and vector operations.

For complete documentation and examples, please visit: http://masonlee.info/phpdsp

## Installation

Using Composer:
```sh
$ composer require mason88/phpdsp
```

## Dependencies

- numphp/numphp: https://github.com/NumPHP/NumPHP
This library is included directly into this repository and contains custom modifications for this project.

## License
http://www.gnu.org/licenses/gpl.html V3

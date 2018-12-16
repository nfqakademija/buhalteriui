<p align="center">
    <a href="http://nfqakademija.lt/" target="_blank"><img src="https://avatars0.githubusercontent.com/u/4995607?v=3&s=100"></a><a href="https://symfony.com" target="_blank">
    <img src="https://symfony.com/logos/symfony_black_02.svg" alt="">
</a></p>

# Pagalba buhalteriui

[![Build Status](https://api.travis-ci.com/nfqakademija/buhalteriui.svg?branch=master)](https://travis-ci.com/nfqakademija/buhalteriui/)
============

# Turinys
- [Projekto aprašymas](#projekto-aprašymas)
- [Reikalavimai](#reikalavimai)
- [Paleidimo instrukcija](#paleidimo-instrukcija)


# Projekto aprašymas

### Problema
Mažų ir vidutinių įmonių buhalteriai susiduria su skenuotų pirkimo sąskaitų duomenų perkelimų į buhalterinę programą.
Taip pat atlieka papildomus įkeltų duomenų tikrinimus.
 		
### Tikslas
Paruošti prototipą vienos rūšies ir formato "Kesko senukai" pirkimo sąskaitoms nuskaityti, išsaugoti ir eksportuoti sutartu formatu.
			
### Sprendimas			
Tinklapis, kuris pagal iš anksto paruoštą duomenų nuskaitymo šabloną, iš skenuotų sąskaitų nuskaito duomenis, išsaugo į duomenų bazę ir paruošia sėkmingai nuskaitytus duomenis eksportavimui CSV formatu.			

# Reikalavimai
* tesseract: `>=3.05`


# Paleidimo instrukcija

```bash
  $ git clone <project>
  $ cd path/to/<project>
  $ composer install 
  $ php bin/console doctrine:database:create
  $ php bin/console doctrine:schema:update --force
```
Nuskaitymo šablonų duomenų įkėlimas
```bash
    $ php bin/console doctrine:fixtures:load
``` 
Tesseract įdiegimas
```bash
sudo apt-get install tesseract-ocr
``` 
Tesseract LIT kalbos įdiegimas
```bash
sudo apt-get install tesseract-ocr-lit
``` 




    

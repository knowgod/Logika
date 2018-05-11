GAME
----
Guess the number (Logika)
=========================

This is console text mode game. Written in PHP.
Your goal is to guess the computer's secret number.


Usage:

	./bin/logika [digits_count]
 
**_digits_count_** is non-mandatory parameter which defines the length of secret number.
    Default **_digits_count_=2**.

Digits are unique in the secret number.
After each your guess computer will report the result as "\<_correct_\>-\<_positioned_\>". 
"\<_correct_\>" means the number of correctly guessed digits; 
"\<_positioned_\>" means the number of digits that place the correct positions in secret number.
So always "\<_positioned_\> <= \<_correct_\>".

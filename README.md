GAME
----
Guess the number (Logika)
=========================

This is console text mode game. Written in PHP.
Your goal is to guess the computer's secret number.


Usage:

	<p>Logika.php [N]</p>
 
	N is non-mandatory parameter wich defines the length of secret number. Initially (if omited) N=2.

Digits are unique in the secret number.
After each your guess computer will report the resul as "C-P". 
"C" means the number of correctly guessed digits; 
"P" means the number of digits that place the corect positions in secret number.
So always "P <= C".

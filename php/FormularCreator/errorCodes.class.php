<?php
	namespace FormularGenerator;
 /**
  * Enumeration der möglichen ValidationFehler
  */
		abstract class errorCodes
		{
			const ERR_REQUIRED = 0;
			const ERR_FORMAT = 1;
			const ERR_MAXLENGTH = 2;
			const ERR_NO_ERROR = 3;
			const ERR_PASSWORD = 4;
		}
  ?>
<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler\Buffer;

/**
 * A destructor object to call close() for us at the end of the request.
 */
class Destructor {
	public function __destruct () {
		\SedoxWPCatalogue\Dependencies\Analog\Handler\Buffer::close ();
	}
}
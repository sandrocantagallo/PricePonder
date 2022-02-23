<?php

if (unlink('PS4_AGCM_19902_EISE_IBL.doc')) {
	echo "file eliminato";
} else {
	echo "impossibile trovare il file";
}

?>
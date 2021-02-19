The 'helpers' folder contains functions that are called only when needed in the PHP files.

An example below:

require_once('helpers/getTermsInOrder.php');
$variable = getTermsInOrder('taxonomy', $listOfTerms);

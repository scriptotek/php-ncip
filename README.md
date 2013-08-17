Basic NCIP php library
======================

Currently implementing only a small subset of the NCIP services.

Example:

	$ncip = new Ncip($service_url, $agency_id);
	$response = $ncip->lookupUser($user_id);
	if ($response['exists']) {
		echo 'Hello ' . $response['firstname'] . ' ' . $response['lastname'];
	else:
		echo 'User not found';

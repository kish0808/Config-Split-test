<?php
ini_set('memory_limit', '1024M');
$file_storage = \Drupal::entityManager()->getStorage('file');

// Only delete temporary files if older than $age. Note that automatic cleanup
// is disabled if $age set to 0.
$fids = Drupal::entityQuery('file')
  ->condition('status', FILE_STATUS_PERMANENT, '<>')
  ->execute();
$files = $file_storage->loadMultiple($fids);
foreach ($files as $file) {
  $references = \Drupal::service('file.usage')->listUsage($file);
  if (empty($references)) {
	if (!file_exists($file->getFileUri())) {
	  if (!file_valid_uri($file->getFileUri())) {
		\Drupal::logger('file system')->warning('Temporary file "%path" that was deleted during garbage collection did not exist on the filesystem. This could be caused by a missing stream wrapper.', ['%path' => $file->getFileUri()]);
	  }
	  else {
		\Drupal::logger('file system')->warning('Temporary file "%path" that was deleted during garbage collection did not exist on the filesystem.', ['%path' => $file->getFileUri()]);
	  }
	}
	// Delete the file entity. If the file does not exist, this will
	// generate a second notice in the watchdog.
	$file->delete();
  }
  else {
	\Drupal::logger('file system')->info('Did not delete temporary file "%path" during garbage collection because it is in use by the following modules: %modules.', ['%path' => $file->getFileUri(), '%modules' => implode(', ', array_keys($references))]);
  }
}
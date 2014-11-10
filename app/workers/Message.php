<?php
/**
 * message worker
 */
class MessageWorker {

	public function fire($job, $data) {
		File::append(app_path() . '/test.md', 'test worker' . PHP_EOL);
		$job->delete();
	}
}
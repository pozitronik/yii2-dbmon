<?php
declare(strict_types = 1);

namespace pozitronik\dbmon\models;

use Throwable;
use Yii;

/**
 * Class DbMonitor
 * Прямой мониторинг и работа с базой.
 * Основная идея - видеть, кто и что крутит, с возможностью прибития
 */
class DbMonitor {
	/**
	 * @return ProcessListItem[]
	 * @throws Throwable
	 */
	public static function ProcessList():array {
		$result = [];
		try {
			$pList = Yii::$app->db->createCommand('SHOW FULL PROCESSLIST')->queryAll();
		} catch (Throwable) {
			$pList = [];
		}
		$debugInfo = new SqlDebugInfo();
		foreach ($pList as $process) {
			$debugInfo->getFromSql($process['Info']);
			$result[] = new ProcessListItem([
				'id' => $process['Id'],
				'db' => $process['db'],
				'command' => $process['Command'],
				'time' => $process['Time'],
				'state' => $process['State'],
				'query' => $process['Info'],
				'user_id' => $debugInfo->user_id,
				'operation' => $debugInfo->operation
			]);
		}
		return $result;
	}

	/**
	 * @param int $process_id
	 * @return null|int Affected rows count, null on error
	 */
	public static function kill(int $process_id):?int {
		try {
			return Yii::$app->db->createCommand("kill {$process_id}")->execute();
		} catch (Throwable) {
			return null;
		}
	}
}